<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('refer')->get();
        return view('pages.users.list', compact('users'));
    }

    public function userAdd(Request $request)
    {
        return view('pages.users.add');
    }

    public function userAddButton(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'type' => 'required',
            'password' => 'required|min:6'
        ]);
        $data = $request->only(['name', 'email', 'type', 'ref_by']);
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);
        if ($user) {
            return redirect()->route('users');
        }
        return redirect()->route('userAdd')
            ->with('error', 'Something wents wrong!')->withInput();
    }
}
