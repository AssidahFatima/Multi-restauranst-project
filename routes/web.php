<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear-cache', function() {
    $text = "Start...";
    Artisan::call('cache:clear');
    $text = $text . Artisan::output();
    Artisan::call('route:clear');
    $text = $text . Artisan::output();
    Artisan::call('config:clear');
    $text = $text . Artisan::output();
    Artisan::call('view:clear');
    $text = $text . Artisan::output();
    return $text;
});

Route::get('/phpinfo', function() {
    echo phpinfo();
});

Route::get('/install', 'InstallController@install2');

Route::get('/logout', 'Auth\LoginController@logout');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Auth::routes();
Route::get('/', 'Auth\LoginController@showLoginForm')->name('/');
Route::get('home','HomeController@index')->name('home');

// foods
Route::get('foods','FoodController@load');
Route::post('foodadd','FoodController@add');
Route::post('fooddelete','FoodController@delete');
Route::post('foodGetInfo','FoodController@foodGetInfo');
Route::post('foodsGoPage','FoodController@foodsGoPage');

Route::post('productVariantsAdd','FoodController@productVariantsAdd');
Route::post('productVariantsDelete','FoodController@productVariantsDelete');
Route::post('rProductAdd','FoodController@rProductAdd');
Route::post('rProductsDelete','FoodController@rProductsDelete');

// categories
Route::get('categories','CategoriesController@load');
Route::post('categoriesadd','CategoriesController@add');
Route::post('categoryGoPage','CategoriesController@categoryGoPage');
Route::post('categorydelete','CategoriesController@delete');
Route::post('categoryGetInfo','CategoriesController@categoryGetInfo');
// extras
Route::get('extras','ExtrasController@load');
Route::post('extrasgroupadd','ExtrasController@add');
Route::post('extrasgroupdelete','ExtrasController@delete');
Route::post('extrasgroupedit','ExtrasController@edit');
Route::post('extrasadd','ExtrasController@addextras');
Route::post('extrasdelete','ExtrasController@deleteextras');
Route::post('extrasedit','ExtrasController@editextras');
// foods reviews
Route::get('foodsreviews','FoodReviewsController@load');
Route::post('foodreviewsadd','FoodReviewsController@add');
Route::post('foodreviewsdelete','FoodReviewsController@delete');
Route::post('foodreviewsedit','FoodReviewsController@edit');
// nutrition
Route::get('nutrition','NutritionController@load');
Route::post('nutritiongroupadd','NutritionController@add');
Route::post('nutritiongroupdelete','NutritionController@delete');
Route::post('nutritiongroupedit','NutritionController@edit');
Route::post('nutritionadd','NutritionController@addnutrition');
Route::post('nutritiondelete','NutritionController@deletenutrition');
Route::post('nutritionedit','NutritionController@editnutrition');
// top foods
Route::get('topfoods','TopFoodsController@topfoods');
Route::post('topfooddelete','TopFoodsController@topfooddelete');
Route::post('topFoodsAdd','TopFoodsController@topFoodsAdd');

// restaurants
Route::get('restaurants','RestaurantsController@load');
Route::post('restorantsadd','RestaurantsController@add')->name('restorantsadd');
Route::post('restaurantsdelete','RestaurantsController@delete');
Route::post('restaurantsedit','RestaurantsController@edit')->name('restaurantsedit');
Route::get('restaurantreviews','RestaurantsController@loadReviews');
Route::post('restorantsreviewadd','RestaurantsController@addReview');
Route::post('restaurantsreviewdelete','RestaurantsController@deleteReview');
Route::post('restaurantsreviewedit','RestaurantsController@editReview')->name('restaurantsreviewedit');
// top restaurants
Route::get('toprestaurants2','TopRestaurantsController@toprestaurants');
Route::post('topRestaurantsAdd','TopRestaurantsController@topRestaurantsAdd');
Route::post('topRestaurantsDelete','TopRestaurantsController@topRestaurantsDelete');

// users
Route::get('users','UserController@users');
Route::post('useradd','UserController@add');
Route::post('userGetInfo','UserController@userGetInfo');
Route::post('userdelete','UserController@delete');
Route::post('usersGoPage','UserController@usersGoPage');
// roles
Route::get('roles','UserController@roles');
Route::get('permissions','PermissionsController@permissions');
Route::post('permissionSet','PermissionsController@permissionSet');
// drivers
Route::get('drivers','DriversController@load');
Route::post('driversGoPage','DriversController@goPage');


// orders
Route::get('ordersstatuses','OrderStatusesController@load');
Route::get('orders','OrdersController@load')->name('orders');
Route::post('ordersedit','OrdersController@edit');
Route::post('orderdelete','OrdersController@delete');
Route::post('orderDetailsDelete','OrdersController@orderDetailsDelete');
Route::post('orderDetailsAdd','OrdersController@orderDetailsAdd');
Route::post('orderview','OrdersController@orderview');
Route::post('changeStatus','OrdersController@changeStatus');
Route::post('changeDriver','OrdersController@changeDriver');
Route::post('ordersGoPage','OrdersController@ordersGoPage');

// reports
Route::get('mostpopular','ReportsController@mostpopular');
Route::get('mostpurchase','ReportsController@mostpurchase');
Route::get('toprestaurants','ReportsController@toprestaurants');

// settings

// image manager
Route::get('media','ImageUploadController@fileCreate');
Route::post('image/upload/store','ImageUploadController@fileStore');
Route::post('image/delete','ImageUploadController@fileDestroy');
Route::post('imageInfo','ImageUploadController@imageInfo');
Route::get('mediaSetType','ImageUploadController@mediaSetType');
Route::post('getImagesList','ImageUploadController@getImagesList');

// faq
Route::get('faq','FaqController@load');
Route::post('faqadd','FaqController@add');
Route::post('faqedit','FaqController@edit');
Route::post('faqdetete','FaqController@delete');

// settings
Route::get('settings','SettingsController@load');
Route::post('settingschange','SettingsController@change');
Route::get('payments','SettingsController@payments');
Route::post('paymentsSave','SettingsController@paymentsSave');
Route::get('currencies','SettingsController@currencies');
Route::post('currencyadd','SettingsController@currencyadd');
Route::post('currencydelete','SettingsController@currencydelete');
Route::post('currencyedit','SettingsController@currencyedit');
Route::post('currencyChange','SettingsController@currencyChange');
Route::post('setRightSymbol','SettingsController@setRightSymbol');
Route::post('settingsSetLang','SettingsController@settingsSetLang');
//Route::post('savefirebasekey','SettingsController@savefirebasekey');
// settings customer app ui
Route::get('caLayout','UIController@caLayout');
Route::post('caLayout_change','UIController@caLayout_change');
Route::get('caLayoutColors','UIController@caLayoutColors');
Route::post('caLayout_changeColors','UIController@caLayout_changeColors');
Route::get('caTheme','UIController@caTheme');
Route::post('caLayout_changeTheme','UIController@caLayout_changeTheme');
Route::get('caLayoutSizes','UIController@caLayoutSizes');
Route::post('caLayoutSizeChange','UIController@caLayoutSizeChange');

// notifications
Route::get('notify','MessagingController@load');
Route::post('sendmsg','MessagingController@send');
Route::post('sendNotify','MessagingController@sendNotify');

// Logging
Route::get('logging','LoggingController@load');
Route::post('loggingPage','LoggingController@loadPage');

// paypal
Route::get('paypal','PayPalController@getExpressCheckout');

// chat
Route::get('chat','ChatController@chat')->name('chat');
Route::post('chatNewMessage','ChatController@chatNewMessage');
Route::post('getChatMessages','ChatController@getChatMessages');
Route::post('getChatMessagesNewCount','ChatController@getChatMessagesNewCount');
Route::post('chatNewUsers','ChatController@chatNewUsers');

// wallet
Route::get('wallet','WalletController@wallet');
Route::post('walletDetails','WalletController@walletDetails');
Route::post('walletChangeBalans','WalletController@walletChangeBalans');

// documents
Route::get('documents','DocumentsController@load');
Route::post('docsave','DocumentsController@save');

// coupons
Route::get('coupons','CouponsController@coupons');
Route::post('couponsAdd','CouponsController@add');
Route::post('coupondelete','CouponsController@delete');
Route::post('couponedit','CouponsController@edit');

// banners
Route::get('banners','BannersController@load');
Route::post('bannersAdd','BannersController@add');
Route::post('bannersGoPage','BannersController@GoPage');
Route::post('bannerGetInfo','BannersController@GetInfo');
Route::post('bannersDelete','BannersController@delete');

// Web Site Settings
Route::get('webSettings','WebSiteSettingsController@load');
Route::post('webSaveSettings','WebSiteSettingsController@webSaveSettings');
Route::post('webRestoreSettings','WebSiteSettingsController@webRestoreSettings');

// CSV
Route::post('exportCSVOrders','ExportController@orders');
Route::post('exportCSVUsers','ExportController@users');
Route::post('exportCSVProducts','ExportController@products');

// city
Route::get('city','CityController@load');
Route::post('saveCity','CityController@save');
