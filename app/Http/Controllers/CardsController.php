<?php

namespace App\Http\Controllers;

use App\Models\CardTariff;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CardsController extends BaseController
{
    public function getCards(string $updatedAt = null)
    {
        $cardsQuery = DB::table('cards')
            ->select(DB::raw('users."name" as un, COALESCE(EXTRACT(epoch FROM users.birth_date)::integer, 0) as bd, cards.identifier as cn, EXTRACT(epoch FROM cards.expired_at)::integer as ed, card_tariffs."id" as ti'))
            ->leftJoin('users', 'cards.user_id', '=', 'users.id')
            ->leftJoin('card_tariffs', 'cards.tariff_id', '=', 'card_tariffs.id');

        if ($updatedAt) {
            $updatedAt = date('Y-m-d H:i:s', strtotime($updatedAt));
            $cardsQuery->orWhere('cards.updated_at', '>=', $updatedAt);
            $cardsQuery->orWhere('card_tariffs.updated_at', '>=', $updatedAt);
            $cardsQuery->orWhere('users.updated_at', '>=', $updatedAt);
        }

        return $cardsQuery;
    }

    public const CARDS_CACHE_KEY = 'cards-resource';

    /**
     * For sync users and cards data on android
     */
    public function getCardsData(Request $request): JsonResponse
    {

        $updatedAt = $request->input('updated_at');
        $updatedAtHash = md5($updatedAt);

        $cacheKey = self::CARDS_CACHE_KEY;
        if ($updatedAt) {
            $cacheKey .= "-{$updatedAtHash}";
        }

        return Cache::remember($cacheKey, 60 * 60, function () use ($updatedAt) {
            $cardsQuery = $this->getCards($updatedAt);

            $cards = $cardsQuery->get();
            $names = [];

            $transformedCards = $cards->map(function ($card) use (&$names) {
                $exName = explode(' ', $card->un);
                unset($exName[0]);

                $exName[2] = mb_substr($exName[2], 0, 1).'.';
                $name = implode(' ', $exName);

                $nameHash = intval(hash('crc32b', $name), 16);
                $names[$nameHash] = $name;

                return [
                    $nameHash,
                    $card->bd ?? 0,
                    $card->cn ?? 0,
                    $card->ed ?? 0,
                    $card->ti ?? 0,
                ];
            })->all();

            return $this->sendResponse([
                'tariffs' => CardTariff::select('name', 'amount', 'is_active')->where('company_id', Company::DEFAULT_ID)->get(),
                'names' => $names,
                'cards' => $transformedCards,
            ]);
        });
    }

    /**
     * Getting cards by updated date
     */
    public function getUpdates(Request $request): Response
    {
        $cardsQuery = $this->getCards($request->input('updated_at'));

        return response($cardsQuery->count());
    }
}
