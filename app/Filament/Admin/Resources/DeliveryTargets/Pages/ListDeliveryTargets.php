<?php

namespace App\Filament\Admin\Resources\DeliveryTargets\Pages;

use App\Filament\Admin\Resources\DeliveryTargets\DeliveryTargetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryTargets extends ListRecords
{
    protected static string $resource = DeliveryTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
