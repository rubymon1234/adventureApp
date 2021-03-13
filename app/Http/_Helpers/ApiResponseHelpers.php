<?php

namespace App\Http\_Helpers;

class ApiResponseHelpers
{ 
    function jsonSuccessResponse($customResponse, $message="Success") {
        $data = [];
        $data['errorCode'] = 200;
        $data['data'] = $customResponse;
        $data['message'] = $message;
        $data['hasError'] = false;
        return response()->json($data, 200);
    }

    function jsonErrorResponse($customResponse, $message = "Something went wrong!!") {
        $data = [];
        $data['errorCode'] = 500;
        $data['data'] = $customResponse;
        $data['message'] = $message;
        $data['hasError'] = true;
        return response()->json($data, 500);
    }
}