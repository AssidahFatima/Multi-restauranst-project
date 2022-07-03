<?php
namespace App\Http\Controllers\API\owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;
use Auth;
use App\ImageUpload;
use App\Http\Controllers\MessagingController;
use App\Lang;

class ChatController extends Controller
{
    public function chatUsers(Request $request)
    {
        $count = 0;
        $users = DB::table('users')->where("role", '>', "2")->
                leftjoin("image_uploads", 'image_uploads.id', '=', 'users.imageid')->
                select('users.id', 'users.name', 'image_uploads.filename as image')->get();
        $all = DB::table('chat')->where('author', '=', "customer")->where('read', '=', "true")->selectRaw('user, count(*) as result')->groupBy('user')->get();
        $unread = DB::table('chat')->where('author', '=', "customer")->where('read', '=', "false")->selectRaw('user, count(*) as result')->groupBy('user')->get();
        foreach ($users as &$value){
            $count += count(DB::table('chat')->where('read', '=', "false")->where('author', '=', "customer")->where("user", $value->id)->get());
            $value->count = 0;
            foreach ($all as &$value2)
                if ($value2->user == $value->id)
                    $value->count = $value2->result;
            $value->unread = 0;
            foreach ($unread as &$value2)
                if ($value2->user == $value->id)
                    $value->unread = $value2->result;

            if ($value->image == null)
                $value->image = "noimage.png";
        }
        return response()->json([
            'error' => '0',
            'users' => $users,
            'unread' => $count,
        ]);
    }

    public function getChatUnread(Request $request)
    {
        $users = DB::table('users')->where("role", '>', "2")->select('users.id')->get();
        $count = 0;
        foreach ($users as &$value)
            $count += count(DB::table('chat')->where('read', '=', "false")->where('author', '=', "customer")->where("user", $value->id)->get());
        return response()->json([
            'error' => '0',
            'unread' => $count,
        ]);
    }

    public static function chatMessages(Request $request)
    {
        $id = $request->input('id');

        $msg = DB::table('chat')->where('user', '=', "$id")->orderBy('created_at', 'asc')->get();

        $values = array('read' => 'true', 'updated_at' => new \DateTime());
        DB::table('chat')
            ->where('user', '=', "$id")
            ->where('author', '=', "customer")
            ->update($values);

        $users = DB::table('users')->where("role", '>', "2")->select('users.id')->get();
        $count = 0;
        foreach ($users as &$value)
            $count += count(DB::table('chat')->where('read', '=', "false")->where('author', '=', "customer")->where("user", $value->id)->get());

        $response = [
            'error' => "0",
            'messages' => $msg,
            'unread' => $count,
        ];
        return response()->json($response, 200);
    }

    public function chatMessageSend(Request $request)
    {
        $user_id = $request->input('id');
        $text = $request->input('text');

        $values = array(
            'user' => "$user_id", 'text' => "$text", 'author' => "manager",
            'delivered' => "false", 'read' => "false",
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        );
        DB::table('chat')->insert($values);
        $msg = DB::table('chat')->where('user', '=', "$user_id")->orderBy('created_at', 'asc')->get();

        //
        // send notifications
        //
        // Send Notifications to user
        //
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['user' => $user_id]);
        $myRequest->request->add(['chat' => 'true']);
        $myRequest->request->add(['title' => Lang::get(477)]); // Chat Message
        $myRequest->request->add(['text' => $text]);
        $defaultImage = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
        $myRequest->request->add(['imageid' => $defaultImage]);
        MessagingController::sendNotify($myRequest);

        return ChatController::chatMessages($request);
    }

}