<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            '====' => "================== New Account Created ==================",
            'Name' => $user->name,
            'Email' => $user->email,
            'Created at' => $user->created_at,
            'access_token' => $token,
        ]);
    }

    public function login(LoginRequest $request)
    {

        if(!Auth::attempt($request->all())){
            return response()->json(['Error' => 'Invalid credentials'], 401);
        };

        $user  = auth()->user();
        $token = auth()->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            '========' => '================ Welcome Back ================',
            'username' => $user->name,
            'email' => $user->email,
            'access_token' => $token,
        ]);
    }



}
