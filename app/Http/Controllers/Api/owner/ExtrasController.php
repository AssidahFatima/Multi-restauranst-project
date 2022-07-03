<?php
namespace App\Http\Controllers\API\owner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;
use Auth;

class ExtrasController extends Controller
{
    //
    // extras group
    //
    public function extrasGroupRet($id)
    {
        $extrasGroup = DB::table('extrasgroup')->get();
        $response = [
            'error' => '0',
            'id' => $id,
            'extrasGroup' => $extrasGroup,
        ];
        return response()->json($response, 200);
    }

    public function extrasGroupSave(Request $request)
    {
        $edit = $request->input('edit') ?: "0";
        $editId = $request->input('editId') ?: "0";

        $values = array(
            'name' => $request->input('name'),
            'restaurant' => $request->input('restaurant') ?: 0,
            'updated_at' => new \DateTime());

        $id = $editId;
        if ($edit == '1')
            DB::table('extrasgroup')->where('id',$editId)->update($values);
        else{
            $values['created_at'] = new \DateTime();
            DB::table('extrasgroup')->insert($values);
            $id = DB::getPdo()->lastInsertId();
        }
        return ExtrasController::extrasGroupRet($id);
    }

    public function extrasGroupDelete(Request $request)
    {
        $id = $request->input('id');
        DB::table('extrasgroup')->where('id',$id)->delete();
        return ExtrasController::extrasGroupRet("");
    }

    //
    // extras
    //
    public function extrasList()
    {
        $images = DB::table('image_uploads')->select('id', 'filename')->get();
        $extras = DB::table('extras')->get();
        $response = [
            'error' => '0',
            'id' => "",
            'extras' => $extras,
            'images' => $images,
        ];
        return response()->json($response, 200);
    }

    public function extrasRet($id)
    {
        $extras = DB::table('extras')->get();
        $images = DB::table('image_uploads')->select('id', 'filename')->get();
        $response = [
            'error' => '0',
            'id' => $id,
            'extras' => $extras,
            'images' => $images,
        ];
        return response()->json($response, 200);
    }

    public function extrasSave(Request $request)
    {
        $edit = $request->input('edit') ?: "0";
        $editId = $request->input('editId') ?: "0";

        $values = array(
            'name' => $request->input('name'),
            'imageid' => $request->input('imageid') ?: 0,
            'price' => $request->input('price') ?: 0,
            'desc' => $request->input('desc') ?: "",
            'extrasgroup' => $request->input('extrasgroup') ?: 0,
            'updated_at' => new \DateTime());

        $id = $editId;
        if ($edit == '1')
            DB::table('extras')->where('id',$editId)->update($values);
        else{
            $values['created_at'] = new \DateTime();
            DB::table('extras')->insert($values);
            $id = DB::getPdo()->lastInsertId();
        }
        return ExtrasController::extrasRet($id);
    }

    public function extrasDelete(Request $request)
    {
        $id = $request->input('id');
        DB::table('extras')->where('id',$id)->delete();
        return ExtrasController::extrasRet("");
    }

}
