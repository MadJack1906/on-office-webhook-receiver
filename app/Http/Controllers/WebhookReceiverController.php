<?php

namespace App\Http\Controllers;

use App\Jobs\ForwardWebhook;
use App\Models\DeliveryAttempt;
use App\Models\ReceivedWebhook;
use App\Models\WebhookEndpoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookReceiverController extends Controller
{
    public function __invoke(Request $request, string $slug): JsonResponse
    {
        $endpoint = WebhookEndpoint::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $body = $request->getContent();

        if (! $this->signatureValid($endpoint, $request, $body)) {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $webhook = ReceivedWebhook::create([
            'webhook_endpoint_id' => $endpoint->id,
            'method' => $request->method(),
            'ip' => $request->ip(),
            'content_type' => $request->header('Content-Type'),
            'headers' => $request->headers->all(),
            'body' => $body,
            'query' => $request->query->all() ?: null,
            'status' => ReceivedWebhook::STATUS_RECEIVED,
        ]);

        $targets = $endpoint->deliveryTargets()->where('is_active', true)->get();

        foreach ($targets as $target) {
            $attempt = DeliveryAttempt::create([
                'received_webhook_id' => $webhook->id,
                'delivery_target_id' => $target->id,
                'status' => DeliveryAttempt::STATUS_PENDING,
            ]);

            ForwardWebhook::dispatch($attempt->id);
        }

        return response()->json([
            'id' => $webhook->id,
            'status' => 'accepted',
            'targets' => $targets->count(),
        ], 202);
    }

    private function signatureValid(WebhookEndpoint $endpoint, Request $request, string $body): bool
    {
        if (empty($endpoint->secret)) {
            return true;
        }

        $header = $endpoint->signature_header ?: 'X-Webhook-Signature';
        $signature = $request->header($header);

        if (empty($signature)) {
            return false;
        }

        // Accept raw hex digest or "sha256=<digest>" (GitHub-style).
        $signature = preg_replace('/^sha256=/', '', trim($signature));

        $expected = hash_hmac('sha256', $body, $endpoint->secret);

        return hash_equals($expected, $signature);
    }
}
