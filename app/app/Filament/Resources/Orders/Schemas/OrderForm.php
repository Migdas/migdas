<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Zamówienie')
                    ->schema([

                        TextInput::make('number')
                            ->label('Numer zamówienia')
                            ->disabled(),

                        Select::make('status')
                            ->label('Status realizacji')
                            ->options([
                                'new' => 'Nowe',
                                'processing' => 'W realizacji',
                                'ready' => 'Gotowe do wysyłki',
                                'shipped' => 'Wysłane',
                                'completed' => 'Zrealizowane',
                                'cancelled' => 'Anulowane',
                            ])
                            ->required(),

                        Select::make('payment_status')
                            ->label('Status płatności')
                            ->options([
                                'unpaid' => 'Nieopłacone',
                                'paid' => 'Opłacone',
                                'failed' => 'Błąd płatności',
                                'refunded' => 'Zwrócone',
                            ])
                            ->required(),

                        TextInput::make('total')
                            ->label('Wartość zamówienia')
                            ->prefix('zł')
                            ->disabled(),

                    ])
                    ->columns(2),


                Section::make('Klient')
                    ->schema([

                        TextInput::make('first_name')
                            ->label('Imię')
                            ->disabled(),

                        TextInput::make('last_name')
                            ->label('Nazwisko')
                            ->disabled(),

                        TextInput::make('email')
                            ->label('E-mail')
                            ->disabled(),

                        TextInput::make('phone')
                            ->label('Telefon')
                            ->disabled(),

                    ])
                    ->columns(2),


                Section::make('Adres dostawy')
                    ->schema([

                        TextInput::make('street')
                            ->label('Ulica')
                            ->disabled(),

                        TextInput::make('building_number')
                            ->label('Numer budynku')
                            ->disabled(),

                        TextInput::make('apartment_number')
                            ->label('Numer mieszkania')
                            ->disabled(),

                        TextInput::make('postal_code')
                            ->label('Kod pocztowy')
                            ->disabled(),

                        TextInput::make('city')
                            ->label('Miasto')
                            ->disabled(),

                        TextInput::make('shipping_method')
                            ->label('Metoda dostawy')
                            ->disabled(),

                        TextInput::make('shipping_cost')
                            ->label('Koszt dostawy')
                            ->prefix('zł')
                            ->disabled(),

                    ])
                    ->columns(2),


                Section::make('Uwagi klienta')
                    ->schema([

                        Textarea::make('customer_note')
                            ->label('Uwagi')
                            ->rows(4)
                            ->disabled()
                            ->columnSpanFull(),

                    ]),
            ]);
    }
}
