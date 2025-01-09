<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;
use Throwable;

class ApiController extends BaseController
{
    use ApiResponse, ValidatesRequests;

    /**
     * Execute an action on the controller.
     *
     * @param string $method
     * @param array $parameters
     * @return JsonResponse
     */
    public function callAction($method, $parameters): JsonResponse
    {
        try {
            $response = parent::callAction($method, $parameters);

            // If the response is already a JsonResponse, return it
            if ($response instanceof JsonResponse) {
                return $response;
            }

            // Wrap other responses in a success response
            return $this->successResponse($response);
        } catch (Throwable $e) {
            return $this->handleExceptionResponse($e);
        }
    }

    /**
     * Validate the given request with custom response handling.
     *
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return array
     * @throws ValidationException
     */
    protected function validate(Request $request, array $rules, array $messages = [], array $customAttributes = []): array
    {
        try {
            return parent::validate($request, $rules, $messages, $customAttributes);
        } catch (ValidationException $e) {
            throw new ValidationException(
                $e->validator,
                $this->validationErrorResponse(
                    $e->validator->errors(),
                    'The given data was invalid.'
                )
            );
        }
    }

    /**
     * Validate request with custom rules and return validated data.
     *
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return array
     * @throws ValidationException
     */
    protected function validateRequest(array $rules, array $messages = [], array $customAttributes = []): array
    {
        return $this->validate(request(), $rules, $messages, $customAttributes);
    }
}
