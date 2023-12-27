<?php

namespace App\Services;

use App\Models\Language;

class LanguageService
{
    private static $lang;

    public static function setLang(){
        $language = Language::where('value', app()->getLocale())->first();

        if (!$language) {
            self::$lang = 1;
        }
        self::$lang = $language->id;
    }

    public static function getLang(){
        return self::$lang;
    }
}
