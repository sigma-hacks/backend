<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardsController extends BaseController
{

    /**
     * For sync users and cards data on android
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCardsData(Request $request): JsonResponse
    {

        $cardsQuery = DB::table('cards')
            ->select(DB::raw('users."name" as un, COALESCE(EXTRACT(epoch FROM users.birth_date)::integer, 0) as bd, cards.identifier as cn, EXTRACT(epoch FROM cards.expired_at)::integer as ed, card_tariffs."id" as ti'))
            ->leftJoin('users', 'cards.user_id','=', 'users.id')
            ->leftJoin('card_tariffs', 'cards.tariff_id', '=', 'card_tariffs.id');

        if( $request->has('updated_at') ) {
            $updatedAt = date('Y-m-d H:i:s', strtotime($request->has('updated_at')));
            $cardsQuery->orWhere('cards.updated_at', '>=', $updatedAt);
            $cardsQuery->orWhere('card_tariffs.updated_at', '>=', $updatedAt);
            $cardsQuery->orWhere('users.updated_at', '>=', $updatedAt);
        }

        $cards = $cardsQuery->limit(100000)->get();
        $names = [];

        $transformedCards = $cards->map(function ($card) use (&$names) {
            $exName = explode(' ', $card->un);
            unset($exName[0]);

            $exName[2] = mb_substr($exName[2], 0, 1) . '.';
            $name = join(' ', $exName);

            $nameHash = intval(hash('crc32b', $name), 16);
            $names[$name] = $nameHash;

            return [
                $nameHash,
                $card->bd ?? 0,
                $card->cn ?? 0,
                $card->ed ?? 0,
                $card->ti ?? 0
            ];
        })->all();

        return $this->sendResponse([
            'names' => $names,
            'cards' => $transformedCards,
        ]);
    }

}
