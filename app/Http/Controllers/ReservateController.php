<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Environment\Console;

class ReservateController extends Controller
{
    public function __construct() {
        $this->middleware('guest', [
            'only' => []
        ]);
    }
    public function store(Request $request)
    {
        $reservation = new reservation();
        $time = time();
        $rda = $request->post('rsv_day_at');
        if (Auth::check()) {
            $currentUser = Auth::user();
            $checkReserve = Reservation::where('reserve_date_at', $rda)->where('email', $currentUser['email'])
                ->count();
            if ($checkReserve == 0) {
                $allReserve = Reservation::where('reserve_date_at', $rda)->count();
                if ($allReserve < 10) {
                    $data = [
                        'invitation' => $this->getUniqueInvitationCode(),
                        'email' => $currentUser['email'],
                        'reserve_date_at' => $rda,
                        'checkin' => false,
                        'checkin_at' => date('Y-m-d H:i:s', $time),
                    ];
                    $reservation->create($data);
                } else {
                    session()->flush('danger', '十个名额已满');
                }
            } else {
                session()->flush('danger', '本日已有预定');
            }
        } else {
            session()->flush('danger', '请登录');
        }
        return redirect()->route('users.show', Auth::user());
    }

    public function cancel(Request $request)
    {
        if ($request->input('ivtcd') !== null) {
            $invitationCode = $request->input('ivtcd');
            if (Auth::check()) {
                $currentUser = Auth::user();
                $data = Reservation::where('invitation', $invitationCode)->first();
                if ($data->email == $currentUser['email']) {
                    $result = Reservation::where('invitation', $invitationCode)->delete();
                    return redirect()->route('users.show', Auth::user());
                }
            }
        }
        return redirect()->route('users.show', Auth::user());
    }

    public function checkin(Request $request)
    {
        $reservation = new Reservation();
        $invitationCode = $request->post('ivtcd');
        $pwd = $request->post('pwd');
        // echo($invitationCode.$pwd);
        if ($invitationCode != null && $pwd != null) {
            if (Auth::check()) {
                $currentUser = Auth::user();
                $checkIfIC = Reservation::where('invitation', $invitationCode)->count();
                if ($checkIfIC != 0) {
                    if (Hash::check($pwd, $currentUser->getAuthPassword())) {
                        $ifRightUser = Reservation::where('email', $currentUser['email'])
                            ->where('invitation', $invitationCode)->count();
                        if ($ifRightUser == 1) {
                            $checkStatus = Reservation::select('checkin')->where('invitation', $invitationCode)
                                ->first();
                            if ($checkStatus->checkin == 0) {
                                $data = [
                                    'checkin' => true,
                                ];
                                $reservation->where('invitation', $invitationCode)->update($data);
                                session()->flash('info', '签到成功!' . $currentUser['name']);
                                return redirect()->route('users.show', Auth::user());
                            } else {

                                session()->flush('danger', '邀请码已验证');
                            }
                        } else {

                            session()->flush('danger', '邀请码不属于你');
                        }
                    } else {

                        session()->flush('danger', '密码错误');
                    }
                } else {

                    session()->flush('danger', '邀请码不存在');
                }
            } else {

                session()->flush('danger', '用户未经认证，请重新登陆');
            }
        } else {

            session()->flush('danger', '缺少一些必要的参数，请重新登陆');
        }
    }

    function getUniqueInvitationCode(): string
    {
        $code = $this->makeInvitationCode();
        while (true) {
            $checker = Reservation::where('invitation', $code)->count();
            if ($checker == 0) {
                break;
            }
            $code = $this->makeInvitationCode();
        }
        return $code;
    }

    function makeInvitationCode($length = 6): string
    {
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y', 'z'
        );
        $keys = array_rand($chars, $length);
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[$keys[$i]];
        }
        return $password;
    }
}
