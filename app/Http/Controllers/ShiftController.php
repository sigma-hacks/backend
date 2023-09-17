<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Models\Shift;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftController extends BaseController
{

    public function getShift(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $companyId = $user->company_id ?? 0;

        $shift = Shift::where('created_user_id', $user->id)->where('company_id', $companyId)->where('is_active', true)->first();

        return $shift;
    }

    /**
     * Starting shift
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function start(Request $request): JsonResponse
    {
        $user = $request->user();
        $createdAt = date('Y-m-d H:i:s');

        if( $request->has('request_at') && $request->has('is_deferred_request') ) {
            $createdAt = date('Y-m-d H:i:s', strtotime($request->input('request_at')));
        }

        $oldShift = $this->getShift($request);
        if( $oldShift?->id >= 1 ) {
            return $this->sendError('Shift was started');
        }

        $shift = new Shift([
            'is_active' => true,
            'created_user_id' => $user?->id,
            'company_id' => $user->company_id,
            'started_at' => $createdAt,
            'finished_at' => null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ]);

        try {
            $shift->save();
        } catch (Exception $e) {
            return $this->sendError('Database error', $e->getMessage(), 500);
        }

        return $this->sendResponse($shift);
    }

    /**
     * Starting shift
     *
     * @param Request $request
     * @param int $shiftId
     * @return JsonResponse
     */
    public function stop(Request $request, int $shiftId): JsonResponse
    {





        $shift->finished_at = $createdAt;

        try {
            $shift->save();
        } catch (Exception $e) {
            return $this->sendError('Database error', $e->getMessage(), 500);
        }

        return $this->sendResponse($shift);
    }

}
