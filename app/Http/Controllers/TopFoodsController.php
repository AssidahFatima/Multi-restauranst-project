<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Logging;
use App\Lang;
use App\UserInfo;

class TopFoodsController extends Controller
{
    public function topfoods(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Foods -> Top Foods Screen");

        $topfoods = DB::table('topfoods')->join("foods", 'foods.id', '=', 'topfoods.food')->
                leftjoin("image_uploads", 'image_uploads.id', '=', 'foods.imageid')->
                join("restaurants", 'restaurants.id', '=', 'foods.restaurant')->
                select('foods.id', 'foods.updated_at', 'foods.name', 'foods.price', 'foods.restaurant', 'restaurants.name as restaurantName', 'image_uploads.filename as image')->get();

        $rightSymbol = DB::table('settings')->where('param', '=', "rightSymbol")->get()->first()->value;
        $symbolDigits = DB::table('settings')->where('param', '=', "default_currencyCode")->
                join("currencies", 'currencies.code', '=', 'settings.value')->get()->first()->digits;
        $currency = DB::table('settings')->where('param', '=', "default_currencies")->get()->first()->value;

        $foods = DB::table('foods')->
                leftjoin("image_uploads", 'image_uploads.id', '=', 'foods.imageid')->
                join("restaurants", 'restaurants.id', '=', 'foods.restaurant')->
                select('foods.id', 'foods.name', 'foods.price', 'restaurants.name as restaurantName', 'image_uploads.filename as image')->get();

        foreach ($foods as &$value) {
            if ($value->image == "")
                $value->image = "noimage.png";
        }
        foreach ($topfoods as &$value) {
            if ($value->image == "")
                $value->image = "noimage.png";
        }

        return view('topfoods', ['topfoods' => $topfoods, 'rightSymbol' => $rightSymbol, 'symbolDigits' => $symbolDigits,
                        'foods' => $foods, 'icurrency' => $currency]);
    }

    public function topfooddelete(Request $request)
    {
        $id = $request->input('id');

        // logging and demo mode
        $name = DB::table('foods')->where("id", $id)->get()->first()->name;
        if (Settings::isDemoMode()){
            Logging::log3("Foods->Top Foods Delete", $name, Lang::get(487));  // 'Abort! This is demo mode',
            return response()->json(['error'=>"1", 'text' => Lang::get(489)]);  // 'This is demo app. You can\'t change this section',
        }
        Logging::log3("Foods->Top Foods Delete", $name, "");    // logging and demo mode

        // delete
        DB::table('topfoods')->where('food',$id)->delete();

        return response()->json(['error'=>"0"]);
    }

    public function topFoodsAdd(Request $request)
    {
        $id = $request->input('id');

        $already = DB::table('topfoods')->where('food', $id)->get()->first();
        if ($already != null)
            return response()->json(['ret'=>false, 'text'=> "Food aleady in list"]);

        // logging
        $name = DB::table('foods')->where("id", $id)->get()->first()->name;
        Logging::log3("Foods->Top Foods. Add Dish name: ", $name, "");

        $values = array('food' => $id,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime());
        DB::table('topfoods')->insert($values);

        $rightSymbol = DB::table('settings')->where('param', '=', "rightSymbol")->get()->first()->value;
        $symbolDigits = DB::table('settings')->where('param', '=', "default_currencyCode")->
            join("currencies", 'currencies.code', '=', 'settings.value')->get()->first()->digits;
        $currency = DB::table('settings')->where('param', '=', "default_currencies")->get()->first()->value;

        $topfoods = DB::table('topfoods')->join("foods", 'foods.id', '=', 'topfoods.food')->
            leftjoin("image_uploads", 'image_uploads.id', '=', 'foods.imageid')->
            join("restaurants", 'restaurants.id', '=', 'foods.restaurant')->
            select('foods.id', 'foods.updated_at', 'foods.name', 'foods.price', 'foods.restaurant', 'restaurants.name as restaurantName', 'image_uploads.filename as image')->get();

        foreach ($topfoods as &$value) {
            if ($value->image == "")
                $value->image = "noimage.png";
        }

        return response()->json(['ret'=>true, 'topfoods' => $topfoods, 'rightSymbol' => $rightSymbol, 'symbolDigits' => $symbolDigits,
            'currency' => $currency, 'already' => $already]);
    }
}
