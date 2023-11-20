<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{

    public function set($language)
    {
        Language::where('active', 1)->where('value', $language)->firstOrFail();

        if(auth()->user()){
            User::where('id', auth()->id())->update(
                [
                    'language' => $language
                ]
            );
        }
        Cookie::queue('language', $language, 5000);
        return redirect()->back();
    }
}
