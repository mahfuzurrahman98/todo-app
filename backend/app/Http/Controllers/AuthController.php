<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {}

    public function logout() {}

    public function profile(Request $request) {}
}
