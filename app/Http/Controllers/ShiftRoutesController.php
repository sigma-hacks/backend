<?php

namespace App\Http\Controllers;

use App\Models\ShiftRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftRoutesController extends CRUDBaseController
{
    public $model = ShiftRoute::class;

    public static function getShiftRoute(int $shiftId, Request $request): JsonResponse
    {

    }

    /**
     * Start shift route
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function start(Request $request): JsonResponse
    {

        $user = $request->user();
        $shift = ShiftController::getShift($request);

        $createdAt = date('Y-m-d H:i:s');

        if( $request->has('request_at') && $request->has('is_deferred_request') ) {
            $createdAt = date('Y-m-d H:i:s', strtotime($request->input('request_at')));
        }

        if( !$shift?->id ) {
            return $this->sendError('Not found started shifts');
        }



        return $this->sendResponse([]);
    }
}
