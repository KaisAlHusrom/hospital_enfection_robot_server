<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

trait ApiResponse
{
    /**
     * Success Response
     */
    protected function successResponse(
        $data = null,
        string $message = 'Success',
        int $code = ResponseAlias::HTTP_OK
    ): JsonResponse {
        $response = config('api.format');
        $response['success'] = true;
        $response['message'] = $message;
        $response['data'] = $data;

        return response()->json($response, $code);
    }

    /**
     * Created Response
     */
    protected function createdResponse(
        $data = null,
        string $message = 'Created successfully'
    ): JsonResponse {
        return $this->successResponse($data, $message, ResponseAlias::HTTP_CREATED);
    }

    /**
     * No Content Response
     */
    protected function noContentResponse(
        string $message = 'No content'
    ): JsonResponse {
        return $this->successResponse(null, $message, ResponseAlias::HTTP_NO_CONTENT);
    }

    /**
     * Error Response
     */
    protected function errorResponse(
        string $message = 'Error',
        $data = null,
        int $code = ResponseAlias::HTTP_BAD_REQUEST,
        ?array $trace = null
    ): JsonResponse {
        $response = config('api.format');
        $response['success'] = false;
        $response['message'] = $message;
        $response['data'] = $data;

        if (config('api.errors.include_trace') && $trace && config('app.debug')) {
            $response['debug'] = [
                'stack' => $trace,
                'code' => $code
            ];
        }


        return response()->json($response, $code);
    }

    /**
     * Validation Error Response
     */
    protected function validationErrorResponse(
        $errors,
        string $message = 'Validation error'
    ): JsonResponse {
        return $this->errorResponse(
            $message,
            $errors,
            ResponseAlias::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Not Found Response
     */
    protected function notFoundResponse(
        string $message = 'Resource not found'
    ): JsonResponse {
        return $this->errorResponse(
            $message,
            null,
            ResponseAlias::HTTP_NOT_FOUND
        );
    }

    /**
     * Unauthorized Response
     */
    protected function unauthorizedResponse(
        string $message = 'Unauthorized'
    ): JsonResponse {
        return $this->errorResponse(
            $message,
            null,
            ResponseAlias::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Unauthorized Response
     */
    protected function methodNotAllowedResponse(
        string $message = 'Method Not Allowed'
    ): JsonResponse {
        return $this->errorResponse(
            $message,
            null,
            ResponseAlias::HTTP_METHOD_NOT_ALLOWED
        );
    }


    /**
     * Forbidden Response
     */
    protected function forbiddenResponse(
        string $message = 'Forbidden'
    ): JsonResponse {
        return $this->errorResponse(
            $message,
            null,
            ResponseAlias::HTTP_FORBIDDEN
        );
    }

    /**
     * Server Error Response
     */
    protected function serverErrorResponse(
        string $message = 'Server Error',
        ?Throwable $exception = null
    ): JsonResponse {
        $trace = $exception ? $this->formatExceptionTrace($exception) : null;
        return $this->errorResponse(
            $message,
            null,
            ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
            $trace
        );
    }

    /**
     * Format Exception Trace
     */
    protected function formatExceptionTrace(Throwable $exception): array
    {
        return [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => collect($exception->getTrace())
                ->map(fn($trace) => array_intersect_key(
                    $trace,
                    array_flip(['file', 'line', 'function', 'class'])
                ))
                ->take(config('api.errors.trace_limit', 10)) // Default to 10 if config is not set
                ->toArray(),
        ];
    }


    /**
     * Handle Exception Response
     */
    protected function handleExceptionResponse(Throwable $exception): JsonResponse
    {
        if ($exception instanceof ValidationException) {
            return $this->validationErrorResponse(
                $exception->validator->errors(),
                config('api.errors.messages.validation', 'Validation error occurred.')
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->notFoundResponse(
                config('api.errors.messages.not_found', 'Resource not found.')
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->notFoundResponse(
                config('api.errors.messages.not_found', 'Resource not found.')
            );;
        }

        // 405 Method Not Allowed
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->methodNotAllowedResponse(
                config('api.errors.messages.forbidden', 'Method Not Allowed.')
            );
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthorizedResponse(
                config('api.errors.messages.unauthorized', 'Unauthorized access.')
            );
        }


        return $this->serverErrorResponse(
            config('api.errors.messages.server_error', 'An internal server error occurred.'),
            $exception
        );
    }
}
