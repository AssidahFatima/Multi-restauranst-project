<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Logging;

class FoodReviewsController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Food Review Screen");

        return FoodReviewsController::view("", "");
    }

    function view($green, $text){
        $users = DB::table('users')->get();
        $data = DB::table('foodsreviews')->get();
        $foods = DB::table('foods')->get();
        return view('foodreviews', ['idata' => $data, 'iusers' => $users, 'ifoods' => $foods, 'texton' => $green, 'text' => $text]);
    }

    public function add(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        FoodReviewsController::dataMassive($request, 1);
        return FoodReviewsController::view("green","Review create successfully");
    }


    function dataMassive(Request $request, $update){
        $id = $request->input('id');

        $user = $request->input('user') ?: "";
        $food = $request->input('food') ?: "";
        $rate = $request->input('rate') ?: "";
        $review = $request->input('review') ?: "";
        $review = preg_replace('/[\r\n\t]/', '', $review);

        $values = array('desc' => $review,
            'user' => $user, 'rate' => $rate, 'food' => $food,
            'updated_at' => new \DateTime());

        $name = DB::table('users')->where('id',$id)->get()->first()->name;
        $foodname = DB::table('foods')->where('id',$id)->get()->first()->name;

        if ($update == 2){
            DB::table('foodsreviews')
                ->where('id',$id)
                ->update($values);
            Logging::log2("Food Review->Update", "Name:" . $name . " Food: " . $foodname);
        }
        if ($update == 1) {
            $values['created_at'] = new \DateTime();
            DB::table('foodsreviews')->insert($values);
            Logging::log2("Food Review->Create", "Name:" . $name . " Food: " . $foodname);
        }

    }

    public function delete(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $nameid = DB::table('foodsreviews')->where('id',$id)->get()->first()->user;
        $name = DB::table('users')->where('id',$nameid)->get()->first()->name;
        $foodid = DB::table('foodsreviews')->where('id',$id)->get()->first()->food;
        $foodname = DB::table('foods')->where('id',$foodid)->get()->first()->name;
        if (Settings::isDemoMode()){
            Logging::log3("Food Review->Delete", "Name:" . $name . " Food: " . $foodname, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>'This is demo app. You can\'t change this section']);
        }

        DB::table('foodsreviews')
            ->where('id',$id)
            ->delete();

        Logging::log3("Food Review->Delete", "Name:" . $name . " Food: " . $foodname, "");

        return response()->json(['ret'=>true]);
    }

    public function edit(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        FoodReviewsController::dataMassive($request, 2);
        return FoodReviewsController::view("green", "Review Updated successfully");
    }


}
