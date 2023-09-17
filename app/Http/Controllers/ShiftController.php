<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Shift;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftController extends BaseController
{
    public static function getShift(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $companyId = $user->company_id ?? Company::DEFAULT_ID;

        $shift = Shift::where('created_user_id', $user->id)->where('company_id', $companyId)->where('is_active', true)->first();

        return $shift;
    }

    /**
     * Starting shift
     */
    public function start(Request $request): JsonResponse
    {
        $user = $request->user();
        $createdAt = date('Y-m-d H:i:s');

        if ($request->has('request_at')) {
            $createdAt = date('Y-m-d H:i:s', strtotime($request->input('request_at')));
        }

        $oldShift = self::getShift($request);
        if ($oldShift?->id >= 1) {
            return $this->sendError('Please finishing shift for started new shift!', [
                "Shift ID:{$oldShift->id} now is not finished",
            ], 409);
        }

        $shift = new Shift([
            'is_active' => true,
            'created_user_id' => $user?->id,
            'company_id' => $user->company_id,
            'started_at' => $createdAt,
            'finished_at' => null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        try {
            $shift->save();
        } catch (Exception $e) {
            return $this->sendServerError('Database error', $e->getMessage());
        }

        return $this->sendResponse($shift);
    }

    /**
     * Starting shift
     */
    public function stop(Request $request): JsonResponse
    {
        $createdAt = date('Y-m-d H:i:s');

        if ($request->has('request_at')) {
            $createdAt = date('Y-m-d H:i:s', strtotime($request->input('request_at')));
        }

        $user = $request->user();
        $shift = self::getShift($request);
        if (! $shift?->id) {
            return $this->sendError('Shift not found');
        }

        $shift->finished_at = $createdAt;
        $shift->is_active = false;

        try {
            $shift->save();
        } catch (Exception $e) {
            return $this->sendServerError('Database error', $e->getMessage());
        }

        return $this->sendResponse($shift);
    }
}
