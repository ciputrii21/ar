<?php

namespace App\Http\Controllers;

use App\Models\Category;
use session;
use App\Models\Arsip;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index()
    {   
        $arsips = Arsip::all();
        return view('arsip', ['arsips' => $arsips]);
    }

    public function add()
    {
        $categories = Category::all();
        return view('arsip-add', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'arsip_code' => 'required|unique:arsips|max:255',
            'title' => 'required|max:255'
        ]);

        $newName = '';
        if($request->file('image')){
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->title.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('cover', $newName);
        }

        $request['cover'] = $newName;
        $arsip = Arsip::create($request->all());
        $arsip->categories()->sync($request->categories);
        return redirect('arsips')->with('status','Arsip Added Successfully');
    }

    public function edit($slug)
    {
        $arsip = Arsip::where('slug', $slug)->first();
        $categories = Category::all();
        return view('arsip-edit', ['categories' => $categories, 'arsip' => $arsip]);
    }

    public function update(Request $request, $slug)
    {
        if($request->file('image')){
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->title.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('cover', $newName);
            $request['cover'] = $newName;
        }

        $arsip = Arsip::where('slug', $slug)->first();
        $arsip->update($request->all());

        if($request->categories) {
            $arsip->categories()->sync($request->categories);
        }

        return redirect('arsips')->with('status','Arsip Updated Successfully');
    }

    public function delete($slug)
    {
        $arsip = Arsip::where('slug', $slug)->first();
        return view('arsip-delete', ['arsip' => $arsip ]);
    }

    public function destroy($slug)
    {
        $arsip = Arsip::where('slug', $slug)->first();
        $arsip->delete();
        return redirect('arsips')->with('status','Arsip Deleted Successfully');
    }

    public function deletedArsip()
    {
        $deletedArsips = Arsip::onlyTrashed()->get();
        return view('arsip-deleted-list', ['deletedArsips' => $deletedArsips]);
    }

    public function restore($slug)  
    {
        $arsip = Arsip::withTrashed()->where('slug', $slug)->first();
        $arsip->restore();
        return redirect('arsips')->with('status','Arsip Restored Successfully');
    }
}
