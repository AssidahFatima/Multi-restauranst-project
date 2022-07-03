<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Logging;
use App\UserInfo;
use App\Lang;

class TopRestaurantsController extends Controller
{
    public function toprestaurants(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Restaurants -> Top Restaurants Screen");

        $toprestaurants = DB::table('toprestaurants')->join("restaurants", 'restaurants.id', '=', 'toprestaurants.restaurant')->
            join("image_uploads", 'image_uploads.id', '=', 'restaurants.imageid')->
            select('restaurants.id', 'restaurants.updated_at', 'restaurants.name', 'image_uploads.filename as image')->get();

        $rightSymbol = DB::table('settings')->where('param', '=', "rightSymbol")->get()->first()->value;
        $symbolDigits = DB::table('settings')->where('param', '=', "default_currencyCode")->
            join("currencies", 'currencies.code', '=', 'settings.value')->get()->first()->digits;
        $currency = DB::table('settings')->where('param', '=', "default_currencies")->get()->first()->value;

        $restaurants = DB::table('restaurants')->
            join("image_uploads", 'image_uploads.id', '=', 'restaurants.imageid')->
        select('restaurants.id', 'restaurants.name', 'restaurants.updated_at', 'image_uploads.filename as image')->get();

        return view('toprestaurants2', ['toprestaurants' => $toprestaurants, 'rightSymbol' => $rightSymbol, 'symbolDigits' => $symbolDigits,
            'restaurants' => $restaurants, 'icurrency' => $currency]);
    }

    public function topRestaurantsDelete(Request $request)
    {
        $id = $request->input('id');

        // logging and demo mode
        $name = DB::table('restaurants')->where("id", $id)->get()->first()->name;
        if (Settings::isDemoMode()){
            Logging::log3("Restaurants->Top Restaurants Delete. Name: ", $name, Lang::get(487));  // 'Abort! This is demo mode',
            return response()->json(['error'=>'1', 'text'=> Lang::get(489)]);  // 'This is demo app. You can\'t change this section',
        }
        Logging::log3("Restaurants->Top Restaurants Delete. Name: ", $name, "");
        // logging and demo mode

        // delete
        DB::table('toprestaurants')->where('restaurant',$id)->delete();

        return response()->json(['error'=>"0"]);
    }

    public function topRestaurantsAdd(Request $request)
    {
        $id = $request->input('id');

        $already = DB::table('toprestaurants')->where('restaurant', $id)->get()->first();
        if ($already != null)
            return response()->json(['ret'=>false, 'text'=> Lang::get(526)]); // "Restaurant aleady in list"

        // logging
        $name = DB::table('restaurants')->where("id", $id)->get()->first()->name;
        Logging::log3("Restaurants->Top Restaurants. Add Restaurant with name: ", $name, "");

        $values = array('restaurant' => $id,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime());
        DB::table('toprestaurants')->insert($values);

        $rightSymbol = DB::table('settings')->where('param', '=', "rightSymbol")->get()->first()->value;
        $symbolDigits = DB::table('settings')->where('param', '=', "default_currencyCode")->
        join("currencies", 'currencies.code', '=', 'settings.value')->get()->first()->digits;
        $currency = DB::table('settings')->where('param', '=', "default_currencies")->get()->first()->value;

        $toprestaurants = DB::table('toprestaurants')->join("restaurants", 'restaurants.id', '=', 'toprestaurants.restaurant')->
            join("image_uploads", 'image_uploads.id', '=', 'restaurants.imageid')->
            select('restaurants.id', 'restaurants.updated_at', 'restaurants.name', 'image_uploads.filename as image')->get();

        return response()->json(['ret'=>true, 'toprestaurants' => $toprestaurants, 'rightSymbol' => $rightSymbol, 'symbolDigits' => $symbolDigits,
            'currency' => $currency]);
    }
}
