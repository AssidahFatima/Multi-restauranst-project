<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Settings;
use App\Logging;
use App\UserInfo;

class ExtrasController extends Controller
{

    //
    // Extras group
    //

    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Extras Screen");

        return ExtrasController::view("","", "");
    }

    function view($green, $text, $open){
        $petani = DB::table('image_uploads')->get();
        $restaurants = DB::table('restaurants')->get();
        if (UserInfo::getUserRoleId() == 2) // manager
            $restaurants = DB::table('manager_rest')->where('user', '=', Auth::user()->id)->join("restaurants", 'restaurants.id', '=', 'manager_rest.restaurant')->get();
        $categories = DB::table('categories')->get();
        $extrasgroup = DB::table('extrasgroup')->get();
        if (UserInfo::getUserRoleId() == 2) // manager
            $extrasgroup = DB::table('manager_rest')->where('user', '=', Auth::user()->id)->join("extrasgroup", 'extrasgroup.restaurant', '=', 'manager_rest.restaurant')->get();
        $extras = DB::table('extras')->get();
        if ($open == "") {
            foreach ($extrasgroup as &$value) {
                $open = $value->id;
                break;
            }
        }
        return view('extras', ['petani' => $petani, 'irestaurants' => $restaurants, 'icategories' => $categories,
            'iextras' => $extras,
            'iextrasgroup' => $extrasgroup, 'texton' => $green, 'text' => $text, 'open'=>$open]);
    }


    public function add(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        ExtrasController::dataMassive($request, 1);
        return ExtrasController::view("green","Extras Group create successfully", "");
    }

    function dataMassive(Request $request, $update){
        $id = $request->input('id');

        $name = $request->input('name') ?: "";
        $restaurant = $request->input('restaurant') ?: 0;

        $values = array('name' => $name,
            'restaurant' => $restaurant,
            'updated_at' => new \DateTime());

        if ($update == 2){
            DB::table('extrasgroup')
                ->where('id',$id)
                ->update($values);
            Logging::log2("Extras Group->Update", "Name:" . $name . " Restaurant: " . $restaurant);
        }
        if ($update == 1) {
            $values['created_at'] = new \DateTime();
            DB::table('extrasgroup')->insert($values);
            Logging::log2("Extras Group->Create", "Name:" . $name . " Restaurant: " . $restaurant);
        }

    }

    public function delete(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $name = DB::table('extrasgroup')->where('id',$id)->get()->first()->name;
        if (Settings::isDemoMode()){
            Logging::log3("Extras Group->Delete", $name, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>'This is demo app. You can\'t change this section']);
        }

        Logging::log2("Extras Group->Delete", "Name: " . $name);

        DB::table('extrasgroup')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    public function edit(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        ExtrasController::dataMassive($request, 2);
        return ExtrasController::view("green", "Extras Group Updated successfully", $id);
    }

    //
    // Extras
    //
    public function addextras(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        return ExtrasController::extrasMassive($request, 1);
    }

    function extrasMassive(Request $request, $update){
        $id = $request->input('id');

        $name = $request->input('name') ?: "";
        $imageid = $request->input('imageid') ?: 0;
        $desc = $request->input('desc') ?: "";
        $price = $request->input('price') ?: 0.0;
        $extrasgroup = $request->input('extrasgroup') ?: 0;

        $values = array('name' => $name,
            'imageid' => $imageid, 'desc' => $desc,
            'price' => $price, 'extrasgroup' => $extrasgroup,
            'updated_at' => new \DateTime());

        if ($update == 2){
            DB::table('extras')
                ->where('id',$id)
                ->update($values);
            Logging::log2("Extras Group->Extras->Update", "Name:" . $name);
        }
        if ($update == 1) {
            $values['created_at'] = new \DateTime();
            DB::table('extras')->insert($values);
            $id = DB::getPdo()->lastInsertId();
            Logging::log2("Extras Group->Extras->Create", "Name:" . $name);
        }

        $filename = "";
        $petani = DB::table('image_uploads')->get();
        foreach ($petani as &$value) {
            if ($value->id == $imageid) {
                $filename = $value->filename;
                break;
            }
        }
        return response()->json(['ret'=>true, 'filename'=>$filename, 'id'=>$id]);
    }


    public function deleteextras(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $name = DB::table('extras')->where('id',$id)->get()->first()->name;

        if (Settings::isDemoMode()){
            Logging::log3("Extras Group->Extras delete", "Name: " . $name, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>'This is demo app. You can\'t change this section']);
        }
        Logging::log3("Extras Group->Extras delete", "Name: " . $name, "");

        DB::table('extras')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    public function editextras(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $name = $request->input('name') ?: "";
        Logging::log2("Extras Group->Extras Edit", "Name: " . $name);

        return ExtrasController::extrasMassive($request, 2);
    }

}
