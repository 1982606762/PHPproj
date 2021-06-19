<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.login');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            session()->flash('info', "登陆成功");
            $GLOBALS['conf'] = require_once("config/days.php");
            //read current user.
            $currentUser = Auth::user();
            //grab current user reservation info
            $data = reservation::where('email', $currentUser['email'])->orderBy('reserve_date_at', 'desc')->get();
            $currentUserTotal = reservation::where('email',$currentUser['email'])->count();

            $assign = array();
            $assign['cuName'] = $currentUser['name'];
            $assign['cuEmail'] = $currentUser['email'];
            $assign['cuDay'] = $GLOBALS['conf']['now'];
            $assign['ttDays'] = $GLOBALS['conf']['continue'];
            $assign['reservationInfo'] = $data;
            $assign['user'] = $currentUser;
            $assign['cuTtRsv'] = $currentUserTotal;

            return view('users.show', $assign);
        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
