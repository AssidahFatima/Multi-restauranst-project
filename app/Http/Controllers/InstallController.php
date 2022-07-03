<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstallController extends Controller
{
    public static function install2(Request $request)
    {

    }

    public static function install() // Request $request
    {
        Artisan::call('key:generate');
        echo Artisan::output();
        echo "<br>";

        Artisan::call('migrate');
        echo Artisan::output();
        echo "<br>";

        //Install passport migration
        Artisan::call('migrate', ['--path' => 'vendor/laravel/passport/database/migrations']);
        echo Artisan::output();
        echo "<br>";
        Artisan::call('passport:install');  //or  echo shell_exec('php ../artisan passport:install');
        echo Artisan::output();
        echo "<br>";

        /*
         * ERROR:
         * The command "passport:install" does not exist.
         *
         * add to file app/Console/Kernel.php
              protected $commands = [
                \Laravel\Passport\Console\InstallCommand::class,
                \Laravel\Passport\Console\KeysCommand::class,
                \Laravel\Passport\Console\ClientCommand::class,
               ];
         */


        Artisan::call('db:seed');
        echo Artisan::output();
        echo "<br>";
    }

    public static function update()
    {
        if (!Schema::hasTable('settings')) {
            InstallController::install();
            DB::table('settings')->insert(['param' => 'install', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        }

        if (!Schema::hasColumn('restaurants', 'openTimeMonday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN openTimeMonday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'closeTimeMonday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN closeTimeMonday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'openTimeTuesday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN openTimeTuesday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'closeTimeTuesday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN closeTimeTuesday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'openTimeWednesday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN openTimeWednesday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'closeTimeWednesday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN closeTimeWednesday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'openTimeThursday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN openTimeThursday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'closeTimeThursday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN closeTimeThursday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'openTimeFriday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN openTimeFriday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'closeTimeFriday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN closeTimeFriday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'openTimeSaturday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN openTimeSaturday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'closeTimeSaturday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN closeTimeSaturday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'openTimeSunday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN openTimeSunday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'closeTimeSunday'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN closeTimeSunday VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'area'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN area INT(11)");

        if (!Schema::hasColumn('orders', 'curbsidePickup'))
            DB::statement("ALTER TABLE orders ADD COLUMN curbsidePickup VARCHAR(255)");
        if (!Schema::hasColumn('orders', 'arrived'))
            DB::statement("ALTER TABLE orders ADD COLUMN arrived VARCHAR(255)");

        if (!Schema::hasTable('chat')) {
            Schema::create('chat', function ($table) {
                $table->id();
                $table->timestamps();
                $table->integer('user');
                $table->string('text');
                $table->string('author');
                $table->string('delivered');
                $table->string('read');
            });
        }

        if (!Schema::hasTable('wallet')) {
            Schema::create('wallet', function ($table) {
                $table->id();
                $table->timestamps();
                $table->integer('user');
                $table->decimal('balans', 15, 2);
            });
        }

        if (!Schema::hasTable('walletlog')) {
            Schema::create('walletlog', function ($table) {
                $table->id();
                $table->timestamps();
                $table->integer('user');
                $table->decimal('amount', 15, 2);
                $table->decimal('total', 15, 2);
                $table->boolean('arrival');
                $table->string('comment');
            });
        }

        if (count(DB::table('settings')->where("param", 'StripeEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'StripeEnable', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'stripeKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'stripeKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'stripeSecretKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'stripeSecretKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'razEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'razEnable', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'razKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'razKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'razName')->get()) == 0)
            DB::table('settings')->insert(['param' => 'razName', 'value' => 'My Company Name', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'cashEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'cashEnable', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'default_currencies')->get()) == 0)
            DB::table('settings')->insert(['param' => 'default_currencies', 'value' => '$', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'default_currencyCode')->get()) == 0)
            DB::table('settings')->insert(['param' => 'default_currencyCode', 'value' => 'USD', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'rightSymbol')->get()) == 0)
            DB::table('settings')->insert(['param' => 'rightSymbol', 'value' => 'false', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'firebase_key')->get()) == 0)
            DB::table('settings')->insert(['param' => 'firebase_key', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'radius')->get()) == 0)
            DB::table('settings')->insert(['param' => 'radius', 'value' => '3', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (count(DB::table('settings')->where("param", 'shadow')->get()) == 0)
            DB::table('settings')->insert(['param' => 'shadow', 'value' => '10', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row1')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row1', 'value' => 'search', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row2')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row2', 'value' => 'topr', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row3')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row3', 'value' => 'topf', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row4')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row4', 'value' => 'cat', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row5')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row5', 'value' => 'pop', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row6')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row6', 'value' => 'nearyou', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row7')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row7', 'value' => 'review', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row1visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row1visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row2visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row2visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row3visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row3visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row4visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row4visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row5visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row5visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row6visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row6visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row7visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row7visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'mainColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'mainColor', 'value' => 'ff668798', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'iconColorWhiteMode')->get()) == 0)
            DB::table('settings')->insert(['param' => 'iconColorWhiteMode', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'iconColorDarkMode')->get()) == 0)
            DB::table('settings')->insert(['param' => 'iconColorDarkMode', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'restaurantCardWidth')->get()) == 0)
            DB::table('settings')->insert(['param' => 'restaurantCardWidth', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'restaurantCardHeight')->get()) == 0)
            DB::table('settings')->insert(['param' => 'restaurantCardHeight', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'restaurantBackgroundColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'restaurantBackgroundColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'restaurantCardTextSize')->get()) == 0)
            DB::table('settings')->insert(['param' => 'restaurantCardTextSize', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'restaurantCardTextColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'restaurantCardTextColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'restaurantTitleColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'restaurantTitleColor', 'value' => 'FFFFFF', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'dishesTitleColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'dishesTitleColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'dishesBackgroundColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'dishesBackgroundColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'dishesCardHeight')->get()) == 0)
            DB::table('settings')->insert(['param' => 'dishesCardHeight', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'oneInLine')->get()) == 0)
            DB::table('settings')->insert(['param' => 'oneInLine', 'value' => 'false', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'categoriesTitleColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'categoriesTitleColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'categoriesBackgroundColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'categoriesBackgroundColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'categoryCardWidth')->get()) == 0)
            DB::table('settings')->insert(['param' => 'categoryCardWidth', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'categoryCardHeight')->get()) == 0)
            DB::table('settings')->insert(['param' => 'categoryCardHeight', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'searchBackgroundColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'searchBackgroundColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'reviewTitleColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'reviewTitleColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'reviewBackgroundColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'reviewBackgroundColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'categoryCardCircle')->get()) == 0)
            DB::table('settings')->insert(['param' => 'categoryCardCircle', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'darkMode')->get()) == 0)
            DB::table('settings')->insert(['param' => 'darkMode', 'value' => 'false', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'topRestaurantCardHeight')->get()) == 0)
            DB::table('settings')->insert(['param' => 'topRestaurantCardHeight', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'bottomBarType')->get()) == 0)
            DB::table('settings')->insert(['param' => 'bottomBarType', 'value' => 'type1', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'bottomBarColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'bottomBarColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'titleBarColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'titleBarColor', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'mapapikey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'mapapikey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'ordersNotifications')->get()) == 0)
            DB::table('settings')->insert(['param' => 'ordersNotifications', 'value' => '0', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'walletEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'walletEnable', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'typeFoods')->get()) == 0)
            DB::table('settings')->insert(['param' => 'typeFoods', 'value' => 'type2', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'version')->get()) == 0)
            DB::table('settings')->insert(['param' => 'version', 'value' => '1.5.0', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        // 18.11.2020
        if (count(DB::table('settings')->where("param", 'payPalEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payPalEnable', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'payPalSandBox')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payPalSandBox', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'payPalClientId')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payPalClientId', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'payPalSecret')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payPalSecret', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'payStackEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payStackEnable', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'payStackKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payStackKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'distanceUnit')->get()) == 0)
            DB::table('settings')->insert(['param' => 'distanceUnit', 'value' => 'km', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'appLanguage')->get()) == 0)
            DB::table('settings')->insert(['param' => 'appLanguage', 'value' => '1', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        // 19.11.2020
        if (!Schema::hasColumn('orders', 'couponName'))
            DB::statement("ALTER TABLE orders ADD COLUMN couponName VARCHAR(255)");
        // 26.11.2020
        if (!Schema::hasColumn('orders', 'send'))
            DB::statement("ALTER TABLE orders ADD COLUMN send INT(1)");

        // yandex kassa
        if (count(DB::table('settings')->where("param", 'yandexKassaEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'yandexKassaEnable', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'yandexKassaShopId')->get()) == 0)
            DB::table('settings')->insert(['param' => 'yandexKassaShopId', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'yandexKassaClientAppKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'yandexKassaClientAppKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'yandexKassaSecretKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'yandexKassaSecretKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        // instamojo
        if (count(DB::table('settings')->where("param", 'instamojoEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'instamojoEnable', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'instamojoSandBoxMode')->get()) == 0)
            DB::table('settings')->insert(['param' => 'instamojoSandBoxMode', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'instamojoApiKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'instamojoApiKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'instamojoPrivateToken')->get()) == 0)
            DB::table('settings')->insert(['param' => 'instamojoPrivateToken', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        // permission
        if (count(DB::table('permissions')->where("value", 'Food::TopFoods:Delete')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Food::TopFoods:Delete',
                'role1' => true,        // admin
                'role2' => true,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('permissions')->where("value", 'Food::TopFoods:Add')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Food::TopFoods:Add',
                'role1' => true,        // admin
                'role2' => true,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('permissions')->where("value", 'Restaurants::TopRestaurants::Delete')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Restaurants::TopRestaurants::Delete',
                'role1' => true,        // admin
                'role2' => true,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('permissions')->where("value", 'Restaurants::TopRestaurants::Add')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Restaurants::TopRestaurants::Add',
                'role1' => true,        // admin
                'role2' => true,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('permissions')->where("value", 'Coupons::Create')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Coupons::Create',
                'role1' => true,        // admin
                'role2' => true,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('permissions')->where("value", 'Coupons::Edit')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Coupons::Edit',
                'role1' => true,        // admin
                'role2' => true,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('permissions')->where("value", 'Coupons::Delete')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Coupons::Delete',
                'role1' => true,        // admin
                'role2' => true,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('permissions')->where("value", 'Chat::View')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Chat::View',
                'role1' => true,        // admin
                'role2' => true,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('permissions')->where("value", 'Wallet::View')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Wallet::View',
                'role1' => true,        // admin
                'role2' => true,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        // clear log (more then 30 days)
        $now = new \DateTime();
        $datetime = $now->modify('-30 day');
        DB::table('logging')->where('updated_at', '<', $datetime)->delete();

        // time zone - default UTC
        if (count(DB::table('settings')->where("param", 'timezone')->get()) == 0)
            DB::table('settings')->insert(['param' => 'timezone', 'value' => 'UTC', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        // set Time Zone
        $timezone = DB::table('settings')->where('param', '=', "timezone")->get()->first()->value;
        date_default_timezone_set($timezone);

        // subcategories
        if (!Schema::hasColumn('categories', 'parent'))
            DB::statement("ALTER TABLE categories ADD COLUMN parent int(11) DEFAULT 0");

        // market version 20.12.2020
        if (!Schema::hasTable('docs')) {
            Schema::create('docs', function ($table) {
                $table->id();
                $table->timestamps();
                $table->string('param');
                $table->longText('value');
            });
            DB::table('docs')->insert(['param' => 'copyright', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'copyright_text', 'value' => '2021 Markets Delivery. All Rights Reserved', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'about', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'about_text', 'value' => 'About us', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'delivery', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'delivery_text', 'value' => 'Delivery info', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'privacy', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'privacy_text', 'value' => 'Privacy Policy', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'terms', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'terms_text', 'value' => 'Terms and Condition', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        }
        if (count(DB::table('docs')->where("param", 'refund_text')->get()) == 0){
            DB::table('docs')->insert(['param' => 'refund', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
            DB::table('docs')->insert(['param' => 'refund_text', 'value' => 'Refund Text', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        }
        if (count(DB::table('docs')->where("param", 'refund_text_name')->get()) == 0)
            DB::table('docs')->insert(['param' => 'refund_text_name', 'value' => 'Refund Policy', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'about_text_name')->get()) == 0)
            DB::table('docs')->insert(['param' => 'about_text_name', 'value' => 'About Us', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'delivery_text_name')->get()) == 0)
            DB::table('docs')->insert(['param' => 'delivery_text_name', 'value' => 'Delivery info', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'privacy_text_name')->get()) == 0)
            DB::table('docs')->insert(['param' => 'privacy_text_name', 'value' => 'Privacy Policy', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'terms_text_name')->get()) == 0)
            DB::table('docs')->insert(['param' => 'terms_text_name', 'value' => 'Terms and Condition', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'about_ca')->get()) == 0)
            DB::table('docs')->insert(['param' => 'about_ca', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'delivery_ca')->get()) == 0)
            DB::table('docs')->insert(['param' => 'delivery_ca', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'privacy_ca')->get()) == 0)
            DB::table('docs')->insert(['param' => 'privacy_ca', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'terms_ca')->get()) == 0)
            DB::table('docs')->insert(['param' => 'terms_ca', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'refund_ca')->get()) == 0)
            DB::table('docs')->insert(['param' => 'refund_ca', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('docs')->where("param", 'copyright_ca')->get()) == 0)
            DB::table('docs')->insert(['param' => 'copyright_ca', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        //
        if (count(DB::table('settings')->where("param", 'siteLang')->get()) == 0)
            DB::table('settings')->insert(['param' => 'siteLang', 'value' => 'langEng', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        // social network sign in ("google", "email")
        if (!Schema::hasColumn('users', 'typeReg'))
            DB::statement("ALTER TABLE users ADD COLUMN typeReg VARCHAR(255) DEFAULT 'email'");

        if (count(DB::table('permissions')->where("value", 'Users::ChangePassword')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Users::ChangePassword',
                'role1' => true,        // admin
                'role2' => false,        // manager
                'role3' => false,        // driver
                'role4' => false,        // client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (count(DB::table('settings')->where("param", 'app_type')->get()) == 0)
            DB::table('settings')->insert(['param' => 'app_type', 'value' => 'restaurants', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'medialib_type')->get()) == 0)
            DB::table('settings')->insert(['param' => 'medialib_type', 'value' => 'medium', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (count(DB::table('permissions')->where("value", 'Documents::View')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Documents::View',
                'role1' => true, 		// admin
                'role2' => true, 		// manager
                'role3' => true, 		// driver
                'role4' => true, 		// client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (count(DB::table('permissions')->where("value", 'Banners::View')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Banners::View',
                'role1' => true, 		// admin
                'role2' => true, 		// manager
                'role3' => false, 		// driver
                'role4' => false, 		// client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (!Schema::hasTable('banners')) {
            Schema::create('banners', function ($table) {
                $table->id();
                $table->timestamps();
                $table->string('name');
                $table->integer('imageid');
                $table->string('type');
                $table->longText('details');
                $table->boolean('visible');
                $table->string('position');
            });
        }

        if (count(DB::table('permissions')->where("value", 'Banners::Edit')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Banners::Edit',
                'role1' => true, 		// admin
                'role2' => true, 		// manager
                'role3' => false, 		// driver
                'role4' => false, 		// client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (count(DB::table('permissions')->where("value", 'Banners::Delete')->get()) == 0)
            DB::table('permissions')->insert(['value' => 'Banners::Delete',
                'role1' => true, 		// admin
                'role2' => true, 		// manager
                'role3' => false, 		// driver
                'role4' => false, 		// client
                'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (count(DB::table('settings')->where("param", 'banner1CardHeight')->get()) == 0)
            DB::table('settings')->insert(['param' => 'banner1CardHeight', 'value' => '40', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'banner2CardHeight')->get()) == 0)
            DB::table('settings')->insert(['param' => 'banner2CardHeight', 'value' => '40', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row8')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row8', 'value' => 'banner1', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row9')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row9', 'value' => 'banner2', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row8visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row8visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row9visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row9visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (count(DB::table('settings')->where("param", 'faq_ca')->get()) == 0)
            DB::table('settings')->insert(['param' => 'faq_ca', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        //
        if (count(DB::table('settings')->where("param", 'row10')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row10', 'value' => 'categoryDetails', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row10visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row10visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row11')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row11', 'value' => 'copyright', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'row11visible')->get()) == 0)
            DB::table('settings')->insert(['param' => 'row11visible', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        //
        if (count(DB::table('settings')->where("param", 'googleLogin_ca')->get()) == 0)
            DB::table('settings')->insert(['param' => 'googleLogin_ca', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'facebookLogin_ca')->get()) == 0)
            DB::table('settings')->insert(['param' => 'facebookLogin_ca', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        //
        if (count(DB::table('settings')->where("param", 'defaultLat')->get()) == 0)
            DB::table('settings')->insert(['param' => 'defaultLat', 'value' => '48.836010', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'defaultLng')->get()) == 0)
            DB::table('settings')->insert(['param' => 'defaultLng', 'value' => '2.331359', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        //

        //
        if (!Schema::hasTable('address')) {
            Schema::create('address', function ($table) {
                $table->id();
                $table->timestamps();
                $table->integer('user');
                $table->longText('text');
                $table->string('lat');
                $table->string('lng');
                $table->string('type');
                $table->string('default');
            });
        }
        //
        if (count(DB::table('settings')->where("param", 'otp')->get()) == 0)
            DB::table('settings')->insert(['param' => 'otp', 'value' => 'false', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'curbsidePickup')->get()) == 0)
            DB::table('settings')->insert(['param' => 'curbsidePickup', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'delivering')->get()) == 0)
            DB::table('settings')->insert(['param' => 'delivering', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'coupon')->get()) == 0)
            DB::table('settings')->insert(['param' => 'coupon', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'deliveringTime')->get()) == 0)
            DB::table('settings')->insert(['param' => 'deliveringTime', 'value' => 'true', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (!Schema::hasTable('rproducts')) {
            Schema::create('rproducts', function ($table) {
                $table->id();
                $table->timestamps();
                $table->integer('food');
                $table->integer('rp');
            });
        }
        //
        if (!Schema::hasColumn('restaurants', 'minAmount'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN minAmount DECIMAL(15, 2)");

        //

        if (!Schema::hasColumn('foods', 'images'))
            DB::statement("ALTER TABLE foods ADD COLUMN images VARCHAR(255)");
        if (!Schema::hasColumn('restaurants', 'perkm'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN perkm tinyint(1) default 0");
        if (!Schema::hasColumn('orders', 'perkm'))
            DB::statement("ALTER TABLE orders ADD COLUMN perkm tinyint(1) default 0 ");
        if (!Schema::hasColumn('users', 'lat'))
            DB::statement("ALTER TABLE users ADD COLUMN lat VARCHAR(255) default ''");
        if (!Schema::hasColumn('users', 'lng'))
            DB::statement("ALTER TABLE users ADD COLUMN lng VARCHAR(255) default ''");
        if (!Schema::hasColumn('users', 'speed'))
            DB::statement("ALTER TABLE users ADD COLUMN speed VARCHAR(255) default ''");

        if (!Schema::hasTable('variants')) {
            Schema::create('variants', function ($table) {
                $table->id();
                $table->timestamps();
                $table->integer('food');
                $table->string('name');
                $table->integer('imageid')->default(0);
                $table->decimal('price', 15, 2);
                $table->decimal('dprice', 15, 2)->default(0);
            });
        }
        //
        if (count(DB::table('settings')->where("param", 'web_mainColor')->get()) == 0)
            DB::table('settings')->insert(['param' => 'web_mainColor', 'value' => '029600', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'web_mainColorHover')->get()) == 0)
            DB::table('settings')->insert(['param' => 'web_mainColorHover', 'value' => '66b88c', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'web_radius')->get()) == 0)
            DB::table('settings')->insert(['param' => 'web_radius', 'value' => '6', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'web_logo')->get()) == 0)
            DB::table('settings')->insert(['param' => 'web_logo', 'value' => '99', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('image_uploads')->where("id", '99')->get()) == 0)
            DB::table('image_uploads')->insert(['id' => 99, 'filename' => 'weblogo.png', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);

        if (!Schema::hasColumn('orders', 'view'))
            DB::statement("ALTER TABLE orders ADD COLUMN view VARCHAR(255) DEFAULT 'false'");

        // FlutterWave
        if (count(DB::table('settings')->where("param", 'FlutterWaveEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'FlutterWaveEnable', 'value' => 'false', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'FlutterWaveEncryptionKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'FlutterWaveEncryptionKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'FlutterWavePublicKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'FlutterWavePublicKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        // MercadoPago
        if (count(DB::table('settings')->where("param", 'MercadoPagoEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'MercadoPagoEnable', 'value' => 'false', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'MercadoPagoAccessToken')->get()) == 0)
            DB::table('settings')->insert(['param' => 'MercadoPagoAccessToken', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'MercadoPagoPublicKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'MercadoPagoPublicKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        // PayMob
        if (count(DB::table('settings')->where("param", 'payMobEnable')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payMobEnable', 'value' => 'false', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'payMobApiKey')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payMobApiKey', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'payMobFrame')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payMobFrame', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'payMobFrame')->get()) > 2){
            $value2 = DB::table('settings')->where('param', "payMobFrame")->get()->first();
            $value2 = $value2->value;
            DB::table('settings')->where('param', "payMobFrame")->delete();
            DB::table('settings')->insert(['param' => 'payMobFrame', 'value' => $value2, 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        }
        if (count(DB::table('settings')->where("param", 'payMobIntegrationId')->get()) == 0)
            DB::table('settings')->insert(['param' => 'payMobIntegrationId', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        // share
        if (count(DB::table('settings')->where("param", 'shareAppGooglePlay')->get()) == 0)
            DB::table('settings')->insert(['param' => 'shareAppGooglePlay', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (count(DB::table('settings')->where("param", 'shareAppAppStore')->get()) == 0)
            DB::table('settings')->insert(['param' => 'shareAppAppStore', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        // city
        if (count(DB::table('settings')->where("param", 'city')->get()) == 0)
            DB::table('settings')->insert(['param' => 'city', 'value' => '', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(),]);
        if (!Schema::hasColumn('restaurants', 'city'))
            DB::statement("ALTER TABLE restaurants ADD COLUMN city TEXT NOT NULL");

        //
        DB::table('settings')->where("param", 'version')->update(['value' => '2.0.0', 'updated_at' => new \DateTime(),]);

    }
}
