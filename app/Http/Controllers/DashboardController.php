<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Arsip;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $arsipCount = Arsip::count();
        $categoryCount = Category::count();
        $userCount = User::count();
        return view('dashboard', ['arsip_count' => $arsipCount, 'category_count' => $categoryCount, 'user_count' => $userCount]);
    }
}
