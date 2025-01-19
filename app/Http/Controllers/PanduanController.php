<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanduanController extends Controller
{
    public function haid()
    {
        return view('panduan.haid');
    }

    public function qadha()
    {
        return view('panduan.qadha');
    }

}
