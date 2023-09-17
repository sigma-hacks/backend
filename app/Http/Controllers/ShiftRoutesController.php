<?php

namespace App\Http\Controllers;

use App\Models\ShiftRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftRoutesController extends CRUDBaseController
{
    public $model = ShiftRoute::class;

    /**
     * Start shift route
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function start(Request $request): JsonResponse
    {

        $user = $request->user();
        ShiftController::getShift($request);

        return $this->sendResponse([]);
    }
}
