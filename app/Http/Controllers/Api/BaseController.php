<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;

class BaseController extends Controller
{
    public function getFaq(Request $request)
    {
        Logging::logapi("faq");

        $faq = DB::table('faq')->where('published', "=", '1')->get();
        $response = [
            'success' => true,
            'data'=> $faq,
        ];
        return response()->json($response, 200);
    }

}
