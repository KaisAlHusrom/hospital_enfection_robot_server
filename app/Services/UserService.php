<?php


namespace App\Services;

use App\Dto\User\CreateUserDto;
use App\Interfaces\Services\IUserService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService {
    public function create(CreateUserDto $createUserDto) {
        $user = User::create([
            'email' => $createUserDto->email,
            'password' => Hash::make($createUserDto->password),
            'name' => $createUserDto->name
        ]);

        return $user;
    }
}
