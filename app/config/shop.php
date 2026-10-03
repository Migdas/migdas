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

];
