<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        $data = $registerRequest->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::query()->create($data);
        return response()->json([
            'user'  => $user,
            'token' => $user
                ->createToken(Str::uuid(), abilities: ['user'])
                ->plainTextToken,
        ]);
    }

    public function login(LoginRequest $request): JsonResponse|Response
    {
        $data = $request->validated();
        if (Auth::attempt($data))
        {
            $user = Auth::user();
            $token = $user
                ->createToken(
                    name: Str::uuid(),
                    abilities: $user->role === User::ROLE_USER ? ['user'] : ['user', 'admin']
                )
                ->plainTextToken;
            return response()->json([
                'user'  => $user,
                'token' => $token,
            ]);
        }

        return response()->noContent(401);
    }

    public function logout(Request $request): Response
    {
        $request->user()->tokens()->delete();
        return response()->noContent();
    }
}
