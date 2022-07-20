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

class CategoriesController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log(Lang::get(491));  // Categories Screen

        return view('categories', []);
    }

    public function add(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id', "0"); // for edit

        $name = $request->input('name');
        Logging::log2("Categories->Add", "Name: " . $name);

        $visible = $request->input('visible');
        $text = $request->input('desc') ?: "";

        $values = array('name' => $name,
            'imageid' => $request->input('image') ?: 0,
            'desc' => $text,
            'visible' => $visible,
            'parent' => $request->input('parent'),
            'updated_at' => new \DateTime());

        if ($id != "0"){
            DB::table('categories')->where('id', $id)->update($values);
            Logging::log2("Categories->Update", "Name: " . $name);
        }else{
            $values['created_at'] = new \DateTime();
            DB::table('categories')->insert($values);
            $id = DB::getPdo()->lastInsertId();
            Logging::log2("Categories->Add", "Name: " . $name);
        }

        return CategoriesController::getOne($id);
    }




    public function categoryGetInfo(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1"]);

        $id = $request->input('id', "0");
        if ($id == "0")
            return response()->json(['error' => "4"]);

        return CategoriesController::getOne($id);
    }

    public function getOne($id){
        $category = DB::table('categories')->where("id", $id)->get()->first();
        $categories = DB::table('categories')->get();

        $filename = DB::table('image_uploads')->where("id", $category->imageid)->get()->first();
        if ($filename != null)
            $category->filename = $filename->filename;
        else
            $category->filename = "noimage.png";
        $category->timeago = Util::timeago($category->updated_at);
        $category->parentName = "";
        foreach ($categories as &$value)
            if ($category->parent == $value->id)
                $category->parentName = $value->name;

        return response()->json(['error'=>"0", 'data' => $category]);
    }

    public function delete(Request $request){

        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $name = DB::table('categories')->where('id',$id)->get()->first()->name;

        if (Settings::isDemoMode()){
            Logging::log3("Categories->Delete User", "Name: " . $name, Lang::get(487)); // "Abort! This is demo mode"
            return response()->json(['ret'=>false, 'text' => Lang::get(489)]); // This is demo app. You can't change this section
        }

        Logging::log2("Categories->Delete", "Name: " . $name);

        DB::table('categories')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    public function categoryGoPage(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error'=>"1"]);

        $search = $request->input('search') ? : "";
        $cat = $request->input('cat', "0");
        $page = $request->input('page') ? : 1;
        $count = $request->input('count', 10);
        $sortBy = $request->input('sortBy') ? : "id";
        $sortAscDesc = $request->input('sortAscDesc') ? : "asc";
        $sortPublished = $request->input('sortPublished', "1");
        $sortUnPublished = $request->input('sortUnPublished', "1");

        $user_id = $request->input('user_id') ?: "";
        $petani = DB::table('image_uploads')->get();

        Logging::log("Category->Go Page " . $page . " search: " . $search);

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

        $searchCat = "";
        if ($cat != "0")
            $searchCat = " parent=" . $cat . " AND ";

        $data = DB::select("SELECT * FROM categories WHERE " . $searchVisible . $searchCat . " name LIKE '%" . $search . "%' ORDER BY " . $sortBy . " " . $sortAscDesc . " LIMIT " . $count . " OFFSET " . $offset);
        $total = count(DB::select("SELECT * FROM categories WHERE " . $searchVisible . $searchCat . " name LIKE '%" . $search . "%'" ));

        $categoriesAll = DB::table('categories')->get();
        foreach ($data as &$category) {
            $filename = DB::table('image_uploads')->where("id", $category->imageid)->get()->first();
            if ($filename != null)
                $category->filename = $filename->filename;
            else
                $category->filename = "noimage.png";
            $category->timeago = Util::timeago($category->updated_at);
            $category->parentName = "";
            foreach ($categoriesAll as &$value)
                if ($category->parent == $value->id)
                    $category->parentName = $value->name;
            $category->itemsCount = count(DB::table('foods')->where("category", $category->id)->get());
        }

        $t = $total/$count;
        if ($total%$count > 0)
            $t++;

        return response()->json(['error'=>"0", 'idata' => $data, 'page' => $page, 'pages' => $t, 'total' => $total]);
    }
}
