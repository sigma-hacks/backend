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
     * @param  Request  $request
     * @return Builder[]|Collection
     */
    public static function getShiftRoute(int $shiftId, bool $isActive = null)
    {
        $query = ShiftRoute::query()->where('shift_id', $shiftId);

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        return $query->get();
    }

    /**
     * Stoping shift routes
     */
    public static function stopShiftRoutes(int $shiftId, string $finishedAt): array
    {
        $shiftRoutes = self::getShiftRoute($shiftId, true);

        $errorMessages = [];
        foreach ($shiftRoutes as $shiftRoute) {
            $shiftRoute->finished_at = date('Y-m-d H:i:s', strtotime($finishedAt));
            $shiftRoute->is_active = false;
            try {
                $shiftRoute->save();
            } catch (Exception $e) {
                $errorMessages[] = $e->getMessage();
            }
        }

        return [
            'shiftRoutes' => $shiftRoutes,
            'errors' => $errorMessages,
        ];
    }

    /**
     * Start shift route
     */
    public function start(Request $request): JsonResponse
    {

        $user = $request->user();
        $shift = ShiftController::getShift($request);

        $finishedAt = date('Y-m-d H:i:s');

        if ($request->has('request_at')) {
            $finishedAt = date('Y-m-d H:i:s', strtotime($request->input('request_at')));
        }

        if (! $shift?->id) {
            return $this->sendError('Not found started shifts');
        }

        $data = self::stopShiftRoutes($shift->id, $finishedAt);

        $shiftRoute = new ShiftRoute([
            'is_active' => true,
            'shift_id' => $shift->id,
            'employer_id' => $user->id,
            'vehicle_number' => $request->input('vehicle_number'),
            'pos_lat' => $request->input('pos_lat'),
            'pos_lng' => $request->input('pos_lng'),
            'started_at' => $request->has('request_at') ? date('Y-m-d H:i:s', strtotime($request->input('request_at'))) : date('Y-m-d H:i:s'),
            'finished_at' => null,
            'bus_router_id' => (int) $request->input('bus_router_id') ?? 0,
        ]);

        try {
            $shiftRoute->save();
        } catch (Exception $e) {
            return $this->sendServerError('Database error', $e->getMessage());
        }

        return $this->sendResponse($shiftRoute, 200, $data['errors']);
    }

    /**
     * Stop shift routes
     */
    public function stop(Request $request): JsonResponse
    {

        $finishedAt = date('Y-m-d H:i:s');
        $shift = ShiftController::getShift($request);

        if ($request->has('request_at')) {
            $finishedAt = date('Y-m-d H:i:s', strtotime($request->input('request_at')));
        }

        if (! $shift?->id) {
            return $this->sendError('Not found started shifts');
        }

        $data = self::stopShiftRoutes($shift->id, $finishedAt);

        return $this->sendResponse($data['shiftRoutes'], 200, $data['errors']);
    }

    /**
     * Checking user card
     */
    public function check(Request $request): JsonResponse
    {

        $shift = ShiftController::getShift($request);

        if(! $shift?->id) {
            return $this->sendError('Not found started shifts, please starting shift and try again');
        }

        $shiftRoutes = self::getShiftRoute($shift->id, true);

        if($shiftRoutes->isEmpty()) {
            return $this->sendError('Not found started shift routes, please starting shift route and try again');
        }

        $shiftRoute = $shiftRoutes->first();

        

        return $this->sendResponse([]);
    }
}
