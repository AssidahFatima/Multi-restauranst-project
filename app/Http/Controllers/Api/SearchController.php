<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;
use App\Foods;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $petani = DB::table('image_uploads')->get();
        $restaurants = DB::table('restaurants')->get();
        $fav = [];
        $search = $request->input('search');
        Logging::logapi("Search. Word=" . $search);

        $fcl = new Foods();
        $foods = DB::table('foods')->where('name', 'LIKE', '%'.$search.'%')->get();
        foreach ($foods as &$value2) {
            $value2 = $fcl->fill2($value2, $restaurants);
            if ($value2 != null)
                $fav[] = $value2;
        }

        $currencies = DB::table('settings')->where('param', '=', "default_currencies")->get()->first()->value;
        $tax = DB::table('settings')->where('param', '=', "default_tax")->get()->first()->value;

        $response = [
            'error' => '0',
            'foods' => $fav,
            'search' => $search,
            'default_tax' => $tax,
            'currency' => $currencies,
        ];
        return response()->json($response, 200);
    }

}
