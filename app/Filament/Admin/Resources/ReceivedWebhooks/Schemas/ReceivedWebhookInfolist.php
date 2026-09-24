<?php

namespace App\Filament\Admin\Resources\ReceivedWebhooks\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReceivedWebhookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('id'),
                        TextEntry::make('endpoint.name'),
                        TextEntry::make('method')->badge(),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('ip'),
                        TextEntry::make('content_type'),
                        TextEntry::make('created_at')->dateTime(),
                    ]),
                Section::make('Headers')
                    ->collapsed()
                    ->schema([
                        KeyValueEntry::make('headers')
                            ->keyLabel('Header')
                            ->valueLabel('Value'),
                    ]),
                Section::make('Query')
                    ->collapsed()
                    ->visible(fn ($record) => ! empty($record->query))
                    ->schema([
                        KeyValueEntry::make('query'),
                    ]),
                Section::make('Body')
                    ->schema([
                        TextEntry::make('body')
                            ->prose()
                            ->markdown(false)
                            ->columnSpanFull(),
                    ]),
                Section::make('Delivery attempts')
                    ->schema([
                        RepeatableEntry::make('attempts')
                            ->schema([
                                TextEntry::make('target.name')->label('Target'),
                                TextEntry::make('attempt')->label('#'),
                                TextEntry::make('status')->badge(),
                                TextEntry::make('response_status')->label('HTTP'),
                                TextEntry::make('duration_ms')->suffix(' ms'),
                                TextEntry::make('error')->placeholder('—'),
                                TextEntry::make('delivered_at')->dateTime()->placeholder('—'),
                            ])
                            ->columns(7)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
