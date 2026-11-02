<?php

namespace App\Models\Concerns;

use App\Models\PriceHistory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPriceHistory
{
    protected static function bootHasPriceHistory(): void
    {
        static::saved(function ($model) {
            if ($model->wasRecentlyCreated || $model->wasChanged(['price', 'sale_price'])) {
                $model->recordCurrentPrice();
            }
        });
    }

    public function priceHistory(): MorphMany
    {
        return $this->morphMany(PriceHistory::class, 'priceable')->orderBy('id');
    }

    private function recordCurrentPrice(): void
    {
        $price = $this->sale_price ?? $this->price;

        // Wariant bez własnej ceny dziedziczy cenę i historię produktu.
        if ($price === null) {
            return;
        }

        $last = $this->priceHistory()->reorder('id', 'desc')->first();

        if ($last === null || (float) $last->price !== round((float) $price, 2)) {
            $this->priceHistory()->create(['price' => $price]);
            $this->unsetRelation('priceHistory');
        }
    }

    /**
     * Najniższa cena z 30 dni przed ostatnią obniżką (art. 6a ustawy
     * o informowaniu o cenach). Null, gdy towar nie jest przeceniony.
     */
    public function lowestPriceBeforeSale(): ?float
    {
        if ($this->sale_price === null) {
            return null;
        }

        $history = $this->priceHistory->sortByDesc('id')->values();

        $reducedAt = $history->first()?->created_at;

        $prices = collect();

        foreach ($history->skip(1) as $entry) {
            $prices->push((float) $entry->price);

            // Ta cena obowiązywała już na początku okresu 30 dni.
            if ($entry->created_at->lt($reducedAt->copy()->subDays(30))) {
                break;
            }
        }

        // Bez wcześniejszej historii punktem odniesienia jest cena regularna.
        return $prices->min() ?? (float) $this->regularPrice();
    }
}
