<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'product_name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([

                TextColumn::make('product_name')
                    ->label('Produkt')
                    ->weight('bold'),

                TextColumn::make('material_name')
                    ->label('Materiał'),

                TextColumn::make('color_name')
                    ->label('Kolor'),

                TextColumn::make('variant_sku')
                    ->label('SKU'),

                TextColumn::make('quantity')
                    ->label('Ilość'),

                TextColumn::make('unit_price')
                    ->label('Cena szt.')
                    ->money('PLN'),

                TextColumn::make('total')
                    ->label('Razem')
                    ->money('PLN'),

            ]);
    }
}
