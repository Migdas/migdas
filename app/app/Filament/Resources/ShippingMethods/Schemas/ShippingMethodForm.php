<?php

namespace App\Filament\Resources\ShippingMethods\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ShippingMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Metoda dostawy')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nazwa widoczna dla klienta')
                            ->placeholder('np. InPost Paczkomat 24/7')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('price')
                            ->label('Cena')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->suffix('zł')
                            ->required(),

                        TextInput::make('description')
                            ->label('Opis')
                            ->placeholder('np. Odbiór w wybranym paczkomacie')
                            ->maxLength(255),

                        TextInput::make('code')
                            ->label('Kod')
                            ->helperText('Zapisywany w zamówieniach - lepiej go nie zmieniać.')
                            ->required()
                            ->alphaDash()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),

                        Toggle::make('requires_point')
                            ->label('Dostawa do punktu (paczkomat / punkt odbioru)')
                            ->helperText('Klient podaje kod punktu zamiast adresu.')
                            ->live(),

                        TextInput::make('point_map_url')
                            ->label('Link do mapy punktów')
                            ->url()
                            ->maxLength(255)
                            ->visible(fn (Get $get) => (bool) $get('requires_point')),

                        TextInput::make('sort_order')
                            ->label('Kolejność')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Toggle::make('is_active')
                            ->label('Dostępna w sklepie')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
