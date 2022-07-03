<?php

namespace App\Http\Controllers;

use App\Logging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Settings;
use App\Lang;

class PermissionsController extends Controller
{
    public function permissions(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Permissions Screen");

        $data = DB::table('permissions')->get();
        return view('permissions', ['idata' => $data, 'texton' => "", 'text' => '']);
    }

    public function permissionSet(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error'=>"1"]);
        if (Auth::user()->role != 1)     // admin
            return response()->json(['error'=>"2"]);

        if (Settings::isDemoMode()){
            Logging::log("Permission Save" . Lang::get(487)); // Abort! This is demo mode
            return response()->json(['error'=>"3", 'text'=>Lang::get(489)]); // This is demo app. You can't change this section
        }

        Logging::log(Lang::get(488)); // Permission Save

        $permissions = DB::table('permissions')->get();
        foreach ($permissions as &$data) {
            $value = $request->input('id1_' . $data->id, "");
            if ($value != "")
                DB::table('permissions')->where('id', $data->id)->update(array('role1' => $value, 'updated_at' => new \DateTime()));
            $value = $request->input('id2_' . $data->id, "");
            if ($value != "")
                DB::table('permissions')->where('id', $data->id)->update(array('role2' => $value, 'updated_at' => new \DateTime()));
            $value = $request->input('id3_' . $data->id, "");
            if ($value != "")
                DB::table('permissions')->where('id', $data->id)->update(array('role3' => $value, 'updated_at' => new \DateTime()));
            $value = $request->input('id4_' . $data->id, "");
            if ($value != "")
                DB::table('permissions')->where('id', $data->id)->update(array('role4' => $value, 'updated_at' => new \DateTime()));
        }

        return response()->json(['error'=>"0"]);
    }

}
