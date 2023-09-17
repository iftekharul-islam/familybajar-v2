<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function checkLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:20',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found!');
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->route('login')->with('error', 'Invalid login credentials');
        }
        // auth and dashboard
    }

    public function registration()
    {
        return view('auth.registration');
    }
    public function checkRegistration(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:20',
            'checked' => 'required',
        ]);

        $data = $request->only('name', 'email');
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        if ($user) {
            // auth and dashboard
        }
    }

    public function forgetPassword()
    {
        return view('auth.forget-password');
    }
    public function checkForgetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $reset_token = Str::random(16);
            Mail::to($request->email)->send(new ResetPassword($reset_token, $user));
            $user->update(['reset_token' => $reset_token]);
            dd("Mail Sent");
            // directly open default email browser
        }
        dd('User not found');
        // return redirect()->route('login')->with('error', 'User not found!');
    }
    public function resetPassword(Request $request)
    {
        $reset_token = $request->token;
        return view('auth.reset-password', compact('reset_token'));
    }
    public function checkResetPassword(Request $request)
    {
        $user = User::where('reset_token', $request->reset_token)->first();
        if ($user) {
            $password = Hash::make($request->password);
            $user->update(['reset_token' => null, 'password' => $password]);
            dd("dashboard");
            // auth and dashboard
        }
        dd('User not found');
        return redirect()->route('login')->with('error', 'User not found!');
    }
}
