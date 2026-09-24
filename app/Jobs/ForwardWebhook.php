<?php

namespace App\Jobs;

use App\Models\DeliveryAttempt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class ForwardWebhook implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var int[] seconds between retries */
    public array $backoff = [10, 60];

    public function __construct(
        public int $attemptId,
    ) {}

    public function handle(): void
    {
        $attempt = DeliveryAttempt::with(['webhook', 'target'])->find($this->attemptId);

        if (! $attempt || ! $attempt->webhook || ! $attempt->target) {
            return;
        }

        $webhook = $attempt->webhook;
        $target = $attempt->target;

        $headers = collect($webhook->headers ?? [])
            ->map(fn ($v) => is_array($v) ? implode(', ', $v) : $v)
            ->except([
                'host', 'content-length', 'connection', 'accept-encoding',
                'x-forwarded-for', 'x-forwarded-host', 'x-forwarded-proto',
                strtolower($webhook->endpoint->signature_header ?: 'x-webhook-signature'),
            ])
            ->merge($target->headers ?? []);


        if (! empty($target->secret)) {
            $headers['X-Webhook-Signature'] = 'sha256='.hash_hmac('sha256', $webhook->body ?? '', $target->secret);
        }

        $headers['X-Webhook-Id'] = (string) $webhook->id;
        $headers['X-Webhook-Attempt'] = (string) $attempt->attempt;

        $started = hrtime(true);

        try {
            $response = Http::withHeaders($headers->all())
                ->timeout($target->timeout ?: 10)
                ->withBody($webhook->body ?? '', $webhook->content_type ?: 'application/json')
                ->send($webhook->method === 'GET' ? 'POST' : $webhook->method, $target->url, [
                    'query' => $webhook->query ?? [],
                ]);

            $attempt->update([
                'status' => $response->successful()
                    ? DeliveryAttempt::STATUS_SUCCESS
                    : DeliveryAttempt::STATUS_FAILED,
                'response_status' => $response->status(),
                'response_body' => mb_substr($response->body(), 0, 65535),
                'error' => $response->successful() ? null : 'HTTP '.$response->status(),
                'duration_ms' => (int) ((hrtime(true) - $started) / 1e6),
                'delivered_at' => now(),
            ]);

            if (! $response->successful()) {
                $this->failOrRetry($attempt);
            }
        } catch (\Throwable $e) {
            $attempt->update([
                'status' => DeliveryAttempt::STATUS_FAILED,
                'error' => mb_substr($e->getMessage(), 0, 2000),
                'duration_ms' => (int) ((hrtime(true) - $started) / 1e6),
            ]);

            $this->failOrRetry($attempt);
        }

        $webhook->refreshStatus();
    }

    private function failOrRetry(DeliveryAttempt $attempt): void
    {
        if ($this->attempts() < $this->tries) {
            // Mark pending again and re-dispatch with backoff.
            $attempt->update([
                'status' => DeliveryAttempt::STATUS_PENDING,
                'attempt' => $attempt->attempt + 1,
            ]);

            self::dispatch($attempt->id)->delay($this->backoff[$this->attempts() - 1] ?? 60);
        }
    }
}
