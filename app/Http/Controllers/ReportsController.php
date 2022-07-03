<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Settings;
use App\Logging;
use App\UserInfo;

class ReportsController extends Controller
{
    public function mostpopular(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Most Popular Screen");

        $data = DB::table('favorites')->selectRaw('food, count(*) as result')->groupBy('food')->orderBy('result', 'desc')->limit(30)->get();
        foreach ($data as &$value) {
            $food = DB::table('foods')->where('id', '=', $value->food)->get()->first();
            if ($food != null) {
                $t = DB::table('image_uploads')->where('id', '=', $food->imageid)->get()->first();
                if ($t != null)
                    $value->image = $t->filename;
                else
                    $value->image = "noimage.png";
                $value->name = $food->name;
                $t = DB::table('restaurants')->where('id', '=', $food->restaurant)->get()->first();
                if ($t != null)
                    $value->restaurantname = $t->name;
                else
                    $value->restaurantname = "Deleted";
            }else {
                $value->name = "Deleted";
                $value->image = "Unknown";
                $value->restaurantname = "Unknown";
            }
        }

        $data2 = DB::table('favorites')->orderBy('updated_at', 'desc')->limit(30)->get();
        foreach ($data2 as &$value) {
            $food = DB::table('foods')->where('id', '=', $value->food)->get()->first();
            if ($food != null) {
                $t = DB::table('image_uploads')->where('id', '=', $food->imageid)->get()->first();
                if ($t != null)
                    $value->image = $t->filename;
                else
                    $value->image = "noimage.png";
                $value->name = $food->name;
                $t = DB::table('restaurants')->where('id', '=', $food->restaurant)->get()->first();
                if ($t != null)
                    $value->restaurantname = $t->name;
                else
                    $value->restaurantname = "Deleted";
                $t = DB::table('users')->where('id', '=', $value->user)->get()->first();
                if ($t != null)
                    $value->customername = $t->name;
                else
                    $value->customername = "Deleted";
            }else{
                $value->name = "Deleted";
                $value->image = "noimage.png";
                $value->restaurantname = "Unknown";
                $value->customername = "Unknown";
            }
        }

        return view('mostpopular', ['idata' => $data, 'data2' => $data2,]);
    }

    public function mostpurchase(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Most Purchase Foods Screen");

        $data = DB::table('ordersdetails')->selectRaw('foodid, count(*) as result')->groupBy('foodid')->orderBy('result', 'desc')->limit(30)->get();
        foreach ($data as &$value) {
            $food = DB::table('foods')->where('id', '=', $value->foodid)->get()->first();
            if ($food != null) {
                $t = DB::table('image_uploads')->where('id', '=', $food->imageid)->get()->first();
                if ($t != null)
                    $value->image = $t->filename;
                else
                    $value->image = "noimage.png";
                $value->name = $food->name;
                $t = DB::table('restaurants')->where('id', '=', $food->restaurant)->get()->first();
                if ($t != null)
                    $value->restaurantname = $t->name;
                else
                    $value->restaurantname = "Deleted";
            }else{
                $value->name = "Deleted";
                $value->image = "noimage.png";
                $value->restaurantname = "Unknown";
            }
        }

        return view('mostpurchase', ['idata' => $data]);
    }

    public function toprestaurants(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Most Popular Restaurants");

        $data = DB::table('orders')->selectRaw('restaurant, count(*) as result')->groupBy('restaurant')->orderBy('result', 'desc')->limit(30)->get();
        foreach ($data as &$value) {
            $restaurant = DB::table('restaurants')->where('id', '=', $value->restaurant)->get()->first();
            if ($restaurant != null) {
                $value->name = $restaurant->name;
                $t = DB::table('image_uploads')->where('id', '=', $restaurant->imageid)->get()->first();
                if ($t != null)
                    $value->image = $t->filename;
                else
                    $value->image = "noimage.png";
            }else{
                $value->name = "Deleted";
                $value->image = "noimage.png";
            }
        }

        return view('toprestaurants', ['idata' => $data]);
    }
}
