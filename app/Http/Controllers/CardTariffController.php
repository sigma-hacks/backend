<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Models\CardTariff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardTariffController extends BaseController
{
    /**
     * Creating Card
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validate = MainHelper::validate($request, CardTariff::CREATING_RULES);
        if ($validate->getStatus() === false) {
            return response()->json($validate->toArray(), 412);
        }

        $arField = $validate->getData();
        $arField['created_user_id'] = MainHelper::getUserId();
        $card = new CardTariff($arField);

        try {
            $card->save();
        } catch (\Exception $exception) {
            return $this->sendServerError("DB_error", $exception->getMessage());
        }

        return $this->sendResponse($card);
    }

    /**
     * Getting all CardTariff
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $card = CardTariff::query()
            ->get();

        return $this->sendResponse($card);
    }

    /**
     * Getting only CardTariff
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function only(Request $request, int $id): JsonResponse
    {
        $card = CardTariff::find($id);

        return $card ? $this->sendError("ID {$id} not found") : $this->sendResponse($card);
    }

    /**
     * Update CardTariff
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $card = CardTariff::find($id);
        if (!$card || $card?->id != $id) {
            return $this->sendError("ID {$id} not found");
        }

        if (!MainHelper::isAdmin() || $card->created_user_id != MainHelper::getUserId()) {
            return $this->sendError("Not have permission", code: 403);
        }

        $validate = MainHelper::validate($request, CardTariff::UPDATING_RULES);
        if ($validate->getStatus() === false) {
            return response()->json($validate->toArray(), 412);
        }

        $arFields = $validate->getData();
        foreach ($arFields as $key=>$value) {
            $card->$key = $value;
        }

        try {
            $card->save();
        } catch (\Exception $exception) {
            return $this->sendServerError("DB_error", $exception->getMessage());
        }

        return $this->sendResponse($card);
    }

    /**
     * Delete CardTariff
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        $card = CardTariff::find($id);

        if (!$card || $card?->id != $id) {
            return $this->sendError("ID {$id} not found");
        }

        if (!MainHelper::isAdmin() || $card->created_user_id != MainHelper::getUserId()) {
            return $this->sendError("Not have permission", code: 403);
        }

        try {
            $card->delete();
        } catch (\Exception $exception) {
            return $this->sendServerError("DB_error", $exception->getMessage());
        }

        return $this->sendResponse(true);
    }
}
