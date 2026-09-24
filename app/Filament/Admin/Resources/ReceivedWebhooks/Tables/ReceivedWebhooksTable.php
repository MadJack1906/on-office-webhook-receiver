<?php

namespace App\Filament\Admin\Resources\ReceivedWebhooks\Tables;

use App\Jobs\ForwardWebhook;
use App\Models\DeliveryAttempt;
use App\Models\ReceivedWebhook;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReceivedWebhooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('endpoint.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('method')
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        ReceivedWebhook::STATUS_DELIVERED => 'success',
                        ReceivedWebhook::STATUS_PARTIALLY_FAILED => 'warning',
                        ReceivedWebhook::STATUS_FAILED => 'danger',
                        ReceivedWebhook::STATUS_DELIVERING => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('attempts_count')
                    ->counts('attempts')
                    ->label('Attempts'),
                TextColumn::make('ip')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        ReceivedWebhook::STATUS_RECEIVED => 'Received',
                        ReceivedWebhook::STATUS_DELIVERING => 'Delivering',
                        ReceivedWebhook::STATUS_DELIVERED => 'Delivered',
                        ReceivedWebhook::STATUS_PARTIALLY_FAILED => 'Partially failed',
                        ReceivedWebhook::STATUS_FAILED => 'Failed',
                    ]),
                SelectFilter::make('webhook_endpoint_id')
                    ->relationship('endpoint', 'name')
                    ->label('Endpoint'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('redeliver')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (ReceivedWebhook $record) {
                        $record->endpoint->deliveryTargets()
                            ->where('is_active', true)
                            ->each(function ($target) use ($record) {
                                $attempt = DeliveryAttempt::create([
                                    'received_webhook_id' => $record->id,
                                    'delivery_target_id' => $target->id,
                                    'status' => DeliveryAttempt::STATUS_PENDING,
                                ]);

                                ForwardWebhook::dispatch($attempt->id);
                            });

                        $record->refreshStatus();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
