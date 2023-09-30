<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Nette\Utils\Random;

class UserController extends Controller
{
    public function profile()
    {
        $user = User::with('refer')->with('orders', function ($q) {
            $q->with('seller')->latest()->take(5);
        })->find(auth()->user()->id);
        $tree = User::buildTree($user->ref_code);
        return view('pages.users.profile', compact('user', 'tree'));
    }

    public function index(Request $request)
    {
        $userList = User::all();
        $breadcrumbs = [
            ['link' => "users", 'name' => "Users"]
        ];
        $users = User::query()->with('refer');

        if ($request->has('user_type')) {
            $users->where('type', $request->user_type);
        }

        if ($request->has('search')) {
            $users->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }

        $users =  $users->latest()->paginate('10');
        return view('pages.users.list', compact('users', 'breadcrumbs', 'userList'));
    }

    public function show($id)
    {
        $user = User::with('refer')->with('orders', function ($q) {
            $q->with('seller')->latest()->take(5);
        })->find($id);
        $tree = User::buildTree($user->ref_code);
        return view('pages.users.show', compact('user', 'tree'));
    }

    public function userEdit($id)
    {
        $breadcrumbs = [
            ['link' => "users", 'name' => "Users"], ['name' => "Edit"]
        ];
        $user_data = User::with('refer')->find($id);
        return view('pages.users.edit', compact('user_data', 'breadcrumbs'));
    }

    public function userEdited(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
        ]);
        $data = $request->only('name');
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif',
            ]);
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/'), $filename);
            $data['image'] = 'uploads/' . $filename;
        }
        if (!empty($request->get('type'))) {
            $data['type'] = $request->type;
        }
        if (!empty($request->get('phone'))) {
            $data['phone'] = $request->phone;
        }
        if (!empty($request->get('password'))) {
            $data['password'] = Hash::make($request->password);
        }

        $data['can_create_customer'] = $request->can_create_customer;

        $user = User::find($id);
        $user->update($data);

        Session::flash('message',  $user->name . ' updated successfully!');

        return redirect()->route('user.show', $user->id);
    }

    public function userAddButton(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'type' => 'required',
            'phone' => 'required',
            'password' => 'required|min:6'
        ]);
        $data = $request->only(['name', 'email', 'type', 'phone', 'ref_by', 'can_create_customer']);
        $data['password'] = Hash::make($request->password);
        $data['can_create_customer'] = $request->can_create_customer ?? 0;
        $user = User::create($data);
        $user->update([
            'ref_code' => $user->id . Random::generate(6)
        ]);

        Session::flash('message',  $user->name . ' created successfully!');

        return redirect()->back();
    }
}
