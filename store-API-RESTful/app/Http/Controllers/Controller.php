<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function responseShopNotFound(): JsonResponse
    {
        return response()->json([
            'message' => 'Shop not found'
        ]);
    }

    protected function responseProductNotFound(): JsonResponse
    {
        return response()->json([
            'message' => 'Product not found'
        ]);
    }

    protected function responseGenericError(Exception $e): JsonResponse
    {
        // TODO: Save Exception to log, email, db, etc.

        return response()->json([
            'message' => 'Generic error, please contact technical support'
        ]);
    }
}
