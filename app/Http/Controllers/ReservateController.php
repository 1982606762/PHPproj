<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Environment\Console;

class ReservateController extends Controller
{
    public function store(Request $request)
    {
        $reservation = new reservation();
        $time = time();
        $errorMsg = array();
        $rda = $request->post('rsv_day_at');
        if ($rda!=null){
            if (Auth::check()){
                $currentUser = Auth::user();
                $checkIfHasRsvThatDay = Reservation::where('reserve_date_at',$rda)->where('email',$currentUser['email'])
                    ->count();
                if ($checkIfHasRsvThatDay==0) {
                    $totalRsv = Reservation::where('reserve_date_at', $rda)->count();
                    if ($totalRsv < 10) {
                        $data = [
                            'invitation' => $this->getUniqueInvitationCode(),
                            'email' => $currentUser['email'],
                            'reserve_date_at' => $rda,
                            'checkin' => false,
                            'checkin_at' => date('Y-m-d H:i:s', $time),
                        ];
                        $reservation->create($data);
                        return redirect()->route('users.show',Auth::user());
                    } else {$errorMsg['fullRsv'] = 1;}
                }else{$errorMsg['aldHave']=1;}
            }else{$errorMsg['auth']=1;}
        }else{$errorMsg['rba']=1;}
    }

    public function cancel(Request $request)
    {
        if($request->input('ivtcd') !== null) {
            $invitationCode = $request->input('ivtcd');
            if (Auth::check()) {
                $currentUser = Auth::user();
                $data = Reservation::where('invitation',$invitationCode)->first();
                //Prevent other users from deleting information
                if ($data->email == $currentUser['email']){
                    $result = Reservation::where('invitation',$invitationCode)->delete();
                    return redirect()->route('users.show',Auth::user());
                }
            }
        }
        return redirect()->route('users.show',Auth::user());
    }

    public function checkin(Request $request)
    {
        $reservation = new Reservation();
        $errorMsg = array();
        $invitationCode = $request->post('ivtcd');
        $pwd = $request->post('pwd');
        // echo($invitationCode.$pwd);
        if ($invitationCode!=null&&$pwd!=null){
            if (Auth::check()){
                $currentUser = Auth::user();
                $checkIfHaveTheIC = Reservation::where('invitation',$invitationCode)->count();
                if ($checkIfHaveTheIC!=0) {
                    if (Hash::check($pwd,$currentUser->getAuthPassword())){
                        $ifRightUser = Reservation::where('email', $currentUser['email'])
                            ->where('invitation',$invitationCode)->count();
                        if ($ifRightUser==1){
                            $checkStatus = Reservation::select('checkin')->where('invitation',$invitationCode)
                                ->first();
                            if ($checkStatus->checkin==0) {
                                $data = [
                                    'checkin' => true,
                                ];
                                $reservation->where('invitation',$invitationCode)->update($data);
                                session()->flash('info', '签到成功!'.$currentUser['name']);
                                return redirect()->route('users.show',Auth::user());
                            } else {$errorMsg['haveVerified'] = 1;}
                        }else{$errorMsg['notMatch']=1;}
                    }else{$errorMsg['wrongPwd']=1;}
                }else{$errorMsg['dontHave']=1;}
            }else{$errorMsg['auth']=1;}
        }else{$errorMsg['loseParam']=1;}
    }

    function getUniqueInvitationCode(): string
    {
        $code = $this->makeInvitationCode();
        while (true){
            $checker = Reservation::where('invitation',$code)->count();
            if ($checker==0){
                break;
            }
            $code = $this->makeInvitationCode();
        }
        return $code;
    }

    function makeInvitationCode( $length = 6 ): string
    {
        $chars = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y', 'z');
        $keys = array_rand( $chars, $length );
        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            $password .= $chars[$keys[$i]];
        }
        return $password;
    }
}
