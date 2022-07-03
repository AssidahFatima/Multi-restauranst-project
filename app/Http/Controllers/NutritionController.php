<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Logging;

class NutritionController extends Controller
{

    //
    // Nutrition group
    //

    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Nutritions Group Screen");

        return NutritionController::view("","", "");
    }

    function view($green, $text, $open){
        $nutritiongroup = DB::table('nutritiongroup')->get();
        $nutrition = DB::table('nutrition')->get();
        if ($open == "") {
            foreach ($nutritiongroup as &$value) {
                $open = $value->id;
                break;
            }
        }
        return view('nutrition', ['inutrition' => $nutrition,
            'inutritiongroup' => $nutritiongroup, 'texton' => $green, 'text' => $text, 'open'=>$open]);
    }


    public function add(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        NutritionController::dataMassive($request, 1);
        return NutritionController::view("green", "Nutrition Group create successfully", "");
    }

    function dataMassive(Request $request, $update){
        $id = $request->input('id');

        $name = $request->input('name') ?: "";

        $values = array('name' => $name,
            'updated_at' => new \DateTime());

        if ($update == 2){
            DB::table('nutritiongroup')
                ->where('id',$id)
                ->update($values);
            Logging::log2("Nutritions Group->Update", "Name:" . $name);
        }
        if ($update == 1) {
            $values['created_at'] = new \DateTime();
            DB::table('nutritiongroup')->insert($values);
            Logging::log2("Nutritions Group->Create", "Name:" . $name);
        }

    }

    public function delete(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $name = DB::table('nutritiongroup')->where('id',$id)->get()->first()->name;
        if (Settings::isDemoMode()){
            Logging::log3("Nutritions Group->Delete", $name, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>'This is demo app. You can\'t change this section']);
        }

        Logging::log3("Nutritions Group->Delete", $name, "");

        DB::table('nutritiongroup')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    public function edit(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        NutritionController::dataMassive($request, 2);
        return NutritionController::view("green", "Nutrition Group Updated successfully", $id);
    }

    //
    // Nutrition
    //
    public function addnutrition(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        return NutritionController::nutritionMassive($request, 1);
    }

    function nutritionMassive(Request $request, $update){
        $id = $request->input('id');

        $name = $request->input('name') ?: "";
        $desc = $request->input('desc') ?: "";
        $nutritiongroup = $request->input('nutritiongroup') ?: 0;

        $values = array('name' => $name,
            'desc' => $desc,
            'nutritiongroup' => $nutritiongroup,
            'created_at' => new \DateTime(), 'updated_at' => new \DateTime());

        if ($update == 2){
            DB::table('nutrition')
                ->where('id',$id)
                ->update($values);
            Logging::log2("Nutritions->Update", "Name:" . $name);
        }
        if ($update == 1) {
            DB::table('nutrition')->insert($values);
            $id = DB::getPdo()->lastInsertId();
            Logging::log2("Nutritions->Create", "Name:" . $name);
        }

        return response()->json(['ret'=>true, 'id'=>$id]);
    }

    public function deletenutrition(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $name = DB::table('nutrition')->where('id',$id)->get()->first()->name;
        if (Settings::isDemoMode()){
            Logging::log3("Nutritions ->Delete", $name, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>'This is demo app. You can\'t change this section']);
        }
        Logging::log3("Nutritions ->Delete", $name, "");

        DB::table('nutrition')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    public function editnutrition(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        return NutritionController::nutritionMassive($request, 2);
    }

}
