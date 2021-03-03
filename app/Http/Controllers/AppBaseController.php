<?php

namespace App\Http\Controllers;

use App\Utils\Encoder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Response;
use Arr;

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
    protected $ids = [
        'board' => 'board',
        'square' => 'square'
    ];

    /**
     * @param  array  $attributes
     * @return array
     */
    public function encodeIds(array $attributes): array
    {
        foreach ($attributes as $key => $value) {
            if (is_int($value) && Arr::exists($this->ids, $key)) {
                $attributes[$key] = $this->encode($value);
            } elseif (is_array($value)) {
                $attributes[$key] = $this->encodeIds($value);
            } elseif (is_a($value, Model::class)) {
                $attributes[$key] = $this->encodeIds($value->toArray());
            }
        }

        return $attributes;
    }

    public function encode(int $id): string
    {
        $encoder = new Encoder();

        return $encoder->encodeHex($id);
    }

    /**
     * @param  array  $attributes
     * @return array
     */
    public function decodeIds(array $attributes): array
    {
        foreach ($attributes as $key => $value) {
            if (is_string($value) && Arr::exists($this->ids, $key)) {
                $attributes[$key] = $this->decode($value);
            } elseif (is_array($value)) {
                $attributes[$key] = $this->decodeIds($value);
            } elseif (is_a($value, Model::class)) {
                $attributes[$key] = $this->decodeIds($value->toArray());
            }
        }

        return $attributes;
    }

    public function decode(string $id): int
    {
        $encoder = new Encoder();

        return $encoder->decodeHex($id);
    }

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
