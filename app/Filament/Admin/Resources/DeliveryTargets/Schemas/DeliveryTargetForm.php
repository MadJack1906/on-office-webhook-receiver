<?php

namespace App\Filament\Admin\Resources\DeliveryTargets\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DeliveryTargetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('webhook_endpoint_id')
                    ->relationship('endpoint', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('url')
                    ->required()
                    ->url()
                    ->maxLength(2048),
                KeyValue::make('headers')
                    ->keyLabel('Header')
                    ->valueLabel('Value')
                    ->helperText('Extra headers sent with each forwarded request.'),
                TextInput::make('secret')
                    ->password()
                    ->revealable()
                    ->helperText('If set, an X-Webhook-Signature HMAC-SHA256 header is sent.')
                    ->maxLength(255),
                TextInput::make('timeout')
                    ->numeric()
                    ->default(10)
                    ->minValue(1)
                    ->maxValue(120)
                    ->suffix('s'),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
