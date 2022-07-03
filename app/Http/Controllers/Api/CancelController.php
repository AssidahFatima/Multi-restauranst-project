<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\MessagingController;
use App\Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use App\Logging;

class CancelController extends Controller
{
    public function cancel(Request $request)
    {
        $order_id = $request->input('order_id');

        $values = array('status' => "6",
            'updated_at' => new \DateTime());
        DB::table('orders')->where('id',$order_id)->update($values);

        //
        // save to OrdersTime details
        //
        $values = array(
            'order_id' => "$order_id", 'status' => "6", 'driver' => 0,
            'comment' => "Cancel by customer",
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        );
        DB::table('ordertimes')->insert($values);

        $count = DB::table('settings')->where('param', '=', "ordersNotifications")->get()->first()->value;
        $count += 1;
        DB::table('settings')->where('param', '=', "ordersNotifications")->update(['value' => "$count", 'updated_at' => new \DateTime(),]);

        //
        // send notifications to manager and admin
        //
        // Send Notifications to Admin
        //
        $order = DB::table('orders')->where("id", "$order_id")->get()->first();
        $defaultImage = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
        $userid = DB::table('users')->where('role',"1")->get()->first();
        if ($userid != null) {
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['user' => $userid->id]);
            $myRequest->request->add(['title' => Lang::get(470)]); // 'Order status changed',
            $myRequest->request->add(['text' => Lang::get(475) . $order_id . Lang::get(476) . "cancelled"]);  // "You order #",  ' was '
            $myRequest->request->add(['imageid' => $defaultImage]);
            MessagingController::sendNotify($myRequest);
        }
        //
        // Send Notifications to Managers
        //
        if ($order != null) {
            $managers = DB::table('users')->where('role', "2")->get();
            foreach ($managers as &$value) {
                //$restaurant
                $manager = DB::table('manager_rest')->where('user', '=', $value->id)->where('restaurant', '=', $order->restaurant)->get();
                if (count($manager) != 0) {
                    $myRequest = new \Illuminate\Http\Request();
                    $myRequest->setMethod('POST');
                    $myRequest->request->add(['user' => $value->id]);
                    $myRequest->request->add(['title' => Lang::get(470)]); // 'Order status changed',
                    $myRequest->request->add(['text' => Lang::get(475) . $order_id . Lang::get(476) . "cancelled"]);  // "You order #",  ' was '
                    $myRequest->request->add(['imageid' => $defaultImage]);
                    MessagingController::sendNotify($myRequest);
                }
            }
        }
        $response = [
            'error' => '0',
        ];
        return response()->json($response, 200);

    }
}
