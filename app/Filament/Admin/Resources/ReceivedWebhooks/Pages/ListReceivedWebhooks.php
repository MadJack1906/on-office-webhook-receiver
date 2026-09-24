<?php

namespace App\Filament\Admin\Resources\ReceivedWebhooks\Pages;

use App\Filament\Admin\Resources\ReceivedWebhooks\ReceivedWebhookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReceivedWebhooks extends ListRecords
{
    protected static string $resource = ReceivedWebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
