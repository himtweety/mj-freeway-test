<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class BaseController extends Controller {
    //

    /**
     * @param $response
     * @param string $type
     * @param int $response_code
     * @param int $outputType
     * @return \Illuminate\Http\JsonResponse
     */
    protected function api_response_success(string $message, $response, int $response_code = Response::HTTP_OK, string $apiVersion = "1.0", string $type = "json", int $outputType = JSON_PRETTY_PRINT): JsonResponse {
        $status = $response_code;
        $error = "";
        $returnArr = [
            "status" => $status,
            "message" => $message,
            "data" => $response,
            "error" => $error,
            "info" => [
                "api_version" => $apiVersion,
                "status" => "success",
                "format" => "json",
                "response_code" => $response_code,
                "generated_at" => Carbon::now(),
            ]
        ];
        return response()
                        ->json($returnArr, $response_code, array(), $outputType) //JSON_NUMERIC_CHECK //JSON_PRETTY_PRINT
                        ->header('Access-Control-Allow-Origin', '*')
                        ->header('Content-Type', 'application/json;charset=utf-8');
    }

    /**
     * @param $error
     * @param string $type
     * @param int $response_code
     * @param int $outputType
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_response_error($message, $error, int $response_code = 200, string $type = "json", int $outputType = JSON_PRETTY_PRINT): JsonResponse {
        $status = $response_code;
        $response = "";
        $returnArr = [
            "status" => $status,
            "message" => null,
            "data" => null,
            "error" => collect($message)->first(),
            "info" => [
                "status" => "error",
                "format" => "json",
                "response_code" => $response_code,
                "generated_at" => Carbon::now(),
            ]
        ];
        if (!is_null($error)) {
            
        }
        return response()
                        ->json($returnArr, $response_code, array(), $outputType)
                        ->header('Access-Control-Allow-Origin', '*')
                        ->header('Content-Type', 'application/json;charset=utf-8');
    }

    public function getHTTPResponse(): \Illuminate\Http\Response {
        return \Illuminate\Http\Response::create();
    }

}
