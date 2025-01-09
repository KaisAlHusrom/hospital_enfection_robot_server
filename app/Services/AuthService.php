<?php


namespace App\Services;

use App\Dto\User\CreateUserDto;
use App\Dto\Auth\RegisterDto;
use App\Interfaces\Services\IAuthService;
use App\Interfaces\Services\IUserService;

class AuthService implements IAuthService {
    public function __construct(protected IUserService $userService) {
    }
    public function register(RegisterDto $dto) {

        $createUserDto = new CreateUserDto(
            email: $dto->email,
            password: $dto->password,
            name: $dto->name
        );

        $user = $this->userService->create($createUserDto);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [$user, $token];
    }
}
