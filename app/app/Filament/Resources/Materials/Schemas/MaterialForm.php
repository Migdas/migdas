<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Materiał')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nazwa')
                            ->placeholder('np. PLA')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (Set $set, ?string $state) =>
                                    $set('slug', Str::slug($state ?? ''))
                            ),

                        TextInput::make('code')
                            ->label('Kod')
                            ->placeholder('np. PLA')
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),

                        TextInput::make('slug')
                            ->label('Adres URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('density_g_cm3')
                            ->label('Gęstość')
                            ->numeric()
                            ->step(0.001)
                            ->suffix('g/cm³')
                            ->placeholder('np. 1.240'),

                        TextInput::make('sort_order')
                            ->label('Kolejność')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Toggle::make('is_active')
                            ->label('Materiał aktywny')
                            ->default(true),

                        Textarea::make('description')
                            ->label('Opis')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
