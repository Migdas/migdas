<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Podstawowe informacje')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nazwa produktu')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (Set $set, ?string $state) =>
                                    $set('slug', Str::slug($state ?? ''))
                            ),

			Select::make('category_id')
    			->label('Kategoria')
    			->relationship('category', 'name')
    			->searchable()
    			->preload(),

                        TextInput::make('slug')
                            ->label('Adres URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Toggle::make('is_active')
                            ->label('Produkt aktywny')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Cena i realizacja')
                    ->schema([
                        TextInput::make('price')
                            ->label('Cena')
                            ->numeric()
                            ->prefix('zł')
                            ->required()
                            ->minValue(0),

                        TextInput::make('sale_price')
                            ->label('Cena promocyjna')
                            ->numeric()
                            ->prefix('zł')
                            ->minValue(0),

                        TextInput::make('lead_time_days')
                            ->label('Czas realizacji')
                            ->numeric()
                            ->suffix('dni')
                            ->default(3)
                            ->minValue(0)
                            ->required(),

                        TextInput::make('weight_grams')
                            ->label('Waga wydruku')
                            ->numeric()
                            ->suffix('g')
                            ->minValue(0),
                    ])
                    ->columns(2),

                Section::make('Opis produktu')
                    ->schema([
                        Textarea::make('short_description')
                            ->label('Krótki opis')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->label('Pełny opis')
                            ->columnSpanFull(),
                    ]),

                Section::make('Zdjęcia')
                    ->schema([
                        FileUpload::make('main_image')
                            ->label('Zdjęcie główne')
                            ->image()
                            ->disk('public')
                            ->directory('products/main')
                            ->visibility('public')
                            ->maxSize(5120),

                        FileUpload::make('gallery')
                            ->label('Galeria')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(8)
                            ->disk('public')
                            ->directory('products/gallery')
                            ->visibility('public')
                            ->maxSize(5120),
                    ])
                    ->columns(2),

                Section::make('Licencja projektu 3D')
                    ->description('Informacje potwierdzające prawo do komercyjnej sprzedaży wydruku.')
                    ->schema([
                        TextInput::make('license_author')
                            ->label('Autor projektu')
                            ->maxLength(255),

                        TextInput::make('license_type')
                            ->label('Typ licencji')
                            ->placeholder('np. CC BY 4.0, CC0, Commercial License')
                            ->maxLength(255),

                        TextInput::make('license_source')
                            ->label('Źródło projektu / URL')
                            ->url()
                            ->maxLength(2048)
                            ->columnSpanFull(),

                        Toggle::make('commercial_use_allowed')
                            ->label('Licencja pozwala na sprzedaż wydruków')
                            ->default(false)
                            ->onColor('success')
                            ->offColor('danger'),
                    ])
                    ->columns(2),
            ]);
    }
}
