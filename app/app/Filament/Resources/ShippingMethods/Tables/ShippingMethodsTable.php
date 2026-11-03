<?php

namespace App\Filament\Resources\ShippingMethods\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ShippingMethodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nazwa')
                    ->description(fn ($record) => $record->description)
                    ->weight('bold'),

                TextColumn::make('price')
                    ->label('Cena')
                    ->money('PLN', locale: 'pl')
                    ->sortable(),

                IconColumn::make('requires_point')
                    ->label('Do punktu')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Kolejność')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Dostępna'),
            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->defaultSort('sort_order');
    }
}
