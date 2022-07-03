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

class SettingsController extends Controller
{
    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        Logging::log2("Settings Screen", "");

        $tax = DB::table('settings')->where('param', '=', "default_tax")->get()->first()->value;
        $firebase = DB::table('settings')->where('param', '=', "firebase_key")->get()->first()->value;
        $mapapikey = DB::table('settings')->where('param', '=', "mapapikey")->get()->first()->value;
        $distanceUnit = DB::table('settings')->where('param', '=', "distanceUnit")->get()->first()->value;
        // default lang
        $langs = Config::get("langs");
        $lang = DB::table('settings')->where('param', '=', "panelLang")->get()->first()->value;
        // time zone
        $timezonesArray = timezone_identifiers_list();
        $timezone = DB::table('settings')->where('param', '=', "timezone")->get()->first()->value;

        return view('settings', ['tax' => $tax, 'firebase' => $firebase, 'mapapikey' => $mapapikey,
            'distanceUnit' => $distanceUnit, 'langs' => $langs, 'defLang' => $lang, 'timezonesArray' => $timezonesArray, 'timezone' => $timezone]);
    }

    public function settingsSetLang(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $newLang = $request->input('newLang') ?: 'langEng';
        Lang::setNewLang($newLang);

        return \Redirect::to('/settings');
    }

    public function change(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $tax = $request->input('tax') ?: 0;
        DB::table('settings')->where('param', 'default_tax')->update(array('value' => $tax, 'updated_at' => new \DateTime()));
        $distanceUnit = $request->input('distanceUnit');
        DB::table('settings')->where('param', 'distanceUnit')->update(array('value' => $distanceUnit, 'updated_at' => new \DateTime()));

        $firebase = $request->input('firebase') ?: "";
        DB::table('settings')->where('param', 'firebase_key')->update(array('value' => $firebase, 'updated_at' => new \DateTime()));
        $mapapikey = $request->input('mapapikey') ?: "";
        DB::table('settings')->where('param', 'mapapikey')->update(array('value' => $mapapikey, 'updated_at' => new \DateTime()));

        DB::table('settings')->where('param', 'timezone')->update(array('value' => $request->input('timezone'), 'updated_at' => new \DateTime()));

        Logging::log2("Settings->Save ", "");

        $response = ['error' => "0"];
        return response()->json($response, 200);
    }

    //
    //
    //
    public function payments(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        return SettingsController::paymentsView("", "");
    }

    function paymentsView($green, $text){
        $StripeEnable = DB::table('settings')->where('param', '=', "StripeEnable")->get()->first()->value;
        $stripeKey = DB::table('settings')->where('param', '=', "stripeKey")->get()->first()->value;
        $stripeSecretKey = DB::table('settings')->where('param', '=', "stripeSecretKey")->get()->first()->value;
        $razEnable = DB::table('settings')->where('param', '=', "razEnable")->get()->first()->value;
        $razKey = DB::table('settings')->where('param', '=', "razKey")->get()->first()->value;
        $razName = DB::table('settings')->where('param', '=', "razName")->get()->first()->value;
        $cashEnable = DB::table('settings')->where('param', '=', "cashEnable")->get()->first()->value;
        //
        $walletEnable = DB::table('settings')->where('param', '=', "walletEnable")->get()->first()->value;
        $payPalEnable = DB::table('settings')->where('param', '=', "payPalEnable")->get()->first()->value;
        $payPalSandBox = DB::table('settings')->where('param', '=', "payPalSandBox")->get()->first()->value;
        $payPalClientId = DB::table('settings')->where('param', '=', "payPalClientId")->get()->first()->value;
        $payPalSecret = DB::table('settings')->where('param', '=', "payPalSecret")->get()->first()->value;
        $payStackKey = DB::table('settings')->where('param', '=', "payStackKey")->get()->first()->value;
        $payStackKey = DB::table('settings')->where('param', '=', "payStackKey")->get()->first()->value;
        $payStackEnable = DB::table('settings')->where('param', '=', "payStackEnable")->get()->first()->value;
        // yandxKassa
        $yandexKassaEnable = DB::table('settings')->where('param', '=', "yandexKassaEnable")->get()->first()->value;
        $yandexKassaShopId = DB::table('settings')->where('param', '=', "yandexKassaShopId")->get()->first()->value;
        $yandexKassaClientAppKey = DB::table('settings')->where('param', '=', "yandexKassaClientAppKey")->get()->first()->value;
        $yandexKassaSecretKey = DB::table('settings')->where('param', '=', "yandexKassaSecretKey")->get()->first()->value;
        // instamojo
        $instamojoEnable = DB::table('settings')->where('param', '=', "instamojoEnable")->get()->first()->value;
        $instamojoApiKey = DB::table('settings')->where('param', '=', "instamojoApiKey")->get()->first()->value;
        $instamojoPrivateToken = DB::table('settings')->where('param', '=', "instamojoPrivateToken")->get()->first()->value;
        $instamojoSandBoxMode = DB::table('settings')->where('param', '=', "instamojoSandBoxMode")->get()->first()->value;

        return view('payments', ['StripeEnable' => $StripeEnable, 'stripeKey' => $stripeKey, 'stripeSecretKey' => $stripeSecretKey,
            'razEnable' => $razEnable, 'razKey' => $razKey, 'razName' => $razName, 'cashEnable' => $cashEnable,
            'payPalEnable' => $payPalEnable, 'payPalSandBox' => $payPalSandBox, 'payPalClientId' => $payPalClientId, 'payPalSecret' => $payPalSecret,
            'walletEnable' => $walletEnable,
            'payStackEnable' => $payStackEnable, 'payStackKey' => $payStackKey,
            'yandexKassaEnable' => $yandexKassaEnable, 'yandexKassaShopId' => $yandexKassaShopId,
            'yandexKassaClientAppKey' => $yandexKassaClientAppKey, 'yandexKassaSecretKey' => $yandexKassaSecretKey,
            'instamojoEnable' => $instamojoEnable, 'instamojoSandBoxMode' => $instamojoSandBoxMode,
            'instamojoApiKey' => $instamojoApiKey, 'instamojoPrivateToken' => $instamojoPrivateToken,
            // payMob
            'payMobEnable' => DB::table('settings')->where('param',"payMobEnable")->get()->first()->value,
            'payMobApiKey' => DB::table('settings')->where('param',"payMobApiKey")->get()->first()->value,
            'payMobFrame' => DB::table('settings')->where('param',"payMobFrame")->get()->first()->value,
            'payMobIntegrationId' => DB::table('settings')->where('param',"payMobIntegrationId")->get()->first()->value,
            // FlutterWave
            'FlutterWaveEnable' => DB::table('settings')->where('param',"FlutterWaveEnable")->get()->first()->value,
            'FlutterWaveEncryptionKey' => DB::table('settings')->where('param',"FlutterWaveEncryptionKey")->get()->first()->value,
            'FlutterWavePublicKey' => DB::table('settings')->where('param',"FlutterWavePublicKey")->get()->first()->value,
            // MercadoPago
            'MercadoPagoEnable' => DB::table('settings')->where('param',"MercadoPagoEnable")->get()->first()->value,
            'MercadoPagoAccessToken' => DB::table('settings')->where('param',"MercadoPagoAccessToken")->get()->first()->value,
            'MercadoPagoPublicKey' => DB::table('settings')->where('param',"MercadoPagoPublicKey")->get()->first()->value,
            ]);
    }

    public function paymentsSave(Request $request){
        $StripeEnable = $request->input('StripeEnable') ?: "";
        $stripeKey = $request->input('stripeKey') ?: "";
        $stripeSecretKey = $request->input('stripeSecretKey') ?: "";
        $razEnable = $request->input('razEnable') ?: "";
        $razKey = $request->input('razKey') ?: "";
        $razName = $request->input('razName') ?: "";
        $cashEnable = $request->input('cashEnable') ?: "";
        $walletEnable = $request->input('walletEnable') ?: "";
        DB::table('settings')->where('param', 'StripeEnable')->update(array('value' => $StripeEnable, 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'stripeKey')->update(array('value' => $stripeKey, 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'stripeSecretKey')->update(array('value' => $stripeSecretKey, 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'razEnable')->update(array('value' => $razEnable, 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'razKey')->update(array('value' => $razKey, 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'razName')->update(array('value' => $razName, 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'cashEnable')->update(array('value' => $cashEnable, 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'walletEnable')->update(array('value' => $walletEnable, 'updated_at' => new \DateTime()));
        // PayPal
        $payPalEnable = $request->input('payPalEnable') ?: "";
        DB::table('settings')->where('param', 'payPalEnable')->update(array('value' => $payPalEnable, 'updated_at' => new \DateTime()));
        $payPalSandBox = $request->input('payPalSandBox') ?: "";
        DB::table('settings')->where('param', 'payPalSandBox')->update(array('value' => $payPalSandBox, 'updated_at' => new \DateTime()));
        $payPalClientId = $request->input('payPalClientId') ?: "";
        DB::table('settings')->where('param', 'payPalClientId')->update(array('value' => $payPalClientId, 'updated_at' => new \DateTime()));
        $payPalSecret = $request->input('payPalSecret') ?: "";
        DB::table('settings')->where('param', 'payPalSecret')->update(array('value' => $payPalSecret, 'updated_at' => new \DateTime()));
        // PayStack
        $payStackEnable = $request->input('payStackEnable') ?: "";
        DB::table('settings')->where('param', 'payStackEnable')->update(array('value' => $payStackEnable, 'updated_at' => new \DateTime()));
        $payStackKey = $request->input('payStackKey') ?: "";
        DB::table('settings')->where('param', 'payStackKey')->update(array('value' => $payStackKey, 'updated_at' => new \DateTime()));
        // yandexKassa
        DB::table('settings')->where('param', 'yandexKassaEnable')->update(array('value' => $request->input('yandexKassaEnable') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'yandexKassaShopId')->update(array('value' => $request->input('yandexKassaShopId') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'yandexKassaClientAppKey')->update(array('value' => $request->input('yandexKassaClientAppKey') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'yandexKassaSecretKey')->update(array('value' => $request->input('yandexKassaSecretKey') ?: "", 'updated_at' => new \DateTime()));
        // instamojo
        DB::table('settings')->where('param', 'instamojoEnable')->update(array('value' => $request->input('instamojoEnable') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'instamojoSandBoxMode')->update(array('value' => $request->input('instamojoSandBoxMode') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'instamojoApiKey')->update(array('value' => $request->input('instamojoApiKey') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'instamojoPrivateToken')->update(array('value' => $request->input('instamojoPrivateToken') ?: "", 'updated_at' => new \DateTime()));
        // payMob
        DB::table('settings')->where('param', 'payMobEnable')->update(array('value' => $request->input('payMobEnable') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'payMobApiKey')->update(array('value' => $request->input('payMobApiKey') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'payMobFrame')->update(array('value' => $request->input('payMobFrame') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'payMobIntegrationId')->update(array('value' => $request->input('payMobIntegrationId') ?: "", 'updated_at' => new \DateTime()));
        // MercadoPago
        DB::table('settings')->where('param', 'MercadoPagoEnable')->update(array('value' => $request->input('MercadoPagoEnable') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'MercadoPagoAccessToken')->update(array('value' => $request->input('MercadoPagoAccessToken') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'MercadoPagoPublicKey')->update(array('value' => $request->input('MercadoPagoPublicKey') ?: "", 'updated_at' => new \DateTime()));
        // FlutterWave
        DB::table('settings')->where('param', 'FlutterWaveEnable')->update(array('value' => $request->input('FlutterWaveEnable') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'FlutterWaveEncryptionKey')->update(array('value' => $request->input('FlutterWaveEncryptionKey') ?: "", 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', 'FlutterWavePublicKey')->update(array('value' => $request->input('FlutterWavePublicKey') ?: "", 'updated_at' => new \DateTime()));

        return response()->json(['ret'=>0, 'text' => Lang::get(466)]); // "Payments Methods Saved"
    }

    public function currencies(Request $request){
        $default_currencyCode = DB::table('settings')->where('param', '=', "default_currencyCode")->get()->first()->value;
        $currencies = DB::table('currencies')->get();
        $rightSymbol = DB::table('settings')->where('param', '=', "rightSymbol")->get()->first()->value;
        return view('currencies', ['currencies' => $currencies, 'default_currencyCode' => $default_currencyCode, 'rightSymbol' => $rightSymbol]);
    }

    public function currencyadd(Request $request){
        $name = $request->input('name') ?: "";
        $code = $request->input('code') ?: "";
        $symbol = $request->input('symbol') ?: "";
        $digits = $request->input('digits') ?: 2;

        Logging::log2("Settings->Currency Add ", "Name: " . $name);

        $values = array('name' => $name, 'code' => $code, 'symbol' => $symbol, 'digits' => $digits, 'created_at' => new \DateTime(), 'updated_at' => new \DateTime());
        DB::table('currencies')->insert($values);
        return \Redirect::to('/currencies');
    }

    public function currencydelete(Request $request){
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('id');

        $name = DB::table('currencies')->where('id',$id)->get()->first()->name;
        if (Settings::isDemoMode()){
            Logging::log3("Currencies->Delete", "Name: " . $name, "Abort! This is demo mode");
            return response()->json(['ret'=>false, 'text'=>Lang::get(467)]); // 'This is demo app. You can\'t change this section'
        }

        Logging::log2("Currencies->Delete", "Name: " . $name);

        DB::table('currencies')
            ->where('id',$id)
            ->delete();

        return response()->json(['ret'=>true]);

    }

    public function currencyedit(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('editid') ?: 0;
        $name = $request->input('name') ?: "";
        $code = $request->input('code') ?: "";
        $symbol = $request->input('symbol') ?: "";
        $digits = $request->input('digits') ?: 0;

        $values = array('name' => $name, 'code' => $code, 'symbol' => $symbol, 'digits' => $digits, 'updated_at' => new \DateTime());

        DB::table('currencies')
            ->where('id',$id)
            ->update($values);

        Logging::log2("Currency->Edit", "Name: " . $name);

        return \Redirect::to('/currencies');
    }

    public function currencyChange(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $id = $request->input('currency') ?: 0;
        $code = DB::table('currencies')->where('id',$id)->get()->first()->code;
        $symbol = DB::table('currencies')->where('id',$id)->get()->first()->symbol;

        DB::table('settings')->where('param', '=', "default_currencyCode")->update(array('value' => $code, 'updated_at' => new \DateTime()));
        DB::table('settings')->where('param', '=', "default_currencies")->update(array('value' => $symbol, 'updated_at' => new \DateTime()));

        Logging::log2("Currency->Set Default", "New corrency code: " . $code);
        return \Redirect::to('/currencies');
    }

    public function setRightSymbol(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');

        $value = $request->input('value') ?: "";
        if ($value != "true")
            $value = "false";

        DB::table('settings')->where('param', '=', "rightSymbol")->update(array('value' => $value, 'updated_at' => new \DateTime()));

        Logging::log2("Currency->Set Symbol right: ", $value);
        return \Redirect::to('/currencies');
    }


}
