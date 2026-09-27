<?php

namespace App\Filament\Resources\Colors\Tables;

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

class ColorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('hex')
                    ->label('Kolor'),

                TextColumn::make('name')
                    ->label('Nazwa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('hex')
                    ->label('HEX')
                    ->placeholder('—'),

                TextColumn::make('sort_order')
                    ->label('Kolejność')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktywny')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Dodano')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

            ->defaultSort('sort_order');
    }
}
