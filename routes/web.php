<?php

use App\Http\Controllers\WebhookReceiverController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::match(['get', 'post', 'put', 'patch', 'delete'], '/webhooks/{slug}', WebhookReceiverController::class)
    ->name('webhooks.receive');
