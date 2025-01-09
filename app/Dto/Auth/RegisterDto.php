<?php


namespace App\Dto\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use InvalidArgumentException;

class RegisterDto {
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $confirmPassword,
        public readonly string $name
        )
    {
        $this->validate();

    }

    private function validate(): void
    {
        if ($this->password !== $this->confirmPassword) {
            throw new InvalidArgumentException('Passwords do not match');
        }

        if (strlen($this->password) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters');
        }
    }

    public static function fromRequest(RegisterRequest $request) {
        return new self(
            $request->validated('email'),
            $request->validated('password'),
            $request->validated('password_confirmation'),
            $request->validated('name')
        );
    }
}
