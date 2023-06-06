<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Hash;

class LoginController extends Controller
{
    public function Login(Request $request)
    {
        $credentials = $request -> validate ([
            'email' => ['required', 'email'],
            'password' => ['required'], ]);
        //Authentication
        if (Auth::attempt($credentials)) {
            return response()->json(['status' => true, 'message' => "Successfully logged in"]);
        }
        return response()->json(['status' => false, 'message' => "Failed to login"]);
    }

    public function Logout() {
        Auth::logout();

        return response()->json(['status' => true, 'message' => "Logged out"]);

    }

}
