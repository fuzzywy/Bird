<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App;

class NavController extends Controller
{
    /**
     * 语言切换
     *
     * @param Request $request HTTP请求
     *
     * @return Request
     */
    public function localeLang(Request $request)
    {
        $lang = Input::get('lang');
        $request->session()->put('language', $lang);
        // App::setlocale('en');
        return $lang;
    }

}//end class
