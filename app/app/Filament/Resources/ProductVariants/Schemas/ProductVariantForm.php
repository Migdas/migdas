<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Wariant produktu')
                    ->schema([
                        Select::make('product_id')
                            ->label('Produkt')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('material_id')
                            ->label('Materiał')
                            ->relationship('material', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('color_id')
                            ->label('Kolor')
                            ->relationship('color', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('sku')
                            ->label('SKU wariantu')
                            ->placeholder('np. MIG-WAVE-PLA-BLK')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Toggle::make('is_active')
                            ->label('Wariant aktywny')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->label('Kolejność')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(2),

                Section::make('Cena')
                    ->description('Jeśli cena wariantu jest pusta, sklep będzie korzystał z ceny produktu głównego.')
                    ->schema([
                        TextInput::make('price')
                            ->label('Cena wariantu')
                            ->numeric()
                            ->prefix('zł')
                            ->minValue(0),

                        TextInput::make('sale_price')
                            ->label('Cena promocyjna wariantu')
                            ->numeric()
                            ->prefix('zł')
                            ->minValue(0),
                    ])
                    ->columns(2),

                Section::make('Magazyn i druk')
                    ->schema([
                        TextInput::make('stock_quantity')
                            ->label('Stan magazynowy')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),

                        TextInput::make('weight_grams')
                            ->label('Waga wydruku')
                            ->numeric()
                            ->suffix('g')
                            ->minValue(0),

                        TextInput::make('lead_time_days')
                            ->label('Czas realizacji')
                            ->numeric()
                            ->suffix('dni')
                            ->minValue(0)
                            ->helperText('Puste = czas realizacji z produktu głównego.'),
                    ])
                    ->columns(3),

            ]);
    }
}
