<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function create()
    {
        return view('users/signup');
    }

    public function show(User $user)
    {
        Auth::login($user);
        $GLOBALS['conf'] = require_once("config/days.php");
        //read current user.
        $currentUser = Auth::user();
        //grab current user reservation info
        $data = reservation::where('email', $currentUser['email'])->orderBy('reserve_date_at', 'desc')->get();
        $currentUserTotal = reservation::where('email', $currentUser['email'])->count();

        $assign = array();
        $assign['cuName'] = $currentUser['name'];
        $assign['cuEmail'] = $currentUser['email'];
        $assign['cuDay'] = $GLOBALS['conf']['now'];
        $assign['ttDays'] = $GLOBALS['conf']['continue'];
        $assign['reservationInfo'] = $data;
        $assign['user'] = $currentUser;
        $assign['cuTtRsv'] = $currentUserTotal;
        return view('users.show', $assign);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users|max:255',
            'name' => 'required|min:6|unique:users|alpha_dash',
            'password' => 'required|min:6|confirmed|'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        Auth::login($user);
        $GLOBALS['conf'] = require_once("config/days.php");
        //read current user.
        $currentUser = Auth::user();
        //grab current user reservation info
        $data = reservation::where('email', $currentUser['email'])->orderBy('reserve_date_at', 'desc')->get();
        $currentUserTotal = reservation::where('email', $currentUser['email'])->count();

        $assign = array();
        $assign['cuName'] = $currentUser['name'];
        $assign['cuEmail'] = $currentUser['email'];
        $assign['cuDay'] = $GLOBALS['conf']['now'];
        $assign['ttDays'] = $GLOBALS['conf']['continue'];
        $assign['reservationInfo'] = $data;
        $assign['user'] = $currentUser;
        $assign['cuTtRsv'] = $currentUserTotal;
        session()->flash('success', '注册成功');

        return view('users.show', $assign);
    }

}
