<?php

namespace App\Filament\Admin\Resources\WebhookEndpoints\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WebhookEndpointsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('inbound_url')
                    ->label('Inbound URL')
                    ->getStateUsing(fn ($record) => $record->inboundUrl())
                    ->copyable()
                    ->icon('heroicon-o-clipboard'),
                TextColumn::make('received_webhooks_count')
                    ->counts('receivedWebhooks')
                    ->label('Received')
                    ->sortable(),
                TextColumn::make('delivery_targets_count')
                    ->counts('deliveryTargets')
                    ->label('Targets')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
