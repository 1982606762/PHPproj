<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    public function create()
    {
        return view('users/signup');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email|unique:users|max:255',
            'name' => 'required|min:6|unique:users|alpha_dash',
            'password' => 'required|min:6|confirmed|'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        session()->flash('success', '注册成功');
        return redirect()->route('users.show', [$user]);
    }
}