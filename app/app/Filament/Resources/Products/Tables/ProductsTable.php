<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('main_image')
                    ->label('Zdjęcie')
                    ->disk('public')
                    ->square()
                    ->size(60),

                TextColumn::make('name')
                    ->label('Nazwa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('price')
                    ->label('Cena')
                    ->money('PLN')
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label('Promocja')
                    ->money('PLN')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('lead_time_days')
                    ->label('Realizacja')
                    ->suffix(' dni')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktywny')
                    ->boolean(),

                IconColumn::make('commercial_use_allowed')
                    ->label('Licencja komercyjna')
                    ->boolean(),

                TextColumn::make('license_type')
                    ->label('Licencja')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dodano')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Produkt aktywny')
                    ->trueLabel('Tylko aktywne')
                    ->falseLabel('Tylko nieaktywne')
                    ->placeholder('Wszystkie'),

                TernaryFilter::make('commercial_use_allowed')
                    ->label('Sprzedaż dozwolona')
                    ->trueLabel('Tak')
                    ->falseLabel('Nie')
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
