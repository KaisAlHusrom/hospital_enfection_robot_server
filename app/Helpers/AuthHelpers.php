<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

function getAuthenticatedUser(Request $request = null): User
{
    if ($request) {
        $user = $request->user();
    } else {
        $user = Auth::user();
    }

    return $user;
}
