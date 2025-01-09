<?php

namespace App\Interfaces\Services;

use App\Dto\Auth\RegisterDto;

interface IAuthService {
    public function register(RegisterDto $registerDto);
}
