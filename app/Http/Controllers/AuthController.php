<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = (new CreateNewUser())->create($request->validated());

        return [
            'token' => $user->createToken('auth')->plainTextToken,
        ];
    }

    public function login(LoginRequest $request)
    {
        $user = User::query()->where('email', $request->email)->first(['id', 'email', 'password']);

        if (!$user && !Hash::check($request->password, $user->password)) {
            abort(401);
        }

        return [
            'token' => $user->createToken('auth')->plainTextToken,
        ];
    }
}
