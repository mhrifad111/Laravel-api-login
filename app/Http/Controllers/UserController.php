<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|required',
            'password' => 'required|confirmed'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken($request->name);


        return response([
            'token' => $token->plainTextToken,
            'user' => $user

        ], 201);
    }

    public function Logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => "LogOut success"
        ]);

    }

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'require d',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => 'The provided credentials  are incorrect'
                ],401);
        }
        $token = $user->createToken($user->name);
        return response([
            'token' => $token->plainTextToken,
            'user' => $user

        ], 201);

    }
}
