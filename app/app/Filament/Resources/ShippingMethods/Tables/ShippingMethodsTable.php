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
                    ->label('A')
                    ->money('PLN', locale: 'pl')
                    ->sortable(),

                TextColumn::make('price_b')
                    ->label('B')
                    ->money('PLN', locale: 'pl')
                    ->placeholder('—'),

                TextColumn::make('price_c')
                    ->label('C')
                    ->money('PLN', locale: 'pl')
                    ->placeholder('—'),

                TextColumn::make('price_large')
                    ->label('Duża')
                    ->money('PLN', locale: 'pl')
                    ->placeholder('—'),

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
