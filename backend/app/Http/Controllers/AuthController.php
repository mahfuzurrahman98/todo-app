<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $email = $request->email;
            $password = $request->password;

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

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return Response::api('Logout successful');
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
    
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return Response::api('Registration successful', [
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }
    
    public function forgotPassword(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email|exists:users']);
            
            $status = Password::sendResetLink(
                $request->only('email')
            );
            
            if ($status === Password::RESET_LINK_SENT) {
                return Response::api('Password reset link sent to your email');
            }
            
            return Response::api('Unable to send password reset link', null, 400);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }
    
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );
            
            if ($status === Password::PASSWORD_RESET) {
                return Response::api('Password has been reset successfully');
            }
            
            return Response::api('Unable to reset password', null, 400);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }
}
