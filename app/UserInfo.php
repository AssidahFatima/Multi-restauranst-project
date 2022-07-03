<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserInfo
{
    public static function getUserRole()
    {
	    $role = Auth::user()->role;
        $roles = DB::table('roles')->where('id', '=', $role)->get()->first();
        return $roles->role;
    }
    
    public static function getUserRoleId()
    {
        $role = Auth::user()->role;
        return $role;
    }

    public static function getUserAvatar()
    {
        $imageAvatar = "img/user.png";
        $imageid = Auth::user()->imageid;
        if ($imageid != null) {
            $imageid = DB::table('image_uploads')->where('id', '=', $imageid)->get()->first();
            if ($imageid != null)
                $imageAvatar = 'images/' . $imageid->filename;
        }
        return $imageAvatar;
    }

    public static function getUserPermission($name)
    {
        $ret = false;
        $role = Auth::user()->role;
        $permission = DB::table('permissions')->where('value', '=', $name)->get()->first();
        if ($role == 1)
            $ret = $permission->role1;
        if ($role == 2)
            $ret = $permission->role2;
        if ($role == 3)
            $ret = $permission->role3;
        if ($role == 4)
            $ret = $permission->role4;
        return $ret;
    }

}
