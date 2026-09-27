<?php

namespace App\Filament\Resources\ProductVariants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductVariantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('product.name')
                    ->label('Produkt')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('material.name')
                    ->label('Materiał')
                    ->sortable(),

                ColorColumn::make('color.hex')
                    ->label('Kolor'),

                TextColumn::make('color.name')
                    ->label('Nazwa koloru')
                    ->sortable(),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),

                TextColumn::make('price')
                    ->label('Cena wariantu')
                    ->money('PLN')
                    ->placeholder('Cena produktu')
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label('Promocja')
                    ->money('PLN')
                    ->placeholder('—'),

                TextColumn::make('stock_quantity')
                    ->label('Stan')
                    ->sortable(),

                TextColumn::make('weight_grams')
                    ->label('Waga')
                    ->suffix(' g')
                    ->placeholder('—'),

                IconColumn::make('is_active')
                    ->label('Aktywny')
                    ->boolean(),
            ])

            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Tylko aktywne')
                    ->falseLabel('Tylko nieaktywne')
                    ->placeholder('Wszystkie'),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
