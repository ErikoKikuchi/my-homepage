<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminHomeController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // Inertia化を見据え、当面は暫定でセクション別に出し分け
        // 将来的にはここが Inertia::render("{$admin->section}/Admin/Dashboard") に置き換わる想定
        return view("{$admin->section}.admin.admin-dashboard");
    }
}
