<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login()
    {
        $pageConfigs = ['blankPage' => true];
        return view('/pages/auth/login', ['pageConfigs' => $pageConfigs]);
    }

    public function loginConfirm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->status != true) {
                return redirect()->route('login')
                    ->with('loginError', 'Account is deactived')->withInput();
            }
            return redirect()->route('home');
        }
        return redirect()->route('login')
            ->with('loginError', 'Invalid login credentials')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }

    public function loginAsUser($id)
    {
        $user = User::find($id);
        if (Auth::user()->type == 1) {
            Auth::login($user);
            return redirect()->route('home');
        } else {
            return redirect()->back()
                ->with('error', 'Invalid login credentials')->withInput();
        }
    }
}
