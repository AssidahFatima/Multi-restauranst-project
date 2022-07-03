<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Settings;
use App\Util;
use App\Theme;

// 16.02.2021

class WebSiteSettingsController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $logoid = DB::table('settings')->where("param", 'web_logo')->get()->first()->value;
        $t = DB::table('image_uploads')->where("id", $logoid)->get()->first();
        if ($t != null)
            $web_filename = $t->filename;
        else
            $web_filename = "";

        return view('webSettings', [
            'web_mainColor' => DB::table('settings')->where("param", 'web_mainColor')->get()->first()->value,
            'web_mainColorHover' => DB::table('settings')->where("param", 'web_mainColorHover')->get()->first()->value,
            'web_radius' => DB::table('settings')->where("param", 'web_radius')->get()->first()->value,
            'web_logo' => $logoid,
            'web_filename' => $web_filename
        ]);
    }

    public function webSaveSettings(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1"], 200);

        DB::table('settings')->where("param", 'web_mainColor')->
            update(['value' => $request->input('web_mainColor'), 'updated_at' => new \DateTime(),]);
        DB::table('settings')->where("param", 'web_mainColorHover')->
            update(['value' => $request->input('web_mainColorHover'), 'updated_at' => new \DateTime(),]);
        DB::table('settings')->where("param", 'web_radius')->
            update(['value' => $request->input('web_radius'), 'updated_at' => new \DateTime(),]);
        DB::table('settings')->where("param", 'web_logo')->
            update(['value' => $request->input('web_logo'), 'updated_at' => new \DateTime(),]);

        return response()->json(['error' => "0"], 200);
    }

    public function webRestoreSettings(Request $request)
    {
        DB::table('settings')->where("param", 'web_mainColor')->
            update(['value' => '029600', 'updated_at' => new \DateTime(),]);
        DB::table('settings')->where("param", 'web_mainColorHover')->
            update(['value' => '66b88c', 'updated_at' => new \DateTime(),]);
        DB::table('settings')->where("param", 'web_radius')->
            update(['value' => '6', 'updated_at' => new \DateTime(),]);
        DB::table('settings')->where("param", 'web_logo')->
            update(['value' => '99', 'updated_at' => new \DateTime(),]);
        return response()->json(['error' => "0"], 200);
    }



}
