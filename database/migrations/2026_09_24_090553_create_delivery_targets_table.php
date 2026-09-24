<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_endpoint_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('url');
            $table->json('headers')->nullable()->comment('Extra headers sent with each forwarded request');
            $table->string('secret')->nullable()->comment('If set, an X-Webhook-Signature HMAC of the body is sent');
            $table->unsignedSmallInteger('timeout')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_targets');
    }
};
