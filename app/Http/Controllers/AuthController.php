<?php


namespace App\Http\Controllers;

use App\Dto\Auth\RegisterDto;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\Services\IAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function __construct(protected IAuthService $authService) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterDto::fromRequest($request);

        [$user, $token] = $this->authService->register($dto);

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ]);
    }
    // /**
    //  * Get authenticated user's details.
    //  */
    // public function getUser(): JsonResponse
    // {
    //     $user = getAuthenticatedUser();
    //     $user->load('roles');
    //     return response()->json($user);
    // }

    // /**
    //  * Handle check if user admin.
    //  */
    // public function isAdmin(): JsonResponse
    // {
    //     $user = getAuthenticatedUser();

    //     if (!$user) {
    //         return $this->unauthorizedResponse('User not found.');
    //     }
    //     if (!$user->isAdmin()) {
    //         return $this->unauthorizedResponse('User is not an admin.');
    //     }

    //     return $this->successResponse([
    //         'is_admin' => $user->isAdmin(),
    //     ]);
    // }


    // /**
    //  * Handle user login.
    //  */
    // public function login(Request $request): JsonResponse
    // {
    //     $validated = $request->validate([
    //         'email' => 'required|string|email|max:255',
    //         'password' => 'required|string',
    //     ]);

    //     // Attempt to authenticate the user
    //     if (!Auth::attempt($validated)) {
    //         return $this->unauthorizedResponse('Invalid email or password.');
    //     }

    //     $request->session()->regenerate();

    //     return $this->noContentResponse();
    // }



    // public function logout(Request $request): JsonResponse
    // {
    //     // Revoke the current token
    //     $user = getAuthenticatedUser($request);

    //     if ($user) {
    //         // Revoke all tokens for the user (if you want to log out from all devices)
    //         $user->tokens()->delete();
    //     }

    //     // Or just revoke the current token
    //     // if ($user->currentAccessToken()) {
    //     //     $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    //     // }

    //     // Logout of the web guard (session)
    //     auth()->guard('web')->logout();

    //     // Invalidate the session
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return $this->noContentResponse('User logged out successfully.');
    // }
}
