<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
		$dpage_id = 0;
        return view('admin.index',compact('dpage_id'));
    }
}
