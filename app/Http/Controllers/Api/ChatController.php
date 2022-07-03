<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;
use App\Lang;
use App\Http\Controllers\MessagingController;

class ChatController extends Controller
{
    public function getChatMessages(Request $request)
    {
        Logging::logapi("Chat->Get Chat Messages");
        $id = auth('api')->user()->id;

        $msg = DB::table('chat')->where('user', '=', "$id")->orderBy('created_at', 'asc')->get();

        $response = [
            'error' => "0",
            'messages' => $msg
        ];
        return response()->json($response, 200);
    }

    public function chatNewMessage(Request $request)
    {
        Logging::logapi("Chat->Chat New Messages");
        $id = auth('api')->user()->id;

        $text = $request->input('text');

        $values = array(
            'user' => "$id", 'text' => "$text", 'author' => "customer",
            'delivered' => "false", 'read' => "false",
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        );
        DB::table('chat')->insert($values);
        $msg = DB::table('chat')->where('user', '=', "$id")->orderBy('created_at', 'asc')->get();

        //
        // Send Notifications to Admin and Managers
        //
        $managers = DB::table('users')->where('role',"<", "3")->get();
        foreach ($managers as &$value) {
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['chat' => 'true']);
            $myRequest->request->add(['user' => $value->id]);
            $myRequest->request->add(['title' => Lang::get(477)]); // Chat Message
            $myRequest->request->add(['text' => $text]);
            $defaultImage = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
            $myRequest->request->add(['imageid' => $defaultImage]);
            MessagingController::sendNotify($myRequest);
        }

        $response = [
            'error' => "0",
            'messages' => $msg
        ];
        return response()->json($response, 200);
    }
}