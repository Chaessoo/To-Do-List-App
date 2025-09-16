<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{


    public function getAllUsers()
{
    $users = User::all();

    return response()->json([
        'status' => 200,
        'message' => 'Daftar semua user',
        'users' => $users,
    ]);
}


    public function register(Request $request) {
        $validator = Validator::make($request->all() , [
            'name' => 'required|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed'
        ]);

        //bikin error
        if ($validator->fails()) {
            return response()->json([
            'status' => 422,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);

            //klo ga error masuk sini
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' =>Hash::make($request->password),
            ]);
                }

            $token = $user->createToken('API Token')->plainTextToken;


            return response()->json([
                'status' => 200,
                'token' => $token,
                'messages' => "Register Berhasil",
                'user' => $user
            ]);
        }



    public function login(Request $request) {
        $validator = Validator::make($request->all() , [
            'email' => 'required|max:191',
            'password' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json([
                "status" => 422,
                "messages" =>$validator->messages()
            ], 422);
        } else {
            $user = User::where('email' , $request->email)->first();

            if(!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    "status" => 401,
                    "messages" => "Password Atau Email Yang Anda Masukkan Salah"
                ], 401);
            } else {

                $token = $user->createToken($user->email)->plainTextToken;

                return response()->json([
                        'status' => 200,
                        'name' => $user->name,
                        'email' => $user->email,
                        'token' => $token,
                        'messages' => 'Logged in Succesfully'
                ]);

            }
        }
    }

    public function logout(Request $request)
{
    // hapus token yang dipakai user saat ini
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'status' => 200,
        'message' => 'Logout berhasil'
    ]);
}

}

