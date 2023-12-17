<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IosTokenController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->one_signal_token;

        if(auth()->check()){
            $user = auth()->user();
            $user->onsignal_token = $token;
            $user->save();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
