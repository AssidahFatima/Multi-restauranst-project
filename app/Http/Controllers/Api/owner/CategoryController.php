<?php
namespace App\Http\Controllers\API\owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;
use Auth;
use App\UserInfo;
use App\Settings;

class CategoryController extends Controller
{
    public function load()
    {
        return CategoryController::categoryRet("");
    }

    public function categoryRet($id)
    {
        $categories = [];
        if (UserInfo::getUserPermission("Food::Categories::View") == 1) {
            $categories = DB::table('categories')->get();
        }
        $images = DB::table('image_uploads')->select('id', 'filename')->get();

        return response()->json([
            'error' => '0',
            'id' => $id,
            'images' => $images,
            'category' => $categories,
        ], 200);
    }

    public function categorySave(Request $request)
    {
        $edit = $request->input('edit') ?: "0";
        $editId = $request->input('editId') ?: "0";

        if ($edit == "0") { // create new
            if (UserInfo::getUserPermission("Food::Categories::Create") == 0)
                return response()->json([
                    'error' => '5',
                ], 200);
        }else{
            if (UserInfo::getUserPermission("Food::Categories::Edit") == 0)
                return response()->json([
                    'error' => '5',
                ], 200);
        }

        $values = array(
            'name' => $request->input('name'),
            'desc' => $request->input('desc') ?: "",
            'imageid' => $request->input('imageid') ?: 0,
            'visible' => $request->input('visible'),
            'parent' => $request->input('parent'),
            'updated_at' => new \DateTime());

        $id = $editId;
        if ($edit == '1')
            DB::table('categories')->where('id',$editId)->update($values);
        else{
            $values['created_at'] = new \DateTime();
            DB::table('categories')->insert($values);
            $id = DB::getPdo()->lastInsertId();
        }

        return CategoryController::categoryRet($id);
    }

    public function categoryDelete(Request $request)
    {
        if (UserInfo::getUserPermission("Food::Categories::Delete") == 0)
            return response()->json([
                'error' => '5',
            ], 200);
        if (Settings::isDemoMode())
            return response()->json([
                'error' => '6',
            ], 200);

        $id = $request->input('id');
        DB::table('categories')->where('id',$id)->delete();
        return CategoryController::categoryRet("");
    }


}
