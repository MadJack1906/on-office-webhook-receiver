<?php

namespace App\Filament\Admin\Resources\WebhookEndpoints\Pages;

use App\Filament\Admin\Resources\WebhookEndpoints\WebhookEndpointResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebhookEndpoint extends CreateRecord
{
    protected static string $resource = WebhookEndpointResource::class;
}
