<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestingController extends Controller
{

    /**
     * Testing request
     *
     * @param Request $request
     * @return Response
     */
    public function test(Request $request): Response
    {
        return response([
            'status' => true,
            'server_date' => date('Y-m-d H:i:s')
        ]);
    }

}
