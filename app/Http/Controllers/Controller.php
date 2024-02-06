<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($result, $code = 404, $messages = [], $success = true){
        $response = [
            'success' => $success,
            'data'    => ($result) ? $result : null,
            'message' => $messages,
        ];
        return response()->json($response, $code);
    }

    public function sendException($errors)
    {
        $response = [
            'success' => false,
            'message' => 'Exception occurred.',
            'exception' => $errors
        ];
        return response()->json($response, 500);
    }
}
