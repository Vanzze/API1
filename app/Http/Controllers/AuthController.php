<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validasi = FacadesValidator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:5',
            'address' => 'required',
            'age' => 'required|integer',
            'email' => 'required|unique:users,email'
        ]);

        if ($validasi->fails()) {
            return response()->json($validasi->errors(), 400);
        }

        $user = User::create([
            'username' => $request->username,
            'age' => $request->age,
            'address' => $request->address,
            'password' => $request->password,
            'email' => $request->email
        ]);

        return response()->json($user, 200);
    }

    public function login(Request $request) {
        $validasi = FacadesValidator::make($request->all(), [
            'password' => 'required|min:5',
            'email' => 'required'
        ]);

        if ($validasi->fails()) {
            return response()->json($validasi->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();
        if(!$user) {
            return response()->json([
                'error' => 'Email atau Password anda salah!'
            ], 400);
        }

        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                'error' => 'Email atau Password anda salah blablabla!'
            ], 400);
        }

        return response()->json($user, 200);
    }
}
