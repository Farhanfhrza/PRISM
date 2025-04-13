<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\Activity_log;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        return view('request.index');
    }

    public function create()
    {
        return view('request.create');
    }
}
