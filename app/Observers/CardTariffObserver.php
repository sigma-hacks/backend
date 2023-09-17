<?php

namespace App\Observers;

use App\Http\Controllers\CardsController;
use App\Models\CardTariff;
use Illuminate\Support\Facades\Cache;

class CardTariffObserver
{
    /**
     * Handle the CardTariff "created" event.
     */
    public function created(CardTariff $cardTariff): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }

    /**
     * Handle the CardTariff "updated" event.
     */
    public function updated(CardTariff $cardTariff): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }

    /**
     * Handle the CardTariff "deleted" event.
     */
    public function deleted(CardTariff $cardTariff): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }

    /**
     * Handle the CardTariff "restored" event.
     */
    public function restored(CardTariff $cardTariff): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }

    /**
     * Handle the CardTariff "force deleted" event.
     */
    public function forceDeleted(CardTariff $cardTariff): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }
}
