<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Lang
{
    public static function init(){
        if (count(DB::table('settings')->where("param", 'panelLang')->get()) == 0)
            DB::table('settings')->insert(['param' => 'panelLang', 'value' => 'langEng', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
    }

    public static function get($id)
    {
        Lang::init();
        $lang = DB::table('settings')->where('param', '=', "panelLang")->get()->first()->value;
        $t = Config::get("$lang.$id");
        return ($t == null ? Config::get("langEng.$id") : $t);
    }

    public static function setNewLang($newLang)
    {
        DB::table('settings')->where('param', '=', "panelLang")->update(['value' => $newLang, 'updated_at' => new \DateTime(),]);
        //
        DB::table('orderstatuses')->where('id', '=', "1")->update(['status' => Lang::get(438), 'updated_at' => new \DateTime(),]);
        DB::table('orderstatuses')->where('id', '=', "2")->update(['status' => Lang::get(439), 'updated_at' => new \DateTime(),]);
        DB::table('orderstatuses')->where('id', '=', "3")->update(['status' => Lang::get(440), 'updated_at' => new \DateTime(),]);
        DB::table('orderstatuses')->where('id', '=', "4")->update(['status' => Lang::get(441), 'updated_at' => new \DateTime(),]);
        DB::table('orderstatuses')->where('id', '=', "5")->update(['status' => Lang::get(442), 'updated_at' => new \DateTime(),]);
        DB::table('orderstatuses')->where('id', '=', "6")->update(['status' => Lang::get(443), 'updated_at' => new \DateTime(),]);
    }

    public static function direction()
    {
        Lang::init();
        $lang = DB::table('settings')->where('param', '=', "panelLang")->get()->first()->value;
        $langs = Config::get("langs");
        foreach ($langs as &$value) {
            if ($value['file'] == $lang)
                return $value['dir'];
        }
        return "ltr";
    }
}
