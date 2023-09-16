<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use App\Models\Company;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompaniesController extends BaseController
{

    /**
     * Create company
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validate = MainHelper::validate($request, Company::CREATING_RULES);

        if( $validate->getStatus() === false ) {
            return $this->sendError('Not valid data', $validate->toArray(), 412);
        }

        $company = new Company($validate->getData());

        try {
            $company->save();
        } catch (Exception $e) {
            return $this->sendServerError('Database error', [$e->getMessage()]);
        }

        return $this->sendResponse($company);
    }

    /**
     * Update company data by companyID
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $companyId): JsonResponse
    {

        $validate = MainHelper::validate($request, Company::UPDATING_RULES);

        if( $validate->getStatus() === false ) {
            return $this->sendError('Not valid data', $validate->toArray(), 412);
        }

        $company = Company::where('id', $companyId)->first();

        if( $company?->id !== $companyId ) {
            return $this->sendError('Company not found', ["Company with ID:{$companyId} was not found in database"]);
        }

        $data = $validate->getData();

        if( $request->has('is_active') ) {
            $company->is_active = $data['is_active'];
        }

        if( $request->has('name') ) {
            $company->name = $data['name'];
        }

        if( $request->has('description') ) {
            $company->description = $data['description'];
        }

        if( $request->has('code') ) {
            $company->code = $data['code'];
        }

        if( $request->has('photo') ) {
            $company->photo = $data['photo'];
        }

        try {
            $company->save();
        } catch (Exception $e) {
            return $this->sendServerError('Database error', [$e->getMessage()]);
        }

        return $this->sendResponse($company);
    }

    /**
     * Getting list companies
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        $companiesDB = Company::query();

        if( $request->has('name') ) {
            $companiesDB->where('name', 'LIKE', "%{$request->input('name')}%");
        }

        if( $request->has('is_active') ) {
            $companiesDB->where('is_active', (bool) $request->input('is_active'));
        }

        $companies = $companiesDB->paginate();

        return $this->sendResponse($companies);
    }

    /**
     * Getting single company
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function single(Request $request, int $companyId): JsonResponse
    {

        $company = Company::where('id', $companyId)->first();

        if( $company?->id !== $companyId ) {
            return $this->sendError('Company not found', ["Company with ID:{$companyId} was not found in database"]);
        }

        return $this->sendResponse($company);
    }
}
