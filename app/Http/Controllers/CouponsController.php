<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Settings;
use App\Logging;
use App\UserInfo;

class CouponsController extends Controller
{
    public function coupons(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Coupons Screen");

        $coupons = DB::table('coupons')->get();
        $restaurants = DB::table('restaurants')->get();
        $categories = DB::table('categories')->get();
        $foods = DB::table('foods')->get();
        return view('coupons', ['coupons' => $coupons,
            'restaurants' => $restaurants,
            'categories' => $categories,
            'foods' => $foods,
            ]);
    }

    public function add(Request $request){
        if (!Auth::check())
            return response()->json(['ret'=>'401']);

        return CouponsController::dataMassive($request, 1);
    }

    function dataMassive(Request $request, $update){

        $dateStart = $request->input('dateStart') ?: "";
        $dateStart = \DateTime::createFromFormat('Y-m-d\TH:i', $dateStart);
        $dateEnd = $request->input('dateEnd') ?: "";
        $dateEnd = \DateTime::createFromFormat('Y-m-d\TH:i', $dateEnd);
        $now = new \DateTime();
        if ($dateStart >= $dateEnd)
            return response()->json(['ret'=> 5]);
        if ($dateEnd <= $now)
            return response()->json(['ret'=> 6]);

        $name = $request->input('name') ?: "";
        $discount = $request->input('discount') ?: 0;

        $published = $request->input('published');
        if ($published == "true") $published = 1; else $published = 0;

        $inpercents = $request->input('inpercents');
        if ($inpercents == "true") $inpercents = 1; else $inpercents = 0;

        $amount = $request->input('amount') ?: 0;
        $desc = $request->input('desc') ?: "";
        $allRestaurants = $request->input('allRestaurants') ?: true;
        if ($allRestaurants == "true") $allRestaurants = 1; else $allRestaurants = 0;
        $allCategory = $request->input('allCategoryGroup') ?: 0;
        if ($allCategory == "true") $allCategory = 1; else $allCategory = 0;
        $allFoods = $request->input('allFoodsGroup') ?: true;
        if ($allFoods == "true") $allFoods = 1; else $allFoods = 0;
        $restaurantsList = $request->input('restaurantsList') ?: "";
        $categoryList = $request->input('categoryList') ?: "";
        $foodsList = $request->input('foodsList') ?: "";

        $values = array('name' => $name,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'discount' => $discount,
            'published' => $published,
            'inpercents' => $inpercents,
            'amount' => $amount,
            'desc' => $desc,
            'allRestaurants' => $allRestaurants,
            'allCategory' => $allCategory,
            'allFoods' => $allFoods,
            'restaurantsList' => $restaurantsList,
            'categoryList' => $categoryList,
            'foodsList' => $foodsList,
            'updated_at' => new \DateTime());

        $edit = $request->input('edit');
        $editid = $request->input('editid');

        if ($edit == "true"){
            DB::table('coupons')
                ->where('id',$editid)
                ->update($values);
            Logging::log2("Coupons->Update", "Name:" . $name);
            $id = $editid;
        }else{
            $values['created_at'] = new \DateTime();
            DB::table('coupons')->insert($values);
            $id = DB::getPdo()->lastInsertId();
            Logging::log2("Coupons->Create", "Name:" . $name);
        }
        return response()->json(['ret'=> 0, 'id' => $id]);
    }

    public function delete(Request $request){

        if (!Auth::check())
            return response()->json(['ret'=> 401]);
        
        $id = $request->input('id');
        $name = DB::table('coupons')->where('id',$id)->get()->first();
        if ($name == null)
            return response()->json(['ret'=>402, 'text'=>'Item with id=' . $id . "not found"]);
        $name=$name->name;
        if (Settings::isDemoMode()){
            Logging::log3("Coupons->Delete", $name, "Abort! This is demo mode");
            return response()->json(['ret'=>402, 'text'=>'This is demo app. You can\'t change this section']);
        }
        Logging::log3("Coupons->Delete", $name, "");

        DB::table('coupons')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true, 'id' => $id]);
    }
    
    public function edit(Request $request){
        if (!Auth::check())
            return response()->json(['ret'=> 401]);

        $id = $request->input('id');
        $data = DB::table('coupons')->where('id',$id)->get()->first();
        if ($data != null){
            $data->dateStart = date('Y-m-d\TH:i', strtotime($data->dateStart));
            $data->dateEnd = date('Y-m-d\TH:i', strtotime($data->dateEnd));
            $data->ret = 0;
            return response()->json($data);
        }else
            response()->json(['ret'=>null]);
    }
}
