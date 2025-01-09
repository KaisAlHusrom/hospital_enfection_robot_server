<?php


namespace App\Interfaces\Services;

use App\Dto\User\CreateUserDto;

interface IUserService {
    public function create(CreateUserDto $createUser);
}
