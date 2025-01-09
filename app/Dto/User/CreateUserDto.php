<?php


namespace App\Dto\User;

use App\Http\Requests\User\CreateUserRequest;

class CreateUserDto {
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $name
        )
    {
    }

    public static function fromRequest(CreateUserRequest $request) {
        return new self(
            $request->email,
            $request->password,
            $request->name
        );
    }
}
