<?php

namespace App\Filament\Admin\Resources\DeliveryTargets\Pages;

use App\Filament\Admin\Resources\DeliveryTargets\DeliveryTargetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryTarget extends EditRecord
{
    protected static string $resource = DeliveryTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
