<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Settings;
use App\Logging;

class LoggingController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Logging Screen");

        return LoggingController::view("","");
    }

    function view($green, $text){
        $logging = DB::table('logging')->where('param4', '=', '')->orderBy('updated_at', 'desc')->limit(100)->get();
        $loggingApi = DB::table('logging')->where('param4', '=', 'api')->orderBy('updated_at', 'desc')->limit(100)->get();
        $loggingCount = count(DB::table('logging')->where('param4', '=', '')->get()) / 100;
        $loggingCountApi = count(DB::table('logging')->where('param4', '=', 'api')->get()) / 100;
        return view('logging', ['logging' => $logging, 'loggingApi' => $loggingApi, 'loggingCount' => $loggingCount, 'loggingCountApi' => $loggingCountApi]);
    }

    function loadPage(Request $request){
        $page = $request->input('page') ?: 0;
        $type = $request->input('type') ?: "";
        $logging = DB::table('logging')->where('param4', '=', $type)->orderBy('updated_at', 'desc')->offset($page*100)->limit(100)->get();
        return response()->json(['logging' => $logging]);
    }

}
