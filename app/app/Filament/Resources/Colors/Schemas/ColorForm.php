<?php

namespace App\Filament\Resources\Colors\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ColorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Kolor')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nazwa')
                            ->placeholder('np. Czarny')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (Set $set, ?string $state) =>
                                    $set('slug', Str::slug($state ?? ''))
                            ),

                        TextInput::make('code')
                            ->label('Kod')
                            ->placeholder('np. BLK')
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),

                        TextInput::make('slug')
                            ->label('Adres URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        ColorPicker::make('hex')
                            ->label('Kolor HEX'),

                        TextInput::make('sort_order')
                            ->label('Kolejność')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Toggle::make('is_active')
                            ->label('Kolor aktywny')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
