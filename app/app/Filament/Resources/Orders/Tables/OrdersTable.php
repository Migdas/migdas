<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('number')
                    ->label('Numer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('first_name')
                    ->label('Klient')
                    ->formatStateUsing(
                        fn ($state, $record) =>
                            $record->first_name . ' ' . $record->last_name
                    )
                    ->searchable([
                        'first_name',
                        'last_name',
                        'email',
                    ]),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'new' => 'Nowe',
                        'processing' => 'W realizacji',
                        'ready' => 'Gotowe do wysyłki',
                        'shipped' => 'Wysłane',
                        'completed' => 'Zrealizowane',
                        'cancelled' => 'Anulowane',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'ready' => 'primary',
                        'shipped' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('payment_status')
                    ->label('Płatność')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'unpaid' => 'Nieopłacone',
                        'paid' => 'Opłacone',
                        'failed' => 'Błąd',
                        'refunded' => 'Zwrócone',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('shipping_method')
                    ->label('Dostawa')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'courier' => 'Kurier',
                        'pickup' => 'Odbiór osobisty',
                        default => $state,
                    }),

                TextColumn::make('total')
                    ->label('Razem')
                    ->money('PLN')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

            ])

            ->filters([

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Nowe',
                        'processing' => 'W realizacji',
                        'ready' => 'Gotowe do wysyłki',
                        'shipped' => 'Wysłane',
                        'completed' => 'Zrealizowane',
                        'cancelled' => 'Anulowane',
                    ]),

                SelectFilter::make('payment_status')
                    ->label('Płatność')
                    ->options([
                        'unpaid' => 'Nieopłacone',
                        'paid' => 'Opłacone',
                        'failed' => 'Błąd',
                        'refunded' => 'Zwrócone',
                    ]),

            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
