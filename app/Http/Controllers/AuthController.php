<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    public function register(Request $request) {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $request->user()->fill([
            'password' => Hash::make($request->newPassword)
        ])->save();

        $user = User::create($data);

        $token = $user->createToken('API token')->accessToken;

        return response()->json([ 'user' => $user, 'token' => $token],200);
    }

    public function login(Request $request) {
        $data = $request->validate([
            'name' => 'email|required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'Incorrect Details.
            Please try again']);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response()->json(['user' => auth()->user(), 'token' => $token],200);
    }

    public function logout(Request $request): RedirectResponse
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return response()->json(['Status'=> "Logged Out"],200);
}
}
