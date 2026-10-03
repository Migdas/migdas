<?php

return [

    'accepted' => 'Pole :attribute musi zostać zaakceptowane.',
    'array' => 'Pole :attribute musi być tablicą.',
    'boolean' => 'Pole :attribute musi mieć wartość prawda albo fałsz.',
    'confirmed' => 'Potwierdzenie pola :attribute nie zgadza się.',
    'date' => 'Pole :attribute nie jest prawidłową datą.',
    'decimal' => 'Pole :attribute musi mieć :decimal miejsc po przecinku.',
    'email' => 'Pole :attribute musi być prawidłowym adresem e-mail.',
    'exists' => 'Wybrana wartość pola :attribute jest nieprawidłowa.',
    'file' => 'Pole :attribute musi być plikiem.',
    'image' => 'Pole :attribute musi być obrazem.',
    'in' => 'Wybrana wartość pola :attribute jest nieprawidłowa.',
    'integer' => 'Pole :attribute musi być liczbą całkowitą.',
    'max' => [
        'array' => 'Pole :attribute nie może mieć więcej niż :max elementów.',
        'file' => 'Pole :attribute nie może być większe niż :max kB.',
        'numeric' => 'Pole :attribute nie może być większe niż :max.',
        'string' => 'Pole :attribute nie może być dłuższe niż :max znaków.',
    ],
    'mimes' => 'Pole :attribute musi być plikiem typu: :values.',
    'min' => [
        'array' => 'Pole :attribute musi mieć co najmniej :min elementów.',
        'file' => 'Pole :attribute musi mieć co najmniej :min kB.',
        'numeric' => 'Pole :attribute musi wynosić co najmniej :min.',
        'string' => 'Pole :attribute musi mieć co najmniej :min znaków.',
    ],
    'numeric' => 'Pole :attribute musi być liczbą.',
    'regex' => 'Pole :attribute ma nieprawidłowy format.',
    'required' => 'Pole :attribute jest wymagane.',
    'required_if' => 'Pole :attribute jest wymagane.',
    'string' => 'Pole :attribute musi być tekstem.',
    'unique' => 'Taka wartość pola :attribute już istnieje.',
    'url' => 'Pole :attribute musi być prawidłowym adresem URL.',

    'custom' => [
        'terms' => [
            'accepted' => 'Musisz zaakceptować regulamin sklepu.',
        ],
        'postal_code' => [
            'regex' => 'Kod pocztowy musi mieć format 00-000.',
        ],
    ],

    'attributes' => [
        'first_name' => 'imię',
        'last_name' => 'nazwisko',
        'email' => 'e-mail',
        'phone' => 'telefon',
        'street' => 'ulica',
        'building_number' => 'numer budynku',
        'apartment_number' => 'numer mieszkania',
        'postal_code' => 'kod pocztowy',
        'city' => 'miasto',
        'shipping_method' => 'sposób dostawy',
        'customer_note' => 'uwagi',
        'terms' => 'regulamin',
        'quantity' => 'ilość',
        'variant_id' => 'wariant',
    ],

];
