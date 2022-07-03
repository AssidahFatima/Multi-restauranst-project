<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Settings;
use App\Logging;
use App\Util;
use App\Lang;

class BannersController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log(Lang::get(506));  // Banners Screen

        return view('banners', []);
    }

    public function add(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id', "0"); // for edit

        $name = $request->input('name');
        Logging::log2("Banners->Add", "Name: " . $name);

        $values = array(
            'name' => $name,
            'imageid' => $request->input('image') ?: 0,
            'type' => $request->input('type'),
            'visible' => $request->input('visible'),
            'details' => $request->input('details'),
            'position' => $request->input('position'),
            'updated_at' => new \DateTime());

        if ($id != "0"){
            DB::table('banners')->where('id', $id)->update($values);
            Logging::log2("Banners->Update", "Name: " . $name);
        }else{
            $values['created_at'] = new \DateTime();
            DB::table('banners')->insert($values);
            $id = DB::getPdo()->lastInsertId();
            Logging::log2("Banners->Add", "Name: " . $name);
        }

        return BannersController::getOne($id);
    }

    public function GetInfo(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1"]);

        $id = $request->input('id', "0");
        if ($id == "0")
            return response()->json(['error' => "4"]);

        return BannersController::getOne($id);
    }

    public function getOne($id){
        $banner = DB::table('banners')->where("id", $id)->get()->first();

        $filename = DB::table('image_uploads')->where("id", $banner->imageid)->get()->first();
        if ($filename != null)
            $banner->filename = $filename->filename;
        else
            $banner->filename = "noimage.png";
        $banner->timeago = Util::timeago($banner->updated_at);
        if ($banner->type == "1") { // food
            $food = DB::table('foods')->where("id", $banner->details)->get()->first();
            if ($food != null) {
                $banner->food = $food->name;
                $banner->detailsText = $food->name;
            }
        } else
            $banner->detailsText = $banner->details;
        return response()->json(['error'=>"0", 'data' => $banner]);
    }

    public function delete(Request $request){

        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $name = DB::table('banners')->where('id',$id)->get()->first()->name;

        if (Settings::isDemoMode()){
            Logging::log3("Banners->Delete", "Name: " . $name, Lang::get(487)); // "Abort! This is demo mode"
            return response()->json(['ret'=>false, 'text' => Lang::get(489)]); // This is demo app. You can't change this section
        }

        Logging::log2("Categories->Delete", "Name: " . $name);

        DB::table('banners')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    public function GoPage(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error'=>"1"]);

        $search = $request->input('search') ? : "";
        $page = $request->input('page') ? : 1;
        $count = $request->input('count', 10);
        $sortBy = $request->input('sortBy') ? : "id";
        $sortAscDesc = $request->input('sortAscDesc') ? : "asc";
        $sortPublished = $request->input('sortPublished', "1");
        $sortUnPublished = $request->input('sortUnPublished', "1");

        $user_id = $request->input('user_id') ?: "";
        $petani = DB::table('image_uploads')->get();

        Logging::log("Banners->Go Page " . $page . " search: " . $search);

        $offset = ($page - 1) * $count;

        $searchVisible = "";
        if ($sortPublished != '1' || $sortUnPublished != '1') {
            if ($sortPublished == '1')
                $searchVisible = "visible = '1' AND ";
            if ($sortUnPublished == '1')
                $searchVisible = "visible = '0' AND ";
        }
        if ($sortPublished == '0' && $sortUnPublished == '0')
            $searchVisible = "visible='3' AND ";


        $datas = DB::select("SELECT * FROM banners WHERE " . $searchVisible .  " name LIKE '%" . $search . "%' ORDER BY " . $sortBy . " " . $sortAscDesc . " LIMIT " . $count . " OFFSET " . $offset);
        $total = count(DB::select("SELECT * FROM banners WHERE " . $searchVisible .  " name LIKE '%" . $search . "%'" ));

        foreach ($datas as &$data) {
            $filename = DB::table('image_uploads')->where("id", $data->imageid)->get()->first();
            if ($filename != null)
                $data->filename = $filename->filename;
            else
                $data->filename = "noimage.png";
            if ($data->type == "1") { // food
                $food = DB::table('foods')->where("id", $data->details)->get()->first();
                if ($food != null) {
                    $data->food = $food->name;
                    $data->detailsText = $food->name;
                }
            } else
                $data->detailsText = $data->details;
            $data->timeago = Util::timeago($data->updated_at);
        }

        $t = $total/$count;
        if ($total%$count > 0)
            $t++;

        return response()->json(['error'=>"0", 'idata' => $datas, 'page' => $page, 'pages' => $t, 'total' => $total]);
    }
}
