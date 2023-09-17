<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class CRUDBaseController extends BaseController
{
    /**
     * Creating Model
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validate = MainHelper::validate($request, app($this->model)->getCreatingRules());
        if ($validate->getStatus() === false) {
            return response()->json($validate->toArray(), 412);
        }

        $arField = $validate->getData();
        $card = app($this->model);
        $card = new $card($arField);

        try {
            $card->save();
        } catch (\Exception $exception) {
            return $this->sendServerError('DB_error', $exception->getMessage());
        }

        return $this->sendResponse($card);
    }

    /**
     * Getting all Model
     */
    public function index(Request $request): JsonResponse
    {
        $card = app($this->model)->query()
            ->get();

        return $this->sendResponse($card);
    }

    /**
     * Getting only Model
     */
    public function only(Request $request, int $id): JsonResponse
    {
        $card = app($this->model)->find($id);

        return $card ? $this->sendResponse($card) : $this->sendError("ID {$id} not found");
    }

    /**
     * Update Model
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $card = app($this->model)->find($id);
        if (! $card || $card?->id != $id) {
            return $this->sendError("ID {$id} not found");
        }

        $validate = MainHelper::validate($request, app($this->model)->getUpdatingRules());
        if ($validate->getStatus() === false) {
            return response()->json($validate->toArray(), 412);
        }

        $arFields = $validate->getData();
        foreach ($arFields as $key => $value) {
            $card->$key = $value;
        }

        try {
            $card->save();
        } catch (\Exception $exception) {
            return $this->sendServerError('DB_error', $exception->getMessage());
        }

        return $this->sendResponse($card);
    }

    /**
     * Delete Model
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        $card = app($this->model)->find($id);

        if (! $card || $card?->id != $id) {
            return $this->sendError("ID {$id} not found");
        }

        try {
            $card->delete();
        } catch (\Exception $exception) {
            return $this->sendServerError('DB_error', $exception->getMessage());
        }

        return $this->sendResponse(true);
    }
}
