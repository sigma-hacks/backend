<?php

namespace App\Observers;

use App\Http\Controllers\CardsController;
use App\Models\Card;
use Illuminate\Support\Facades\Cache;

class CardObserver
{
    /**
     * Handle the Card "created" event.
     */
    public function created(Card $card): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }

    /**
     * Handle the Card "updated" event.
     */
    public function updated(Card $card): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }

    /**
     * Handle the Card "deleted" event.
     */
    public function deleted(Card $card): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }

    /**
     * Handle the Card "restored" event.
     */
    public function restored(Card $card): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }

    /**
     * Handle the Card "force deleted" event.
     */
    public function forceDeleted(Card $card): void
    {
        Cache::forget(CardsController::CARDS_CACHE_KEY);
    }
}
