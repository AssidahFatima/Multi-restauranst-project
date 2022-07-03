<?php
namespace App\Http\Controllers\API\owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;
use Auth;
use App\UserInfo;
use App\Settings;

class RestaurantsController extends Controller
{
    public function restaurantsList()
    {
        return RestaurantsController::restaurantsRet("");
    }

    public function restaurantsRet($id)
    {
        $restaurants = [];
        if (UserInfo::getUserPermission("Restaurants::View") == 1) {
            if (UserInfo::getUserRoleId() == 2) // manager
                $restaurants = DB::table('manager_rest')->where('user', '=', Auth::user()->id)->join("restaurants", 'restaurants.id', '=', 'manager_rest.restaurant')->get();
            else
                $restaurants = DB::table('restaurants')->get();
        }
        $images = DB::table('image_uploads')->select('id', 'filename')->get();

        return response()->json([
            'error' => '0',
            'id' => $id,
            'restaurants' => $restaurants,
            'images' => $images,
        ], 200);
    }

    public function restaurantsSave(Request $request)
    {
        $edit = $request->input('edit') ?: "0";
        $editId = $request->input('editId') ?: "0";

        if ($edit == "0") { // create new
            if (UserInfo::getUserPermission("Restaurants::Create") == 0)
                return response()->json([
                    'error' => '5',
                ], 200);
        }else{
            if (UserInfo::getUserPermission("Restaurants::Edit") == 0)
                return response()->json([
                    'error' => '5',
                ], 200);
        }

        $values = array('name' => $request->input('name'),
            'imageid' => $request->input('imageid') ?: 0,
            'desc' => $request->input('desc') ?: "",
            'published' => $request->input('published'),
            'phone' => $request->input('phone') ?: "",
            'mobilephone' => $request->input('mobilephone') ?: "",
            'address' => $request->input('address') ?: "",
            'lat' => $request->input('lat') ?: "",
            'lng' => $request->input('lng') ?: "",
            'fee' => $request->input('fee') ?: 0,
            'percent' => $request->input('percent'),
            'openTimeMonday' => $request->input('openTimeMonday') ?: "",
            'closeTimeMonday' => $request->input('closeTimeMonday') ?: "",
            'openTimeTuesday' => $request->input('openTimeTuesday') ?: "",
            'closeTimeTuesday' => $request->input('closeTimeTuesday') ?: "",
            'openTimeWednesday' => $request->input('openTimeWednesday') ?: "",
            'closeTimeWednesday' => $request->input('closeTimeWednesday') ?: "",
            'openTimeThursday' => $request->input('openTimeThursday') ?: "",
            'closeTimeThursday' => $request->input('closeTimeThursday') ?: "",
            'openTimeFriday' => $request->input('openTimeFriday') ?: "",
            'closeTimeFriday' => $request->input('closeTimeFriday') ?: "",
            'openTimeSaturday' => $request->input('openTimeSaturday') ?: "",
            'closeTimeSaturday' => $request->input('closeTimeSaturday') ?: "",
            'openTimeSunday' => $request->input('openTimeSunday') ?: "",
            'closeTimeSunday' => $request->input('closeTimeSunday') ?: "",
            'area' => $request->input('area') ?: 0,
            'delivered' => '1',
            'updated_at' => new \DateTime());

        $id = $editId;
        if ($edit == '1')
            DB::table('restaurants')->where('id',$editId)->update($values);
        else{
            $values['created_at'] = new \DateTime();
            DB::table('restaurants')->insert($values);
            $id = DB::getPdo()->lastInsertId();
        }
        return RestaurantsController::restaurantsRet($id);
    }

    public function restaurantsDelete(Request $request)
    {
        if (UserInfo::getUserPermission("Restaurants::Delete") == 0)
            return response()->json([
                'error' => '5',
            ], 200);
        if (Settings::isDemoMode())
            return response()->json([
                'error' => '6',
            ], 200);

        $id = $request->input('id');
        DB::table('restaurants')->where('id',$id)->delete();
        return RestaurantsController::restaurantsRet("");
    }

}
