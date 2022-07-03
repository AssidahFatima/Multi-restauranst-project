<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Logging;
use App\Lang;

class ExportController extends Controller
{
    public function orders(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1"], 200);

        Logging::log2("Export CSV", "");

        $orders = DB::table('orders')->where('send', '1')->get();

//        $fileName = 'orders.csv';
//        $headers = array(
//            "Content-type"        => "text/csv",
//            "Content-Disposition" => "attachment; filename=$fileName",
//            "Pragma"              => "no-cache",
//            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
//            "Expires"             => "0"
//        );

        $columns = array('Id', 'Date', 'Restaurant ID', 'Restaurant Name', 'User', 'Method', 'Status', 'Total');

        $file = fopen('orders.csv', 'w');
        fputcsv($file, $columns);
        foreach ($orders as $task) {
            $row['Id'] = $task->id;
            $row['Date'] = $task->created_at;
            $row['Restaurant ID'] = $task->restaurant;
            $rest = DB::table('restaurants')->where('id', $task->restaurant)->get()->first();
            if ($rest != null)
                $row['Restaurant Name'] = $rest->name;
            $user = DB::table('users')->where('id', $task->user)->get()->first();
            if ($user != null)
                $row['User'] = $user->name;
            $row['Method'] = $task->method;
            $status = DB::table('orderstatuses')->where('id', $task->status)->get()->first();
            if ($status != null)
                $row['Status'] = $status->status;
            $row['Total'] = $task->total;
            fputcsv($file, array($row['Id'], $row['Date'], $row['Restaurant ID'], $row['Restaurant Name'], $row['User'], $row['Method'], $row['Status'], $row['Total']));
        }

        fclose($file);
        return response()->json(['error' => "0", 'file' => "orders.csv", 'debug' => ""], 200);
    }

    public function users(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1"], 200);

        Logging::log2("Export CSV", "");

        $users = DB::table('users')->get();

        $columns = array('Id', 'Name', 'Email', 'Date Create', 'Date Update', 'Role', 'Image', 'Address', 'Phone');

        $file = fopen('users.csv', 'w');
        fputcsv($file, $columns);
        foreach ($users as $task) {
            $row['Id'] = $task->id;
            $row['Name'] = $task->name;
            $row['Email'] = $task->email;
            $row['Date Create'] = $task->created_at;
            $row['Date Update'] = $task->updated_at;
            $role = DB::table('roles')->where('id', $task->role)->get()->first();
            if ($role != null)
                $row['Role'] = $role->role;
            $image = DB::table('image_uploads')->where('id', $task->imageid)->get()->first();
            if ($image != null)
                $row['Image'] = url("images/" . $image->filename);
            $row['Address'] = $task->address;
            $row['Phone'] = $task->phone;
            fputcsv($file, array($row['Id'], $row['Name'], $row['Email'], $row['Date Create'], $row['Date Update'], $row['Role'], $row['Image'], $row['Address'], $row['Phone']));
        }

        fclose($file);
        return response()->json(['error' => "0", 'file' => "users.csv", 'debug' => ""], 200);
    }

    public function products(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1"], 200);

        Logging::log2("Export CSV", "");

        $users = DB::table('foods')->get();
        $columns = array('Id', 'Name', 'Description', 'Date Create', 'Date Update', 'Image', 'Price', 'Discount Price', 'Restaurant ID', 'Category ID');

        $file = fopen('foods.csv', 'w');
        fputcsv($file, $columns);
        foreach ($users as $task) {
            $row['Id'] = $task->id;
            $row['Name'] = $task->name;
            $row['Description'] = $task->desc;
            $row['Date Create'] = $task->created_at;
            $row['Date Update'] = $task->updated_at;
            $image = DB::table('image_uploads')->where('id', $task->imageid)->get()->first();
            if ($image != null)
                $row['Image'] = url("images/" . $image->filename);
            $row['Price'] = $task->price;
            $row['Discount Price'] = $task->discountprice;
            $row['Restaurant ID'] = $task->restaurant;
            $row['Category ID'] = $task->category;
            fputcsv($file, array($row['Id'], $row['Name'], $row['Description'], $row['Date Create'], $row['Date Update'], $row['Image'],
                $row['Price'], $row['Discount Price'], $row['Restaurant ID'], $row['Category ID']));
        }

        fclose($file);
        return response()->json(['error' => "0", 'file' => "foods.csv", 'debug' => ""], 200);
    }

}
