<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Logging;
use App\UserInfo;

class RestaurantsController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Restaurants Screen");

        $text = $request->input('text');
        if ($text != null)
            return RestaurantsController::view("green", $text);

        return RestaurantsController::view("", "");
    }

    public function view($green, $text){
        $petani = DB::table('image_uploads')->get();
        $restaurants = DB::table('restaurants')->leftjoin("image_uploads", 'image_uploads.id', '=', 'restaurants.imageid')->
                select('restaurants.*', 'image_uploads.filename as filename')->get();
        $currency = DB::table('settings')->where('param', '=', "default_currencies")->get()->first()->value;

        if (UserInfo::getUserRoleId() == 2) // manager
            $restaurants = DB::table('manager_rest')->where('user', '=', Auth::user()->id)->join("restaurants", 'restaurants.id', '=', 'manager_rest.restaurant')->
                    leftjoin("image_uploads", 'image_uploads.id', '=', 'restaurants.imageid')->select('restaurants.*', 'image_uploads.filename as filename')->get();

        foreach ($restaurants as &$value7) {
            if ($value7->filename == "")
                $value7->filename = "noimage.png";
            $value7->productsCount = count(DB::select("SELECT * FROM foods WHERE restaurant=$value7->id"));
        }

        return view('restaurants', ['petani' => $petani,
            'city' => DB::table('settings')->where('param',"city")->get()->first()->value,
            'restaurants' => $restaurants, 'texton' => $green, 'text' => $text, 'currency' => $currency]);
    }

    public function add(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        RestaurantsController::dataMassive($request, 1);

        return RestaurantsController::view("green", "Restaurant Saved successfully");
    }

    public function delete(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $name = DB::table('restaurants')->where('id',$id)->get()->first()->name;
        if (Settings::isDemoMode()){
            Logging::log3("Restaurants->Delete", $name, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>'This is demo app. You can\'t change this section']);
        }
        Logging::log3("Restaurants->Delete", $name, "");

        DB::table('restaurants')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    function dataMassive(Request $request, $update){
        $id = $request->input('id');
        $name = $request->input('name') ?: "";
        $visible = $request->input('visible');
        if ($visible == 'on') $visible = true;  else $visible = false;
        $delivered = $request->input('delivered');
        if ($delivered == 'on') $delivered = true; else $delivered = false;
        $text = $request->input('desc') ?: "";
        $text = preg_replace('/[\r\n\t]/', '', $text);
        $image = $request->input('image') ?: 0;
        $address = $request->input('address') ?: "";
        $phone = $request->input('phone') ?: "";
        $mobilephone = $request->input('mobilephone') ?: "";
        $lat = $request->input('lat') ?: "";
        $lng = $request->input('lng') ?: "";
        $fee = $request->input('fee') ?: 0.0;
        $percent = $request->input('percent');
        if ($percent == 'on') $percent = true;  else $percent = false;
        $perkm = $request->input('perkm');
        if ($perkm == 'on') $perkm = true;  else $perkm = false;
        // opened time
        $openTimeMonday = $request->input('openTimeMonday') ?: "";
        $closeTimeMonday = $request->input('closeTimeMonday') ?: "";
        $openTimeTuesday = $request->input('openTimeTuesday') ?: "";
        $closeTimeTuesday = $request->input('closeTimeTuesday') ?: "";
        $openTimeWednesday = $request->input('openTimeWednesday') ?: "";
        $closeTimeWednesday = $request->input('closeTimeWednesday') ?: "";
        $openTimeThursday = $request->input('openTimeThursday') ?: "";
        $closeTimeThursday = $request->input('closeTimeThursday') ?: "";
        $openTimeFriday = $request->input('openTimeFriday') ?: "";
        $closeTimeFriday = $request->input('closeTimeFriday') ?: "";
        $openTimeSaturday = $request->input('openTimeSaturday') ?: "";
        $closeTimeSaturday = $request->input('closeTimeSaturday') ?: "";
        $openTimeSunday = $request->input('openTimeSunday') ?: "";
        $closeTimeSunday = $request->input('closeTimeSunday') ?: "";
        //
        $area = $request->input('area') ?: 30;
        $minAmount = $request->input('minAmount') ?: 0;
        //
        $values = array('name' => $name, 'imageid' => $image, 'desc' => "$text", 'published' => $visible,
            'phone' => $phone, 'mobilephone' => $mobilephone, 'address' => $address, 'lat' => $lat, 'lng' => $lng,
            'fee' => $fee, 'percent' => $percent,
            'openTimeMonday' => $openTimeMonday, 'closeTimeMonday' => $closeTimeMonday,
            'openTimeTuesday' => $openTimeTuesday, 'closeTimeTuesday' => $closeTimeTuesday,
            'openTimeWednesday' => $openTimeWednesday, 'closeTimeWednesday' => $closeTimeWednesday,
            'openTimeThursday' => $openTimeThursday, 'closeTimeThursday' => $closeTimeThursday,
            'openTimeFriday' => $openTimeFriday, 'closeTimeFriday' => $closeTimeFriday,
            'openTimeSaturday' => $openTimeSaturday, 'closeTimeSaturday' => $closeTimeSaturday,
            'openTimeSunday' => $openTimeSunday, 'closeTimeSunday' => $closeTimeSunday,
            'area' => $area, 'minAmount' => $minAmount,
            'perkm' => $perkm,
            'city' => $request->input('cityData') ?: "",
            'delivered' => $delivered, 'updated_at' => new \DateTime());

        if ($update == 2){
            DB::table('restaurants')
                ->where('id',$id)
                ->update($values);
            Logging::log2("Restaurants->Update", "Name:" . $name);
        }
        if ($update == 1){
            $values['created_at'] = new \DateTime();
            DB::table('restaurants')->insert($values);
            $id = DB::getPdo()->lastInsertId();
            Logging::log2("Restaurants->Create", "Name:" . $name);
            //
            $values = array('user' => Auth::user()->id, 'restaurant' => $id,
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime());
            DB::table('manager_rest')->insert($values);
        }
    }

    public function edit(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        RestaurantsController::dataMassive($request, 2);

        $petani = DB::table('image_uploads')->get();
        $categories = DB::table('restaurants')->get();
        $str = "Restaurant Updated successfully";
        return \Redirect::to('/restaurants?text=' . $str);
        //return view('restaurants', ['petani' => $petani, 'icategories' => $categories, 'texton' => "green", 'text' => $str]);
    }

    //
    // Reviews
    //
    public function loadReviews(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Restaurants Reviews Screen");

        return RestaurantsController::viewRestarantsReviews("", "");
    }

    function viewRestarantsReviews($green, $text){
        $users = DB::table('users')->get();
        $data = DB::table('restaurantsreviews')->get();
        $restaurants = DB::table('restaurants')->get();
        if (UserInfo::getUserRoleId() == 2) // manager
            $restaurants = DB::table('manager_rest')->where('user', '=', Auth::user()->id)->join("restaurants", 'restaurants.id', '=', 'manager_rest.restaurant')->get();
        if (UserInfo::getUserRoleId() == 2) // manager
            $data = DB::table('manager_rest')->where('manager_rest.user', '=', Auth::user()->id)->join("restaurantsreviews", 'restaurantsreviews.restaurant', '=', 'manager_rest.restaurant')->get();
        return view('restaurantsreviews', ['idata' => $data, 'iusers' => $users, 'irestaurants' => $restaurants, 'texton' => $green, 'text' => $text]);
    }

    public function addReview(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        RestaurantsController::dataMassiveReview($request, 1);
        return RestaurantsController::viewRestarantsReviews("green","Review create successfully");
    }


    function dataMassiveReview(Request $request, $update){
        $id = $request->input('id');

        $values = array(
            'desc' => $request->input('review') ?: "",
            'user' => $request->input('user') ?: "",
            'rate' => $request->input('rate') ?: "",
            'restaurant' => $request->input('restaurant') ?: "1",
            'updated_at' => new \DateTime());

        if ($update == 2){
            DB::table('restaurantsreviews')->where('id',$id)->update($values);
            Logging::log("Restaurant Review->Update" );
        }
        if ($update == 1){
            $values['created_at'] = new \DateTime();
            DB::table('restaurantsreviews')->insert($values);
            Logging::log("Restaurant Review->Create");
        }
    }

    public function deleteReview(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $nameid = DB::table('restaurantsreviews')->where('id',$id)->get()->first()->user;
        $name = DB::table('users')->where('id',$nameid)->get()->first()->name;
        $restaurantid = DB::table('restaurantsreviews')->where('id',$id)->get()->first()->restaurant;
        $restName = DB::table('restaurants')->where('id',$restaurantid)->get()->first();
        if ($restName != null)
            $restaurantname = $restName->name;
        else
            $restaurantname = "";

        if (Settings::isDemoMode()){
            Logging::log3("Restaurant Review->Delete", "Name:" . $name . " Restaurant: " . $restaurantname, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>'This is demo app. You can\'t change this section']);
        }
        Logging::log3("Restaurant Review->Delete", "Name:" . $name . " Restaurant: " . $restaurantname, "");

        DB::table('restaurantsreviews')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    public function editReview(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        RestaurantsController::dataMassiveReview($request, 2);
        return RestaurantsController::viewRestarantsReviews("green", "Review Updated successfully");
    }


}
