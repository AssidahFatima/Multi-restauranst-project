<?php

namespace App\Http\Controllers;

use App\Settings;
use App\Logging;
use App\Lang;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class UserController extends Controller
{
    public function roles(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Users->Roles Screen");

        $data = DB::table('roles')->get();
        return view('roles', ['idata' => $data]);
    }

    public function users(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log("Users->Users Screen");

        $user_id = $request->input('user_id') ?: "";
        $restaurants = DB::table('restaurants')->get();
        return view('users', ['showuser' => $user_id,
            'restaurants' => $restaurants,
        ]);
    }

    public function usersGoPage(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error'=>"1"]);

        $search = $request->input('search') ? : "";
        $role = $request->input('role') ? : 0;
        $page = $request->input('page') ? : 1;
        $count = $request->input('count') ? : 10;
        $sortBy = $request->input('sortBy') ? : "id";
        $sortAscDesc = $request->input('sortAscDesc') ? : "asc";

        $user_id = $request->input('user_id') ?: "";
        $petani = DB::table('image_uploads')->get();

        Logging::log("Users->Go Page " . $page . " search: " . $search);

        $offset = ($page - 1) * $count;

        if ($role == 0) {
            if (Auth::user()->role == 1) {    // admin
                $data = DB::select("SELECT * FROM users WHERE email LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' ORDER BY " . $sortBy . " " . $sortAscDesc . " LIMIT " . $count . " OFFSET " . $offset);
                $total = count(DB::select("SELECT * FROM users WHERE email LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%'"));
            } else { // manager, driver, client
                $data = DB::select("SELECT * FROM users WHERE role > 2 AND ( email LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' ) ORDER BY " . $sortBy . " " . $sortAscDesc . " LIMIT " . $count . " OFFSET " . $offset);
                $total = count(DB::select("SELECT * FROM users WHERE role > 2 AND ( email LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' )"));
            }
        }else {
            $data = DB::select("SELECT * FROM users WHERE role = " . $role . " AND ( email LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' ) ORDER BY " . $sortBy . " " . $sortAscDesc .  " LIMIT " . $count . " OFFSET " . $offset);
            $total = count(DB::select("SELECT * FROM users WHERE role = " . $role . " AND ( email LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' )"));
        }

        $t = $total/$count;
        if ($total%$count > 0)
            $t++;

        $roles = DB::table('roles')->get();
        foreach ($data as &$user) {
            $user->roleName = "user";
            $filename = DB::table('image_uploads')->where("id", $user->imageid)->get()->first();
            if ($filename != null)
                $user->filename = $filename->filename;
            else
                $user->filename = "noimage.png";
            foreach ($roles as &$role) {
                if ($user->role == $role->id) {
                    $user->roleName = $role->role;
                    break;
                }
            }
            $user->timeago = Util::timeago($user->updated_at);
        }

        return response()->json(['error'=>"0", 'idata' => $data, 'page' => $page, 'pages' => $t]);
    }

    public function add(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error'=>"1"]);

        $id = $request->input('id', "0"); // for edit

        // create item id "users" table
        $email = $request->input('email') ?: "";
        if (DB::table('users')->where("email", $email)->get()->first() != null && $id == "0")
            return response()->json(['error'=>"2", 'id' => $id]);

        $name = $request->input('name') ?: "";
        $role = $request->input('role') ?: "";
        $email = $request->input('email') ?: "";
        $password = $request->input('password') ?: "";
        $phone = $request->input('phone') ?: "";
        $address = $request->input('address') ?: "";
        $image = $request->input('image') ?: 0;

        $values = array('name' => $name, 'email' => $email, 'role'=>$role,
            'imageid' => $image, 'address' => $address, 'phone' => $phone,
            'updated_at' => new \DateTime());

        if ($id != "0"){
            if ($password != "")
                $values['password'] = bcrypt($password);
            DB::table('users')->where('id', $id)->update($values);
            Logging::log2("Users->Update User", "Name: " . $name);
        }else{
            $values['password'] = bcrypt($password);
            $values['created_at'] = new \DateTime();
            DB::table('users')->insert($values);
            $id = DB::getPdo()->lastInsertId();
            Logging::log2("Users->Add User", "Name: " . $name);
        }

        if ($role == '2'){ // manager
            DB::table('manager_rest')->where('user', $id)->delete();
            $restaurants = DB::table('restaurants')->get();
            foreach ($restaurants as &$value) {
                $val = $request->input('rest' . $value->id) ?: "";
                if ($val == 'on'){
                    $values = array('user' => $id, 'restaurant' => $value->id,
                        'created_at' => new \DateTime(), 'updated_at' => new \DateTime());
                    DB::table('manager_rest')->insert($values);
                }
            }
        }

        return UserController::getOneUser($id);
    }

    public function getOneUser($id){
        $user = DB::table('users')->where("id", $id)->get()->first();
        $roles = DB::table('roles')->get();

        $user->roleName = "client";
        $filename = DB::table('image_uploads')->where("id", $user->imageid)->get()->first();
        if ($filename != null)
            $user->filename = $filename->filename;
        else
            $user->filename = "noimage.png";
        foreach ($roles as &$role) {
            if ($user->role == $role->id) {
                $user->roleName = $role->role;
                break;
            }
        }
        $user->timeago = Util::timeago($user->updated_at);
        $managerest = DB::table('manager_rest')->get();
        return response()->json(['error'=>"0", 'user' => $user, 'managerest' => $managerest]);
    }

    public function userGetInfo(Request $request)
    {
        if (!Auth::check())
            return response()->json(['error' => "1"]);

        $id = $request->input('id', "0");
        if ($id == 0)
            return response()->json(['error' => "4"]);

        return UserController::getOneUser($id);
    }

    public function delete(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');
        $name = DB::table('users')->where('id',$id)->get()->first()->name;

        if (Settings::isDemoMode()){
            Logging::log3("Users->Delete User", "Name: " . $name, Lang::get(487)); // "Abort! This is demo mode"
            return response()->json(['ret'=>false, 'text' => Lang::get(489)]); // This is demo app. You can't change this section
        }

        Logging::log2("Users->Delete User", "Name: " . $name);

        DB::table('users')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);
    }
}
