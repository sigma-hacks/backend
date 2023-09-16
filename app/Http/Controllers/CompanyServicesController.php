<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Models\Company;
use App\Models\CompanyService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanyServicesController extends BaseController
{

    /**
     * Create service for company
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {

        $validate = MainHelper::validate($request, CompanyService::CREATING_RULES);

        if( $validate->getStatus() === false ) {
            return $this->sendError('Not valid data', $validate->toArray(), 412);
        }

        $service = new CompanyService($validate->getData());

        try {
            $service->save();
        } catch (Exception $e) {
            return $this->sendServerError('Database error', [$e->getMessage()]);
        }

        return $this->sendResponse($service);
    }

    /**
     * Update service for company
     *
     * @param Request $request
     * @param int $serviceId
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $serviceId): JsonResponse
    {

        $validate = MainHelper::validate($request, CompanyService::UPDATING_RULES);

        if( $validate->getStatus() === false ) {
            return $this->sendError('Not valid data', $validate->toArray(), 412);
        }

        $service = CompanyService::where('id', $serviceId)->first();

        if( $service?->id !== $service ) {
            return $this->sendError('Company not found', ["Company with ID:{$serviceId} was not found in database"]);
        }

        $data = $validate->getData();

        if( $request->has('name') ) $service->name = $data['name'];
        if( $request->has('price') ) $service->price = $data['price'];
        if( $request->has('photo') ) $service->photo = $data['photo'];

        try {
            $service->save();
        } catch (Exception $e) {
            return $this->sendServerError('Database error', [$e->getMessage()]);
        }

        return $this->sendResponse($service);
    }

    /**
     * Getting list services
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        $servicesDB = CompanyService::query();

        if( $request->has('name') ) {
            $servicesDB->where('name', 'LIKE', "%{$request->has('name')}%");
        }

        if( $request->has('price') ) {
            $price = $request->input('price');

            if( is_array($price) && count($price) == 2 ) {
                $servicesDB
                    ->where('price', '>=', $price[0])
                    ->where('price', '<=', $price[1]);
            }

            if( !is_array($price) ) {
                $servicesDB->where('price', $price);
            }
        }

        $services = $servicesDB->paginate();

        return $this->sendResponse($services);
    }

    /**
     * Getting single service
     *
     * @param Request $request
     * @param int $serviceId
     * @return JsonResponse
     */
    public function single(Request $request, int $serviceId): JsonResponse
    {

        $service = CompanyService::where('id', $serviceId)->first();

        if( !$service || $service?->id !== $serviceId ) {
            return $this->sendError("Service with ID:{$serviceId} not found!");
        }

        return $this->sendResponse($service);
    }

    /**
     * Remove service row from database
     *
     * @param Request $request
     * @param int $serviceId
     * @return JsonResponse
     */
    public function delete(Request $request, int $serviceId): JsonResponse
    {

        if( $serviceId <= 0 ) {
            return $this->sendError('Service ID can\'t be empty', ['Service ID is not set or empty'], 412);
        }

        $service = CompanyService::find($serviceId);

        try {
            $service->delete();
        } catch (Exception $e) {
            return $this->sendServerError('Database error', $e->getMessage());
        }

        return $this->sendResponse($service, 200, 'Successfully delete service');
    }

}
