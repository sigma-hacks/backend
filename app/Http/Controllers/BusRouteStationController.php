<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Models\BusRouteStation;
use App\Models\CardTariff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusRouteStationController extends BaseController
{
    /**
     * Creating Bus
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validate = MainHelper::validate($request, BusRouteStation::CREATING_RULES);
        if ($validate->getStatus() === false) {
            return response()->json($validate->toArray(), 412);
        }

        $arField = $validate->getData();
        $bus = new BusRouteStation($arField);

        try {
            $bus->save();
        } catch (\Exception $exception) {
            return $this->sendServerError("DB_error", $exception->getMessage());
        }

        return $this->sendResponse($bus);
    }

    /**
     * Getting all BusRouteStation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $bus = BusRouteStation::query()
            ->when($request->has('is_active'), fn($query) => $query->where('is_active', '=', $request->is_active))
            ->get();

        return $this->sendResponse($bus);
    }

    /**
     * Getting only BusRouteStation
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function only(Request $request, int $id): JsonResponse
    {
        $bus = BusRouteStation::find($id);

        return $bus ? $this->sendResponse($bus) : $this->sendError("ID {$id} not found");
    }

    /**
     * Update BusRouteStation
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $bus = BusRouteStation::find($id);
        if (!$bus || $bus?->id != $id) {
            return $this->sendError("ID {$id} not found");
        }


        $validate = MainHelper::validate($request, BusRouteStation::UPDATING_RULES);
        if ($validate->getStatus() === false) {
            return response()->json($validate->toArray(), 412);
        }

        $arFields = $validate->getData();
        foreach ($arFields as $key=>$value) {
            $bus->$key = $value;
        }

        try {
            $bus->save();
        } catch (\Exception $exception) {
            return $this->sendServerError("DB_error", $exception->getMessage());
        }

        return $this->sendResponse($bus);
    }

    /**
     * Delete BusRouteStation
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        $bus = BusRouteStation::find($id);

        if (!$bus || $bus?->id != $id) {
            return $this->sendError("ID {$id} not found");
        }

        try {
            $bus->delete();
        } catch (\Exception $exception) {
            return $this->sendServerError("DB_error", $exception->getMessage());
        }

        return $this->sendResponse(true);
    }
}
