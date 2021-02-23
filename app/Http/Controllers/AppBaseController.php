<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    /**
     * @param $result
     * @param $message
     * @param  int  $code
     * @return JsonResponse
     */
    public function sendResponse($result, $message, $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return Response::json($response, $code);
    }

    /**
     * @param $message
     * @param  int  $code
     * @param  array  $errors
     * @return JsonResponse
     */
    public function sendError($message, $code = 404, $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return Response::json($response, $code);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function sendSuccess($message): JsonResponse
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ]);
    }
}
