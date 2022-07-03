<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;
use App\Foods;

class CategoryController extends Controller
{
    public function get(Request $request)
    {
        $petani = DB::table('image_uploads')->get();
        $restaurants = DB::table('restaurants')->get();
        $fav = [];
        $category = $request->input('category');
        Logging::logapi("category. id=".$category);

        $fcl = new Foods();

        $foods = DB::table('foods')->where('category', '=', $category)->get();
        foreach ($foods as &$value2) {
            $value2 = $fcl->fill2($value2, $restaurants);
            if ($value2 != null)
                $fav[] = $value2;
//            $value2->image = "noimage.png";
//            foreach ($petani as &$value3) {
//                if ($value2->imageid == $value3->id)
//                    $value2->image = $value3->filename;
//            }
//            $restaurantYes = false;
//            foreach ($restaurants as &$value4) {
//                if ($value2->restaurant == $value4->id) {
//                    if ($value4->published == '1') {
//                        $value2->restaurantName = $value4->name;
//                        $value2->restaurantPhone = $value4->phone;
//                        $value2->restaurantMobilePhone = $value4->mobilephone;
//                        $restaurantYes = true;
//                    }
//                }
//            }
//            // nutritions
//            $n = DB::table('nutrition')->where('nutritiongroup',$value2->nutritions)->get();
//            $value2->nutritionsdata = $n;
//            // extras
//            $e = DB::table('extras')->where('extrasgroup',$value2->extras)->get();
//            foreach ($e as &$value5) {
//                $value5->image = "noimage.png";
//                foreach ($petani as &$value6) {
//                    if ($value5->imageid == $value6->id)
//                        $value5->image = $value6->filename;
//                }
//            }
//            $value2->extrasdata = $e;
//            // reviews
//            $d = DB::table('foodsreviews')->where('food',$value2->id)->get();
//            foreach ($d as &$value6) {
//                $user = DB::table('users')->where('id',$value6->user)->get()->first();
//                if ($user != null) {
//                    $value6->userName = $user->name;
//                    foreach ($petani as &$value7) {
//                        if ($user->imageid == $value7->id)
//                            $value6->image = $value7->filename;
//                    }
//                }
//            }
//            $value2->foodsreviews = $d;
//            // save
//            if ($restaurantYes)
//                $fav[] = $value2;
        }

        $currencies = DB::table('settings')->where('param', '=', "default_currencies")->get()->first()->value;
        $tax = DB::table('settings')->where('param', '=', "default_tax")->get()->first()->value;
        $categories = DB::table('categories')->where('id', '=', $category)->get()->first();

        $catimage = "noimage.png";
        foreach ($petani as &$value3) {
            if ($categories->imageid == $value3->id)
                $catimage = $value3->filename;
        }

        $response = [
            'error' => '0',
            'desc' => $categories->desc,
            'name' => $categories->name,
            'image' => $catimage,
            'foods' => $fav,
            'default_tax' => $tax,
            'currency' => $currencies,
        ];
        return response()->json($response, 200);
    }

}
