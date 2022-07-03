<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Logging;

class OrderStatusesController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Order Statuses Screen");
        
        $data = DB::table('orderstatuses')->get();
        return view('orderstatuses', ['idata' => $data, 'texton' => "", 'text' => '']);
    }

}
