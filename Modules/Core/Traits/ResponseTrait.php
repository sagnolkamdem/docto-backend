<?php

namespace Modules\Core\Traits;

use Illuminate\Http\JsonResponse;
use ReflectionClass;

trait ResponseTrait
{
    public function json(mixed $message, int $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        return new JsonResponse($message, $status, $headers, $options);
    }

    public function created(mixed $message = null, int $status = 201, array $headers = [], $options = 0): JsonResponse
    {
        return new JsonResponse($message, $status, $headers, $options);
    }

    public function deleted($modelClass = null): JsonResponse
    {
        if (! $modelClass) {
            return $this->accepted();
        }

        $id = $modelClass->getHashedKey();
        $className = (new ReflectionClass($modelClass))->getShortName();

        return $this->accepted([
            'message' => __(':className (:id) Deleted Successfully.', ['className' => $className, 'id' => $id]),
        ]);
    }

    public function accepted(mixed $message = null, int $status = 202, array $headers = [], $options = 0): JsonResponse
    {
        return new JsonResponse($message, $status, $headers, $options);
    }

    public function noContent(int $status = 204): JsonResponse
    {
        return new JsonResponse(null, $status);
    }

    protected function successResponse(?string $message = null, array $data = [], int $status = 200): JsonResponse
    {
        $response = ['data' => $data, 'message' => $message];

        return new JsonResponse($response, $status);
    }

    protected function errorResponse(?string $message = null, array $data = [], int $status = 404): JsonResponse
    {
        $response = ['data' => $data, 'message' => $message];

        return new JsonResponse($response, $status);
    }
}
