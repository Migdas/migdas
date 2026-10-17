<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Powiadomienia o zamówieniach
    |--------------------------------------------------------------------------
    |
    | Adres, na który trafia informacja o każdym nowym zamówieniu.
    | Puste - powiadomienie nie jest wysyłane.
    |
    */

    'notification_email' => env('SHOP_NOTIFICATION_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Dane do przelewu
    |--------------------------------------------------------------------------
    |
    | Pokazywane w e-mailu z potwierdzeniem zamówienia, jeśli są uzupełnione.
    |
    */

    'bank_account' => env('SHOP_BANK_ACCOUNT'),

    'bank_recipient' => env('SHOP_BANK_RECIPIENT'),

    /*
    |--------------------------------------------------------------------------
    | Dane sprzedawcy
    |--------------------------------------------------------------------------
    |
    | Pokazywane w regulaminie sklepu. Puste pola są tam oznaczone
    | jako wymagające uzupełnienia.
    |
    */

    'seller' => [
        'name' => env('SHOP_SELLER_NAME', 'Michał Migdalski'),
        'address' => env('SHOP_SELLER_ADDRESS'),
        'nip' => env('SHOP_SELLER_NIP'),
        'email' => env('SHOP_SELLER_EMAIL', 'sklep@migdas.pl'),
        'phone' => env('SHOP_SELLER_PHONE', '571 531 175'),
    ],

];
