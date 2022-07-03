<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;
use App\Foods;

class FavoritesController extends Controller
{
    public function add(Request $request)
    {
        $id = auth('api')->user()->id;
        $food = $request->input('food');

        $foodname = DB::table('foods')->where('id', '=', "$food")->get()->first()->name;
        Logging::logapi("Favorites->Add. food name=".$foodname);

        $fav = DB::table('favorites')->where('user', '=', "$id")->where('food', '=', "$food")->get()->first();
        if ($fav == null) {
            $values = array('user' => $id, 'food' => $food,
                'updated_at' => new \DateTime());
            $values['created_at'] = new \DateTime();
            DB::table('favorites')->insert($values);
        }else{
            $values = array('user' => $id, 'food' => $food,
                'updated_at' => new \DateTime());
            DB::table('favorites')
                ->where('user',$id)->where('food',$food)
                ->update($values);
        }

        $response = [
            'error' => '0',
        ];
        return response()->json($response, 200);
    }

    public function delete(Request $request)
    {
        $id = auth('api')->user()->id;
        $food = $request->input('food');

        $foodname = DB::table('foods')->where('id', '=', "$food")->get()->first()->name;
        Logging::logapi("Favorites->Remove. food name=".$foodname);

        DB::table('favorites')
            ->where('user', '=', "$id")->where('food', '=', "$food")
            ->delete();

        $response = [
            'error' => '0',
        ];
        return response()->json($response, 200);
    }

    public function get(Request $request)
    {
        Logging::logapi("Favorites->Get");

        $id = auth('api')->user()->id;
        $petani = DB::table('image_uploads')->get();

        $fav = DB::table('favorites')->where('user', '=', "$id")->get();

        $fcl = new Foods();

        // most popular
        $food = [];
        $restaurants = DB::table('restaurants')->get();
        $foods = DB::table('foods')->get();
        foreach ($fav as &$value) {
            foreach ($foods as &$value2) {
                if ($value->food == $value2->id) {
                    $value2 = $fcl->fill2($value2, $restaurants);
                    if ($value2 != null)
                        $food[] = $value2;
//                    $value2->image = "noimage.png";
//                    foreach ($petani as &$value3) {
//                        if ($value2->imageid == $value3->id)
//                            $value2->image = $value3->filename;
//                    }
//                    $restaurantYes = false;
//                    foreach ($restaurants as &$value4) {
//                        if ($value2->restaurant == $value4->id) {
//                            $value2->restaurantName = $value4->name;
//                            $value2->restaurantPhone = $value4->phone;
//                            $value2->restaurantMobilePhone = $value4->mobilephone;
//                            $restaurantYes = true;
//                        }
//                    }
//                    // nutritions
//                    $n = DB::table('nutrition')->where('nutritiongroup',$value2->nutritions)->get();
//                    $value2->nutritionsdata = $n;
//                    // extras
//                    $e = DB::table('extras')->where('extrasgroup',$value2->extras)->get();
//                    foreach ($e as &$value5) {
//                        foreach ($petani as &$value6) {
//                            if ($value5->imageid == $value6->id)
//                                $value5->image = $value6->filename;
//                        }
//                    }
//                    $value2->extrasdata = $e;
//                    // reviews
//                    $d = DB::table('foodsreviews')->where('food',$value2->id)->get();
//                    foreach ($d as &$value6) {
//                        $user = DB::table('users')->where('id',$value6->user)->get()->first();
//                        if ($user != null) {
//                            $value6->userName = $user->name;
//                            foreach ($petani as &$value7) {
//                                if ($user->imageid == $value7->id)
//                                    $value6->image = $value7->filename;
//                            }
//                        }
//                    }
//                    $value2->foodsreviews = $d;
//                    // save
//                    if ($restaurantYes)
//                         $food[] = $value2;
                }
            }
        }

        $currencies = DB::table('settings')->where('param', '=', "default_currencies")->get()->first()->value;

        $response = [
            'error' => '0',
            'favorites' => $fav,
            'food' => $food,
            'currency' => $currencies,
        ];
        return response()->json($response, 200);
    }
}
