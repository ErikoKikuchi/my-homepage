<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->attributes->get('section');
        return view("pages.{$section}.admin.admin-dashboard");
    }
}
