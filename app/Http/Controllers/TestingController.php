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
        $headers = $request->header();
        $data = $request->all();

        return response([
            'status' => true,
            'server_date' => date('Y-m-d H:i:s'),
            'request' => [
                'headers' => $headers,
                'data' => $data
            ]
        ]);
    }

}
