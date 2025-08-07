<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function nin()
    {
        return view('nin-services');
    }

    public function bvn()
    {
        return view('bvn-services');
    }

    public function verification()
    {
        return view('verification');
    }

    public function vip()
    {
        return view('vip-services');
    }
}
