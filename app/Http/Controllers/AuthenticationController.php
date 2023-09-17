<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login_cover()
    {
        $pageConfigs = ['blankPage' => true];

        return view('/content/authentication/auth-login-cover', ['pageConfigs' => $pageConfigs]);
    }

    public function login_confirm(Request $request)
    {
//        return $request->all();
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->route('home');
        }

        return redirect()->route('auth-login-cover')
            ->with('error', 'Invalid login credentials');
    }

    public function logout(Request $request)
    {
        // Your custom logout logic here

        Auth::logout();

        $request->session()->invalidate();

        return redirect()->route('auth-login-cover');
    }
}
