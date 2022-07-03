<?php
namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Logging;
use App\UserInfo;
use App\Lang;

class ChatController extends Controller
{
    public function getChatMessages(Request $request)
    {
        Logging::logapi("Chat->Get Chat Messages");
        $id = $request->input('user_id');

        $msg = DB::table('chat')->where('user', '=', "$id")->orderBy('created_at', 'asc')->get();

        $values = array('read' => 'true', 'updated_at' => new \DateTime());
        DB::table('chat')
            ->where('user', '=', "$id")
            ->where('author', '=', "customer")
            ->update($values);

        $response = [
            'error' => "0",
            'messages' => $msg
        ];
        return response()->json($response, 200);
    }

    public function chat(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');
        Logging::log("Chat Screen");

        $users = DB::table('users')->where("role", '>', "2")->
            leftjoin("image_uploads", 'image_uploads.id', '=', 'users.imageid')->
            select('users.*', 'image_uploads.filename as image')->get();

        return view('chat', ['users' => $users]);
    }

    public function chatNewMessage(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Chat->Chat New Messages");

        $user_id = $request->input('user_id');
        $text = $request->input('text');

        $values = array(
            'user' => "$user_id", 'text' => "$text", 'author' => "manager",
            'delivered' => "false", 'read' => "false",
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        );
        DB::table('chat')->insert($values);
        $msg = DB::table('chat')->where('user', '=', "$user_id")->orderBy('created_at', 'asc')->get();

        // send notification
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

        $response = [
            'error' => "0",
            'messages' => $msg
        ];
        return response()->json($response, 200);
    }

    public function getChatMessagesNewCount(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1",], 200);

        $t = DB::table('chat')->where('read', '=', "false")->where('author', '=', "customer")->rightJoin("users", "users.id", "chat.user")->where("role", '>', "2")->get();
        $count = count($t);

        $id=Auth::user()->id;
        if (UserInfo::getUserRoleId() == 1) // admin
            $orders = count(DB::select("SELECT * FROM orders WHERE view='false'"));
        else                              // manager
            $orders = count(DB::select("SELECT * FROM manager_rest
                        LEFT JOIN orders ON orders.restaurant=manager_rest.restaurant
                        WHERE manager_rest.user=$id AND orders.view='false' "));
            //('manager_rest')->where('user', '=', Auth::user()->id)->join("restaurants", 'restaurants.id', '=', 'manager_rest.restaurant')->get();

        //$orders = DB::table('settings')->where('param', '=', "ordersNotifications")->get()->first()->value;

        return response()->json([
            'error' => "0",
            't' => $t,
            'count' => $count,
            'orders' => $orders,
        ], 200);
    }

    public function chatNewUsers(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1",], 200);

        $users = DB::table('users')->where("role", '>', "2")->
                leftjoin("image_uploads", 'image_uploads.id', '=', 'users.imageid')->
                select('users.id', 'users.name', 'image_uploads.filename as image')->get();

        $usersMessages = DB::table('chat')->where('author', '=', "customer")->where('read', '=', "false")->selectRaw('user, count(*) as result')->groupBy('user')->get();
        $all = DB::table('chat')->where('author', '=', "customer")->where('read', '=', "true")->selectRaw('user, count(*) as result')->groupBy('user')->get();

        $usersData = array();
        foreach ($users as &$data) {
            if ($data->image == null)
                $data->image = "noimage.png";
            // all messages
            $data->messages = 0;
            foreach ($all as &$data2){
                if ($data->id == $data2->user)
                    $data->messages = $data2->result;
            }
            // unread messages
            $data->unread = 0;
            foreach ($usersMessages as &$data2){
                if ($data->id == $data2->user)
                    $data->unread = $data2->result;
            }
            $usersData[] = $data;
        }

        usort($usersData, function($a, $b) {
            if ($a->unread != $b->unread)
                return $a->unread < $b->unread ? 1 : -1;
            if ($a->messages == $b->messages) return 0;
            return $a->messages < $b->messages ? 1 : -1;
        });

        return response()->json([
            'error' => "0",
            'users' => $usersData,
        ], 200);
    }

}
