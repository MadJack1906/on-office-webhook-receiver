<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('received_webhooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_endpoint_id')->constrained()->cascadeOnDelete();
            $table->string('method', 10);
            $table->string('ip', 45)->nullable();
            $table->string('content_type')->nullable();
            $table->json('headers');
            $table->longText('body')->nullable();
            $table->json('query')->nullable();
            $table->string('status')->default('received')->comment('received|delivering|delivered|partially_failed|failed');
            $table->timestamps();

            $table->index(['webhook_endpoint_id', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('received_webhooks');
    }
};
