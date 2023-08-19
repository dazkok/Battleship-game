<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function home($p_link = 'home')
    {
        $this->setSessionId();
        $variables = collect([]);

        return view('home.home', compact('variables'));
    }

    public function setSessionId()
    {
        if(!session()->has('user_session_id')) {
            session(['user_session_id' => Str::random(18)]);
        }
    }
}
