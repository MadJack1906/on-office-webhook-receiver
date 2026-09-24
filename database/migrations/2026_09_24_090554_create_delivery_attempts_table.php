<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('received_webhook_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_target_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('attempt')->default(1);
            $table->string('status')->default('pending')->comment('pending|success|failed');
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->longText('response_body')->nullable();
            $table->text('error')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['received_webhook_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_attempts');
    }
};
