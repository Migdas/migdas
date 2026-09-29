<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\ProductVariant;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $recordTitleAttribute = 'sku';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Wariant')
                    ->schema([
                        Select::make('material_id')
                        ->label('Materiał')
                        ->relationship('material', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
    	                ->afterStateUpdated(
                        fn (Set $set, Get $get, $livewire) =>
                        self::updateSku($set, $get, $livewire)
                        ),

                        Select::make('color_id')
                         ->label('Kolor')
                         ->relationship('color', 'name')
                         ->searchable()
                         ->preload()
                         ->required()
                         ->live()
                         ->afterStateUpdated(
                         fn (Set $set, Get $get, $livewire) =>
                          self::updateSku($set, $get, $livewire)
                         ),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->readOnly()
                            ->saved(false)
                            ->placeholder('Zostanie wygenerowane automatycznie')
                            ->helperText('SKU powstaje z produktu, materiału i koloru.'),

                        Toggle::make('is_active')
                            ->label('Aktywny')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->label('Kolejność')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(2),

                Section::make('Cena')
                    ->description('Pusta cena oznacza użycie ceny produktu głównego.')
                    ->schema([
                        TextInput::make('price')
                            ->label('Cena wariantu')
                            ->numeric()
                            ->prefix('zł')
                            ->minValue(0),

                        TextInput::make('sale_price')
                            ->label('Cena promocyjna')
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
                            ->label('Waga')
                            ->numeric()
                            ->suffix('g')
                            ->minValue(0),

                        TextInput::make('lead_time_days')
                            ->label('Czas realizacji')
                            ->numeric()
                            ->suffix('dni')
                            ->minValue(0)
                            ->helperText('Puste = czas z produktu głównego.'),
                    ])
                    ->columns(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sku')
            ->columns([
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
                    ->label('Cena')
                    ->money('PLN')
                    ->placeholder('Cena produktu'),

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
            ->headerActions([
                CreateAction::make()
                    ->label('Dodaj wariant'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

          private static function updateSku(
             Set $set,
             Get $get,
             $livewire
          ): void {
              $materialId = $get('material_id');
              $colorId = $get('color_id');

              if (! $materialId || ! $colorId) {
                   $set('sku', null);

              return;
              }

            $productId = $livewire->getOwnerRecord()->getKey();

           $set(
                'sku',
                ProductVariant::generateSku(
                (int) $productId,
                (int) $materialId,
                (int) $colorId
        )
    );
  }

}
