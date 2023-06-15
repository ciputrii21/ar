<?php

namespace App\Http\Controllers;

use App\Models\RentLogs;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ArsipRentController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', 1)->where('status', '!=', 'inactive')->get();
        $arsips = Arsip::all();
        return view('arsip-rent', ['users' => $users, 'arsips' => $arsips]);
    }

    public function store(Request $request) 
    {
        $request['rent_date'] = Carbon::now()->toDateString();
        $request['return_date'] = Carbon::now()->addDay(3)->toDateString();
        
        $arsip = Arsip::findOrFail($request->arsip_id)->only('status');

        if($arsip['status'] != 'in stock' ) {
            Session::flash('message', 'Cannot rent, the arsip is not available'); 
            Session::flash('alert-class', 'alert-danger'); 
            return redirect('arsip-rent');
        }
        else{
            $count = RentLogs::where('user_id', $request->user_id)->where('actual_return_date', null)->count();
            
            if ($count >= 3) {
                Session::flash('message', 'Cannot rent, user has reach limit of arsip'); 
                Session::flash('alert-class', 'alert-danger'); 
                return redirect('arsip-rent');
            }
            else {
                    try {
                    DB::beginTransaction();
                    // proses insert to rent_logs table
                    RentLogs::create($request->all());
                    //proses update arsip table
                    $arsip = Arsip::findOrFail($request->arsip_id);
                    $arsip->status = 'not available';
                    $arsip->save();
                    DB::commit();

                    Session::flash('message', 'Rent Arsip success!!!'); 
                    Session::flash('alert-class', 'alert-success'); 
                    return redirect('arsip-rent');
                    } 
                    catch (\Throwable $th) {
                        DB::rollBack();
                    }
            }
        }
    }

    public function returnArsip() 
    {
        $users = User::where('id', '!=', 1)->where('status', '!=', 'inactive')->get();
        $arsips = Arsip::all();
        return view('return-arsip', ['users' => $users, 'arsips' => $arsips]);
    }

    public function saveReturnArsip(Request $request)
    {
        //user & arsip yang dipilih untuk direturn benar, maka berhasil di return arsip
        // jika user & buku yang dipilih untuk direturn salah, maka muncul eror notice
        $rent = RentLogs::where('user_id', $request->user_id)->where('arsip_id', $request->arsip_id)->where('actual_return_date', null);
        $rentData = $rent->first();
        $countData = $rent->count();

        if ($countData == 1) {
            // kita akan return arsip
            $rentData->actual_return_date = Carbon::now()->toDateString();
            $rentData->save();

            Session::flash('message', 'The arsip is returned successfully'); 
            Session::flash('alert-class', 'alert-success'); 
            return redirect('arsip-return');
        }
        else {
            Session::flash('message', 'There is error in process'); 
            Session::flash('alert-class', 'alert-danger'); 
            return redirect('arsip-return');
        }
    }
}
