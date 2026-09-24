<?php

namespace App\Filament\Admin\Resources\DeliveryTargets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeliveryTargetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('endpoint.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->limit(50)
                    ->copyable(),
                TextColumn::make('timeout')
                    ->suffix('s')
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
