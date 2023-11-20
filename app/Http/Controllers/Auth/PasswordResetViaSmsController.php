<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Wrappers\TurboSmsWrapper;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetViaSmsController extends Controller
{

    public function reset_via_sms()
    {

        return view('auth.passwords.reset_via_sms');
    }

    public function sendSms()
    {
        $data = request()->validate([
            'phone' => 'required|exists:users,phone'
        ]);
        $code = rand(10000, 99999);
        $phone = $data['phone'];

        $user = User::where('phone', $phone)->first();
        $user->password_reset_sms = $code;
        $user->save();

        TurboSMS::sendMessages($phone, trans('auth.sms_restore_start', ['code' => $code]), 'sms');

        return redirect()->route('password.reset_via_sms')->with(
                [
                    'success' => trans('auth.restore_start', [
                        'code' => $code
                    ]),
                    'current_phone' => $phone
                ]
        );
    }

    public function update()
    {
        $data = request()->validate([
            'phone' => 'required|exists:users,phone',
            'code' => 'required|string|min:3',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('phone', $data['phone'])->where('password_reset_sms', $data['code'])->first();

        if(!$user){
            return redirect()->back()->with(['error' => trans('auth.restore_error_code'), 'current_phone' => $data['phone']]);
        }
        $user->password = Hash::make($data['password']);
        $user->password_reset_sms = null;
        $user->save();

        return redirect()->route('login')->with(['success' => trans('auth.restore_done')]);
    }
}
