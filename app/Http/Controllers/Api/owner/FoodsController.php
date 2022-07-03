<?php
namespace App\Http\Controllers\API\owner;

use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;
use Auth;
use App\UserInfo;
use App\Settings;

class FoodsController extends Controller
{
    public function load()
    {
        return FoodsController::foodsRet("");
    }

    public function foodsRet($id)
    {
        $restaurants = [];
        if (UserInfo::getUserPermission("Food::Food::View") == 1) {
            if (UserInfo::getUserRoleId() == 2) { // manager
                $foods = DB::table('manager_rest')->where('user', '=', Auth::user()->id)->join("foods", 'foods.restaurant', '=', 'manager_rest.restaurant')->get();
                $restaurants = DB::table('manager_rest')->where('user', '=', Auth::user()->id)->join("restaurants", 'restaurants.id', '=', 'manager_rest.restaurant')->get();
            }else{
                $foods = DB::table('foods')->get();
                $restaurants = DB::table('restaurants')->select('id', 'name', 'published')->get();
            }
        }
        foreach ($foods as &$value)
            $value->variants = DB::select("SELECT * FROM variants WHERE food=$value->id ORDER BY variants.price ASC");

        $images = DB::table('image_uploads')->select('id', 'filename')->get();
        $extrasGroup = DB::table('extrasgroup')->select('id', 'name', 'restaurant')->get();
        $nutritionGroup = DB::table('nutritiongroup')->get();
        $temp = DB::table('settings')->where('param', '=', "default_currencyCode")->get()->first()->value;
        $symbolDigits = DB::table('currencies')->where('code', '=', $temp)->get()->first()->digits;
        $response = [
            'error' => '0',
            'id' => $id,
            'images' => $images,
            'foods' => $foods,
            'restaurants' => $restaurants,
            'extrasGroup' => $extrasGroup,
            'nutritionGroup' => $nutritionGroup,
            'numberOfDigits' => $symbolDigits,
        ];
        return response()->json($response, 200);
    }

    public function foodSave(Request $request)
    {
        $edit = $request->input('edit') ?: "0";
        $editId = $request->input('editId') ?: "0";

        if ($edit == "0") { // create new
            if (UserInfo::getUserPermission("Food::Food::Create") == 0)
                return response()->json([
                    'error' => '5',
                ], 200);
        }else{
            if (UserInfo::getUserPermission("Food::Food::Edit") == 0)
                return response()->json([
                    'error' => '5',
                ], 200);
        }

        $values = array(
            'name' => $request->input('name'),
            'imageid' => $request->input('imageid') ?: 0,
            'price' => $request->input('price'),
            'discountprice' => 0,
            'desc' => $request->input('desc') ?: "",
            'restaurant' => $request->input('restaurant') ?: 0,
            'category' => $request->input('category') ?: 0,
            'ingredients' => $request->input('ingredients') ?: "",
            'unit' => "",
            'packageCount' => 0,
            'weight' => 0,
            'canDelivery' => 1,
            'published' => $request->input('published'),
            'stars' => 5,
            'extras' => $request->input('extras') ?: 0,
            'nutritions' => $request->input('nutritions') ?: 0,
            'updated_at' => new \DateTime());

        $id = $editId;
        if ($edit == '1')
            DB::table('foods')->where('id',$editId)->update($values);
        else{
            $values['created_at'] = new \DateTime();
            DB::table('foods')->insert($values);
            $id = DB::getPdo()->lastInsertId();

            $cacheVariant = $request->input('cacheVariant');
            if ($cacheVariant != null) {
                foreach ($cacheVariant as &$value) {
                    ProductsController::productVariantsAdd2($id, $value['name'], $value['price'],
                        $value['dprice'] ?: 0, $value['imageid']);
                }
            }
        }

        return FoodsController::foodsRet($id);
    }

    public function foodDelete(Request $request)
    {
        if (UserInfo::getUserPermission("Food::Food::Delete") == 0)
            return response()->json([
                'error' => '5',
            ], 200);
        if (Settings::isDemoMode())
            return response()->json([
                'error' => '6',
            ], 200);

        $id = $request->input('id');
        DB::table('foods')->where('id',$id)->delete();
        return FoodsController::foodsRet("");
    }


}
