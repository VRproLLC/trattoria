<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegisterUsers;
use App\Providers\RouteServiceProvider;
use App\Rules\SmsCode;
use App\User;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * @throws \Exception
     */
    public function sendSms(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'min:5', 'max:255', 'unique:users'],
        ]);

        if($validator->fails()){
            return redirect()->route('register')->with(
                [
                    'success' => $validator->getMessageBag()->first()
                ]
            );
        }
        if($registers = RegisterUsers::where('phone', $request->get('phone'))->first()){
            $registers->delete();
        }

        $code = rand(10000, 99999);

        $registerId = RegisterUsers::create([
            'ip' => request()->server('SERVER_ADDR'),
            'code' => $code,
            'phone' => request('phone'),
            'ua' => request()->server('HTTP_USER_AGENT'),
        ]);

        TurboSMS::sendMessages(request('phone'), trans('auth.sms_register_start', ['code' => $code]), 'sms');

        return redirect()->route('register.confirm', ['id' => $registerId->id])->with(
            [
                'success' => trans('auth.register_start', [
                    'code' => $code
                ]),
                'current_phone' => request('phone')
            ]
        );
    }

    public function confirm(int $id = 0){
        $registerId = RegisterUsers::where('id', $id)->first();

        return view('auth.register.confirm', [
            'register' => $registerId
        ]);
    }


    /**
     * @throws \Exception
     */
    public function confirmDone(Request $request, int $id = 0)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'code' => ['required', 'string', 'min:3', new SmsCode()],
        ]);

        if($validator->fails()) {
            return redirect()->route('register.confirm', ['id' => $id])->withErrors(
                $validator
            )->withInput();
        }

        User::create([
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'password' => Hash::make($request->get('password')),
            'language' => app()->getLocale()
        ]);

        if($registers = RegisterUsers::where('id', $id)->first()){
            $registers->delete();
        }

        if(auth()->attempt(['phone' => $request->get('phone'), 'password' => $request->get('password')])){
            return redirect()->route('main');
        }
        return redirect()->route('login');
    }
}
