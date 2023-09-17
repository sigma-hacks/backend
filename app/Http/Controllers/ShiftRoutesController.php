<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Models\Card;
use App\Models\CardCheck;
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

        $startedAt = date('Y-m-d H:i:s');

        if ($request->has('request_at')) {
            $startedAt = date('Y-m-d H:i:s', strtotime($request->input('request_at')));
        }

        if (! $shift?->id) {
            return $this->sendError('Not found started shifts');
        }

        $data = self::stopShiftRoutes($shift->id, $startedAt);

        $shiftRoute = new ShiftRoute([
            'is_active' => true,
            'shift_id' => $shift->id,
            'employer_id' => $user->id,
            'vehicle_number' => $request->input('vehicle_number'),
            'pos_lat' => $request->input('pos_lat'),
            'pos_lng' => $request->input('pos_lng'),
            'started_at' => date('Y-m-d H:i:s', strtotime($startedAt)),
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
     * Save card checking
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {

        $identify = (int) $request->input('card_number');

        if($identify <= 2000000000000000) {
            return $this->sendError('Field "card_number" is not valid! Ex.: 2202000000000001 (min value)', '', 412);
        }

        $card = Card::where('identifier', $identify)->with('tariff')->first();

        if(! $card?->id) {
            return $this->sendError("Card with number: '{$identify}' not found in database!");
        }

        $shift = ShiftController::getShift($request);

        if(! $shift?->id) {
            return $this->sendError('Not found started shifts, please starting shift and try again');
        }

        $shiftRoutes = self::getShiftRoute($shift->id, true);

        if($shiftRoutes->isEmpty()) {
            return $this->sendError('Not found started shift routes, please starting shift route and try again');
        }

        $shiftRoute = $shiftRoutes->first();

        $startedAt = date('Y-m-d H:i:s');

        if ($request->has('request_at')) {
            $startedAt = date('Y-m-d H:i:s', strtotime($request->input('request_at')));
        }

        $check = new CardCheck([
            'employer_id' => MainHelper::getUserId(),
            'card_id' => $card->id,
            'company_id' => $shift->company_id,
            'shift_id' => $shift->id,
            'shift_route_id' => $shiftRoute->id,
            'bus_route_id' => $shiftRoute->bus_router_id,
            'pos_lat' => (double) $request->input('pos_lat'),
            'pos_lng' => (double) $request->input('pos_lat'),
            'checked_at' => $startedAt
        ]);

        try {
            $check->save();
        } catch (Exception $e) {
            return $this->sendServerError('Database error', $e->getMessage());
        }

        return $this->sendResponse([
            'card' => $card,
            'check' => $check
        ]);
    }

    /**
     * Getting data with last card checks
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function lastChecks(Request $request): JsonResponse
    {

        $limit = (int) $request->input('limit') ?? 15;
        $user = $request->user();
        $checks = CardCheck::where('employer_id', $user->id)->limit($limit)->orderByDesc('created_at')->get();

        return $this->sendResponse($checks);
    }
}
