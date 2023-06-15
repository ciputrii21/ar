<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        if ($request->category || $request->title) {
            $arsips = Arsip::where('title', 'like', '%'.$request->title.'%')
                        ->orWhereHas('categories', function($q) use($request) {
                            $q->where('categories.id', $request->category);
                        })
                        ->get();
        }
        else{
            $arsips = Arsip::all();
        }
        return view('arsip-list', ['arsips' => $arsips, 'categories'=>$categories]);
    }
}
