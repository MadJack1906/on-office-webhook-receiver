<?php

namespace App\Filament\Admin\Resources\DeliveryTargets;

use App\Filament\Admin\Resources\DeliveryTargets\Pages\CreateDeliveryTarget;
use App\Filament\Admin\Resources\DeliveryTargets\Pages\EditDeliveryTarget;
use App\Filament\Admin\Resources\DeliveryTargets\Pages\ListDeliveryTargets;
use App\Filament\Admin\Resources\DeliveryTargets\Schemas\DeliveryTargetForm;
use App\Filament\Admin\Resources\DeliveryTargets\Tables\DeliveryTargetsTable;
use App\Models\DeliveryTarget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeliveryTargetResource extends Resource
{
    protected static ?string $model = DeliveryTarget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DeliveryTargetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryTargetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeliveryTargets::route('/'),
            'create' => CreateDeliveryTarget::route('/create'),
            'edit' => EditDeliveryTarget::route('/{record}/edit'),
        ];
    }
}
