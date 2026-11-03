<?php

namespace App\Support;

/**
 * Gabaryty paczek - zgodne z gabarytami paczkomatów InPost / Orlen Paczki.
 * Paczka "duża" nie mieści się do paczkomatu i jedzie tylko kurierem.
 */
class ParcelSize
{
    public const A = 'A';
    public const B = 'B';
    public const C = 'C';
    public const LARGE = 'D';

    /** Maksymalne wymiary w cm (posortowane od najmniejszego). */
    public const LIMITS = [
        self::A => [8, 38, 64],
        self::B => [19, 38, 64],
        self::C => [38, 41, 64],
    ];

    public const LABELS = [
        self::A => 'Gabaryt A (do 8 × 38 × 64 cm)',
        self::B => 'Gabaryt B (do 19 × 38 × 64 cm)',
        self::C => 'Gabaryt C (do 41 × 38 × 64 cm)',
        self::LARGE => 'Duża paczka (tylko kurier)',
    ];

    public const SHORT_LABELS = [
        self::A => 'gabaryt A',
        self::B => 'gabaryt B',
        self::C => 'gabaryt C',
        self::LARGE => 'duża paczka',
    ];

    /** Kolumna z ceną w tabeli shipping_methods. */
    public const PRICE_COLUMNS = [
        self::A => 'price',
        self::B => 'price_b',
        self::C => 'price_c',
        self::LARGE => 'price_large',
    ];

    public static function all(): array
    {
        return array_keys(self::LABELS);
    }

    /**
     * Najmniejszy gabaryt, w który zmieści się wydruk o podanych wymiarach
     * (z zapasem na opakowanie). Null, gdy brak wymiarów.
     */
    public static function fromDimensions(?float $length, ?float $width, ?float $height, float $padding = 2): ?string
    {
        if (! $length || ! $width || ! $height) {
            return null;
        }

        $dims = [$length + $padding, $width + $padding, $height + $padding];
        sort($dims);

        foreach (self::LIMITS as $size => $limit) {
            if ($dims[0] <= $limit[0] && $dims[1] <= $limit[1] && $dims[2] <= $limit[2]) {
                return $size;
            }
        }

        return self::LARGE;
    }

    /** Największy gabaryt z listy - decyduje o cenie wysyłki całego koszyka. */
    public static function largest(iterable $sizes): string
    {
        $order = array_flip(self::all());
        $largest = self::A;

        foreach ($sizes as $size) {
            if (isset($order[$size]) && $order[$size] > $order[$largest]) {
                $largest = $size;
            }
        }

        return $largest;
    }
}
