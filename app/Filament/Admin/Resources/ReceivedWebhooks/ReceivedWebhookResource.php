<?php

namespace App\Filament\Admin\Resources\ReceivedWebhooks;

use App\Filament\Admin\Resources\ReceivedWebhooks\Pages\ListReceivedWebhooks;
use App\Filament\Admin\Resources\ReceivedWebhooks\Pages\ViewReceivedWebhook;
use App\Filament\Admin\Resources\ReceivedWebhooks\Schemas\ReceivedWebhookInfolist;
use App\Filament\Admin\Resources\ReceivedWebhooks\Tables\ReceivedWebhooksTable;
use App\Models\ReceivedWebhook;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReceivedWebhookResource extends Resource
{
    protected static ?string $model = ReceivedWebhook::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static ?string $navigationLabel = 'Received Webhooks';

    public static function infolist(Schema $schema): Schema
    {
        return ReceivedWebhookInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReceivedWebhooksTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReceivedWebhooks::route('/'),
            'view' => ViewReceivedWebhook::route('/{record}'),
        ];
    }
}
