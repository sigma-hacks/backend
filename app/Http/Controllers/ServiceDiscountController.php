<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Models\ServiceDiscount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceDiscountController extends BaseController
{
    /**
     * Creating ServiceDiscount
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validate = MainHelper::validate($request, ServiceDiscount::CREATING_RULES);
        if ($validate->getStatus() === false) {
            return response()->json($validate->toArray(), 412);
        }

        $arField = $validate->getData();
        $arField['created_user_id'] = MainHelper::getUserId();
        $service = new ServiceDiscount($arField);

        try {
            $service->save();
        } catch (\Exception $exception) {
            return $this->sendServerError('DB_error', $exception->getMessage());
        }

        return $this->sendResponse($service);
    }

    /**
     * Getting all ServiceDiscount
     */
    public function index(Request $request): JsonResponse
    {
        $service = ServiceDiscount::query()
            ->get();

        return $this->sendResponse($service);
    }

    /**
     * Getting only ServiceDiscount
     */
    public function only(Request $request, int $id): JsonResponse
    {
        $service = ServiceDiscount::find($id);

        return $service ? $this->sendResponse($service) : $this->sendError("ID {$id} not found");
    }

    /**
     * Update ServiceDiscount
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $service = ServiceDiscount::find($id);
        if (! $service || $service?->id != $id) {
            return $this->sendError("ID {$id} not found");
        }

        if (! MainHelper::isAdmin() && $service->created_user_id != MainHelper::getUserId()) {
            return $this->sendError('Not have permission', code: 403);
        }

        $validate = MainHelper::validate($request, ServiceDiscount::UPDATING_RULES);
        if ($validate->getStatus() === false) {
            return response()->json($validate->toArray(), 412);
        }

        $arFields = $validate->getData();
        foreach ($arFields as $key => $value) {
            $service->$key = $value;
        }

        try {
            $service->save();
        } catch (\Exception $exception) {
            return $this->sendServerError('DB_error', $exception->getMessage());
        }

        return $this->sendResponse($service);
    }

    /**
     * Delete ServiceDiscount
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        $service = ServiceDiscount::find($id);

        if (! $service || $service?->id != $id) {
            return $this->sendError("ID {$id} not found");
        }

        if (! MainHelper::isAdmin() && $service->created_user_id != MainHelper::getUserId()) {
            return $this->sendError('Not have permission', code: 403);
        }

        try {
            $service->delete();
        } catch (\Exception $exception) {
            return $this->sendServerError('DB_error', $exception->getMessage());
        }

        return $this->sendResponse(true);
    }
}
