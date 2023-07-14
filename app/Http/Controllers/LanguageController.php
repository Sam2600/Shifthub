<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class LanguageController extends Controller
{
    public function changeLanguage($locale)
    {
        if (in_array($locale, ['en', 'my'])) {
            session(['locale' => $locale]);
        }
        
        return redirect()->back();
    }
}



