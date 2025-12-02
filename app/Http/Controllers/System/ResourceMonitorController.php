<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;

class ResourceMonitorController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('backend.system.monitor')->render();

    }

}
