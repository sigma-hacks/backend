<?php

namespace App\Http\Controllers;

use App\Models\ShiftRoute;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftRoutesController extends CRUDBaseController
{
    public $model = ShiftRoute::class;

    /**
     * Getting shift routes by shiftId
     *
     * @param int $shiftId
     * @param Request $request
     * @return Builder[]|Collection
     */
    public static function getShiftRoute(int $shiftId, bool $isActive = null)
    {
        $query = ShiftRoute::query()->where('shift_id', $shiftId);

        if( $isActive !== null ) {
            $query->where('is_active', $isActive);
        }

        return $query->get();
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

        $oldShiftRoutes = self::getShiftRoute($shift->id, true);

        $errorMessages = [];
        foreach ($oldShiftRoutes as $oldShiftRoute) {
            $oldShiftRoute->finished_at = date('Y-m-d H:i:s');
            $oldShiftRoute->is_active = false;
            try {
                $oldShiftRoute->save();
            } catch (Exception $e) {
                $errorMessages[] = $e->getMessage();
            }
        }

        $shiftRoute = new ShiftRoute([
            'is_active' => true,
            'created_user_id'
        ]);

        return $this->sendResponse([]);
    }
}
