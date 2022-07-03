<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Logging;
use App\UserInfo;
use App\Util;
use App\Currency;
use App\Lang;

class OrdersController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');
        Logging::log("Orders Screen");
        return OrdersController::view();
    }

    function view(){
        $iusers = DB::table('users')->get();
        $orderstatus = DB::table('orderstatuses')->get();
        $restaurants = DB::table('restaurants')->get();
        $drivers = DB::table('users')->where("role", '3')->get();
        $ordersdetails = null;

        $settings = DB::table('settings')->where('param', '=', "default_currencies")->get()->first();
        $currency = "";
        if ($settings != null)
            $currency = $settings->value;
        // currency
        $rightSymbol = DB::table('settings')->where('param', '=', "rightSymbol")->get()->first()->value;
        $temp = DB::table('settings')->where('param', '=', "default_currencyCode")->get()->first()->value;
        $symbolDigits = DB::table('currencies')->where('code', '=', $temp)->get()->first()->digits;
        //
        DB::table('settings')->where('param', '=', "ordersNotifications")->update(['value' => "0", 'updated_at' => new \DateTime(),]);
        //
        $coupons = DB::table('coupons')->get();

        return view('orders', ['iusers' => $iusers, 'iorderstatus' => $orderstatus,
            'irestaurants' => $restaurants, 'idrivers' => $drivers, 'currency' => $currency, 'coupons' => $coupons,
            'texton' => "", 'text' => '', 'rightSymbol' => $rightSymbol, 'symbolDigits' => $symbolDigits]);
    }

    public function delete(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        if (Settings::isDemoMode()){
            Logging::log3("Orders->Delete", "Id: " . $id, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>Lang::get(467)]); // "This is demo app. You can not change this section",
        }
        Logging::log3("Orders->Delete", "Id: " . $id, "");

        DB::table('orders')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }

    public function orderDetailsDelete(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $orderid = $request->input('orderid');
        Logging::log3("Orders Details->Delete Dishes", "Id: " . $id, "");

        DB::table('ordersdetails')
            ->where('id',$id)
            ->delete();

        //
        $order = DB::table('orders')->where('id', '=', $orderid)->get()->first();
        $ordersdetails = DB::table('ordersdetails')->where('order', '=', $orderid)->get();
        $subtotal = 0;
        foreach ($ordersdetails as &$value) {
            $total = $value->foodprice * $value->count + $value->extrasprice * $value->extrascount;
            $subtotal = $subtotal + $total;
        }
        $fee = $order->fee*$subtotal/100;
        if ($order->percent == 0)
            $fee = $order->fee;
        $tax = $order->tax*$subtotal/100;
        $total = $subtotal + $fee + $tax;

        $values = array('total' => $total, 'updated_at' => new \DateTime());
        DB::table('orders')
            ->where('id',$orderid)
            ->update($values);

        return OrdersController::orderview2($orderid);
    }

    public function orderDetailsAdd(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $orderid = $request->input('orderid');
        $count = $request->input('count');
        Logging::log3("Order Details->Add Diches", "Id: " . $id, "");

        //
        $food = DB::table('foods')->where('id', '=', $id)->get()->first();
        $image = DB::table('image_uploads')->where('id', '=', $food->imageid)->get()->first();
        if ($image != null)
            $image = $image->filename;
        else
            $image = 0;
        $price = $food->price;
        if ($food->discountprice != 0)
            $price = $food->discountprice;
        $values = array('order' => $orderid, 'food' => $food->name,
            'count' => $count, 'foodprice' => $price,
            'extras' => "", 'extrascount' => 0, 'extrasprice' => 0, 'foodid' => $id, 'extrasid' => 0,
            'image' => $image,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime());
        DB::table('ordersdetails')->insert($values);

        //
        $order = DB::table('orders')->where('id', '=', $orderid)->get()->first();
        $ordersdetails = DB::table('ordersdetails')->where('order', '=', $orderid)->get();
        $subtotal = 0;
        foreach ($ordersdetails as &$value) {
            $total = $value->foodprice * $value->count + $value->extrasprice * $value->extrascount;
            $subtotal = $subtotal + $total;
        }
        $fee = $order->fee*$subtotal/100;
        if ($order->percent == 0)
            $fee = $order->fee;
        $tax = $order->tax*$subtotal/100;
        $total = $subtotal + $fee + $tax;

        $values = array('total' => $total, 'updated_at' => new \DateTime());
        DB::table('orders')
            ->where('id',$orderid)
            ->update($values);

        return OrdersController::orderview2($orderid);
    }

    function dataMassive(Request $request, $update){
        $id = $request->input('id');

        $user = $request->input('user') ?: 0;
        $restaurant = $request->input('restaurant') ?: 0;
        $driver = $request->input('driver') ?: 0;
        $hint = $request->input('hint') ?: "";
        $status = $request->input('status') ?: 0;
        $pstatus = $request->input('pstatus') ?: "";
        $tax = $request->input('tax') ?: 0;
        $active = $request->input('active') ?: "";
        if ($active == 'on') $active = true; else $active = false;

        $values = array('user' => $user, 'driver' => $driver, 'status' => "$status", 'pstatus' => $pstatus,
            'tax' => $tax, 'hint' => $hint, 'active' => $active, 'restaurant' => $restaurant,
            'updated_at' => new \DateTime());

        if ($update == 2){
            DB::table('orders')
                ->where('id',$id)
                ->update($values);
            Logging::log2("Orders->Update", "Id: " . $id);
        }
    }

    public function edit(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        OrdersController::dataMassive($request, 2);
        return OrdersController::view();
    }

    public function orderview(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        return OrdersController::orderview2($id);
    }

    public function orderview2($id)
    {
        Logging::log2("Orders->Order Detail", "Id: " . $id);
        try {
            $orders = DB::table('orders')->where('id', '=', $id)->get()->first();
            if ($orders != null) {
                $restaurant = DB::table('restaurants')->where('id', '=', $orders->restaurant)->get()->first();
                $userDriver = DB::table('users')->where('id', '=', $orders->driver)->get()->first();
                $user = DB::table('users')->where('id', '=', $orders->user)->get()->first();
                $orderstatus = DB::table('orderstatuses')->where('id', '=', $orders->status)->get()->first();
                $ordersdetails = DB::table('ordersdetails')->where('order', '=', $orders->id)->get();
                foreach ($ordersdetails as &$value) {
                    $foodCategory = DB::table('foods')->where('id', '=', "$value->foodid")->get()->first();
                    if ($foodCategory != null)
                        $value->category = $foodCategory->category;
                }
                $settings = DB::table('settings')->where('param', '=', "default_currencies")->get()->first();
                $orderstatuses = DB::table('orderstatuses')->get();
                $ordertimes = DB::table('ordertimes')->where('order_id', '=', $id)->orderBy('updated_at', 'desc')->get();
                $foods = DB::table('foods')->where('restaurant', '=', $orders->restaurant)->get();
                $petani = DB::table('image_uploads')->get();
                $drivers = DB::table('users')->where('role', '=', '3')->get();
                foreach ($foods as &$food) {
                    $food->image = "noimage.png";
                    foreach ($petani as &$image) {
                        if ($food->imageid == $image->id){
                            $food->image = $image->filename;
                            break;
                        }
                    }
                }
                return response()->json(['ret' => true, 'order' => $orders, 'restaurant' => $restaurant,
                    'driveruser' => $userDriver, 'user' => $user, 'status' => $orderstatus->status,
                    'currency' => $settings->value, 'ordertimes' => $ordertimes, 'orderstatuses' => $orderstatuses,
                    'drivers' => $drivers,
                    'ordersdetails' => $ordersdetails, 'foods' => $foods]);
            }else
                return response()->json(['ret' => false, '$orders' => $orders, 'id' => $id]);
        } catch (Throwable $e) {
            return response()->json(['ret'=>false, 'error' => $e]);
        }
        return response()->json(['ret' => false, '$orders' => $orders, 'id' => $id]);
    }

    public function changeStatus(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $order_id = $request->input('id') ?: "";
        $status = $request->input('status') ?: "";

        $values = array('status' => $status, 'updated_at' => new \DateTime());
        DB::table('orders')->where('id',$order_id)->update($values);
        Logging::log2("Orders->Update Status", " orderId: " . $order_id);

        //
        // Send Notifications to user
        //
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $id = DB::table('orders')->where('id',$order_id)->get()->first()->user;
        $myRequest->request->add(['user' => $id]);
        $myRequest->request->add(['title' => Lang::get(470)]); // 'Order status changed',
        $status_text = DB::table('orderstatuses')->where('id',$status)->get()->first()->status;
        $myRequest->request->add(['text' => Lang::get(475) . $order_id . Lang::get(476) . $status_text]);  // "You order #",  ' was '
        $defaultImage = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
        $myRequest->request->add(['imageid' => $defaultImage]);
        MessagingController::sendNotify($myRequest);

        //
        // save to OrdersTime details
        //
        $values = array(
            'order_id' => "$order_id", 'status' => "$status", 'driver' => 0,
            'comment' => "",
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        );
        DB::table('ordertimes')->insert($values);

        $orderstatuses = DB::table('orderstatuses')->get();
        $ordertimes = DB::table('ordertimes')->where('order_id', '=', $order_id)->orderBy('updated_at', 'desc')->get();
        $drivers = DB::table('users')->where('role', '=', '3')->get();

        return response()->json([
            'ret'=>true,
            'ordertimes' => $ordertimes,
            'orderstatuses' => $orderstatuses,
            'drivers' => $drivers,
        ]);
    }

    public static function changeDriver(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $order_id = $request->input('id') ?: "";
        $driver = $request->input('driver') ?: "";

        $values = array('driver' => $driver, 'updated_at' => new \DateTime());

        DB::table('orders')
            ->where('id',$order_id)
            ->update($values);

        Logging::log2("Orders->Update Driver", " orderId: " . $order_id);

        //
        // Send Notifications to driver
        //
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['user' => $driver]);
        $myRequest->request->add(['title' => Lang::get(538)]); // New order received
        $myRequest->request->add(['text' => Lang::get(536) . $order_id . Lang::get(537)]); //  "New order #",  was received
        $defaultImage = DB::table('settings')->where('param', '=', "notify_image")->get()->first()->value;
        $myRequest->request->add(['imageid' => $defaultImage]);
        MessagingController::sendNotify($myRequest);

        //
        // save to OrdersTime details
        //
        $values = array(
            'order_id' => "$order_id", 'status' => "15", 'driver' => $driver,
            'comment' => "",
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        );
        DB::table('ordertimes')->insert($values);

        return response()->json(['ret'=>true]);
    }

    public function ordersGoPage(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1"]);

        $search = $request->input('search') ?: "";
        $cat = $request->input('cat', "0");
        $rest = $request->input('rest', 0) ?: 0;
        $page = $request->input('page') ?: 1;
        $count = $request->input('count', 10);
        $sortBy = $request->input('sortBy') ?: "id";
        $sortAscDesc = $request->input('sortAscDesc') ?: "asc";
        $sortPublished = $request->input('sortPublished', "1");
        $sortUnPublished = $request->input('sortUnPublished', "1");

        //$user_id = $request->input('user_id') ?: "";
        $petani = DB::table('image_uploads')->get();

        Logging::log("Orders->Go Page " . $page . " search: " . $search);

        $offset = ($page - 1) * $count;

        $searchVisible = "";
        if ($sortPublished != '1' || $sortUnPublished != '1') {
            if ($sortPublished == '1')
                $searchVisible = "published = '1' AND ";
            if ($sortUnPublished == '1')
                $searchVisible = "published = '0' AND ";
        }
        if ($sortPublished == '0' && $sortUnPublished == '0')
            $searchVisible = "published='3' AND ";

        $searchCat = "";
        if ($cat != "0")
            $searchCat = " orders.status=" . $cat . " AND ";

        $searchRest = "";
        if ($rest != "0")
            $searchRest = " orders.restaurant=" . $rest . " AND ";

        $rightJoin = "";
        if (UserInfo::getUserRoleId() > 1) { // manager
            $rightJoin = " RIGHT JOIN manager_rest ON manager_rest.user = " . Auth::user()->id . " AND orders.restaurant = manager_rest.restaurant";
            DB::select($textt = "UPDATE manager_rest
                        LEFT JOIN orders ON orders.restaurant=manager_rest.restaurant
                        SET orders.view='true'
                        WHERE manager_rest.user=" . Auth::user()->id);
        }else{      // admin
            DB::table('orders')->update(['view' => "true",]);
        }


        //return response()->json(['error' => "0", '$textt' => $textt, 'id' => UserInfo::getUserRoleId()]);

        $data = DB::select("SELECT orders.*, users.name, restaurants.name as restaurantName FROM orders " . $rightJoin . " LEFT JOIN users ON users.id=orders.user LEFT JOIN restaurants ON restaurants.id=orders.restaurant
                        WHERE " . $searchRest . $searchVisible . $searchCat . " orders.send=1 AND (orders.id LIKE '%" . $search . "%' OR users.name LIKE '%" . $search . "%') ORDER BY " . $sortBy . " " . $sortAscDesc . " LIMIT " . $count . " OFFSET " . $offset);
        $total = count(DB::select("SELECT orders.* FROM orders " . $rightJoin . " LEFT JOIN users ON users.id=orders.user WHERE " . $searchRest . $searchVisible . $searchCat . " orders.send=1 AND (orders.id LIKE '%" . $search . "%' OR users.name LIKE '%" . $search . "%')"));

        foreach ($data as &$value) {
            $value->timeago = Util::timeago($value->updated_at);
            $value->totalFull = Currency::makePrice($value->total);
        }

        $t = $total / $count;
        if ($total % $count > 0)
            $t++;

        return response()->json(['error' => "0", 'idata' => $data, 'page' => $page, 'pages' => $t, 'total' => $total]);
    }
}
