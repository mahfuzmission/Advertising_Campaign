<?php

namespace App\Classes;

class Helper
{

    const SUCCESS = 200;
    const CREATED = 201;
    const ERROR = 400;
    const TOO_MANY_REQUEST = 429;
    const UNPROCESSABLE_CONTENT = 422;
    const SERVER_ERROR = 500;


    public static function successResponse($data=[], $statusCode=self::SUCCESS, $status="success"): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            'status_code' => $statusCode,
            'data' =>  $data,
        ], $statusCode);
    }

    public static function errorResponse($message, $statusCode=self::ERROR, $status="failure"): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            'status_code' => $statusCode,
            'message' =>  $message,
        ], $statusCode);
    }
}
