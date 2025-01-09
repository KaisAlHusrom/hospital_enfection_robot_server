<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Api
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd('API middleware');
        // Force JSON response type
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        // Transform error responses to match API format
        if ($response instanceof JsonResponse && !$response->isSuccessful()) {
            $originalData = $response->getData(true);

            $debugData = [
                'stack' => $originalData['debug'] ?? null,
                'code' => $response->getStatusCode(),
            ];
            $debug = config('app.debug') ? $debugData : null;

            $response->setData([
                'success' => false,
                'message' => $originalData['message'] ?? 'An error occurred',
                'errors' => $originalData['data'] ?? null,
                'debug' => $debug,
            ]);
        }



        // Add API headers from config
        // foreach (config('api.headers.security') as $header => $value) {
        //     $response->headers->set($header, $value);
        // }

        // foreach (config('api.headers.cache') as $header => $value) {
        //     $response->headers->set($header, $value);
        // }

        // Add CORS headers from config
        // if (config('api.enable_cors', true)) {
        //     // dd('test');
        //     $cors = config('api.headers.cors');
        //     $response->headers->set('Access-Control-Allow-Origin', implode(', ', $cors['allowed_origins']));
        //     $response->headers->set('Access-Control-Allow-Methods', implode(', ', $cors['allowed_methods']));
        //     $response->headers->set('Access-Control-Allow-Headers', implode(', ', $cors['allowed_headers']));
        // }

        // Add API version
        // $response->headers->set('X-API-Version', config('api.version'));

        return $response;
    }
}
