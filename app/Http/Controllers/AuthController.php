<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        try {
            $email = $validated['email'];
            $password = $validated['password'];

            $user = User::where('email', $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                return Response::api('Invalid credentials', null,  401);
            }

            $token = $user->createToken('auth-token')->plainTextToken;

            return Response::api('Login successful', [
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            return Response::api('User profile fetched successfully', $request->user());
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return Response::api('Logout successful');
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }
}
