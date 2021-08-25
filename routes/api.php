<?php

use App\User;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Note: all the routes defined in routes/api.php will be prefixed with api/ by
//Laravel and auth routes are prefixed with auth. So our /login route is
// actually /api/auth/login

Route::group(['middleware' => 'api','CORS'
], function ($router) {

    Route::post('register', 'api\JWTAuthController@register');
    Route::post('login', 'api\JWTAuthController@login');
    Route::get('category_modulo/{module}','api\ProductCategoryController@getCategoryModule');
    Route::get('category/products/{id}','api\ProductCategoryController@byCategoryProductAll');
    Route::post('sendEmail', 'api\MailController@sendEmail');
    Route::post('sendEmailMessenger', 'api\MailController@sendEmailMessenger');
    Route::get('register/verify/{code}', 'api\JWTAuthController@verify');
    Route::post('product/find','api\ProductController@buscador');

});

Route::group(['middleware' => 'auth:api','CORS',
              'prefix' => 'auth'

], function ($router) {

    //Users ****
    Route::post('logout', 'api\JWTAuthController@logout');
    Route::post('refresh', 'api\JWTAuthController@refresh');
    Route::post('user', 'api\JWTAuthController@getAuthUser');
    Route::resource('users','api\JWTAuthController');

    //Products ****
    Route::resource('product','api\ProductController');
    Route::post('product/update/{product}','api\ProductController@update');

    //UserPorduct_Cart ****
    Route::get('/cart/{userId}','api\UserProductController@getContent');
    Route::post('/cart/add','api\UserProductController@addItem');
    Route::get('/cart/count/{userId}','api\UserProductController@countCart');
    Route::get('/cart/totalprice/{userId}','api\UserProductController@TotalPricetCart');
    Route::put('/cart/update','api\UserProductController@updateCart');
    Route::get('/cart/clear/{userId}','api\UserProductController@clearCart');
    Route::post('/cart/delete/','api\OrderController@deleteProductCart');
    Route::post('/cart/delete/','api\UserProductController@deleteProductCart');
    Route::post('emptyCart','api\UserProductController@emptyCart');


    //Category ****
    Route::resource('category','api\ProductCategoryController');
    Route::get('productOrderByCategory','api\ProductCategoryController@categoryProductAll');

    //Messenger ****
    Route::resource('messenger','api\MessengerController');
    Route::post('messenger/update/{messenger}','api\MessengerController@update');
    Route::post('messenger/odersAsignedMessenger','api\MessengerController@odersAsigned');
    Route::post('messenger/odersCancelEntregadas','api\MessengerController@ordersFinished');
    Route::post('messenger/odersActive','api\MessengerController@ordersActive');

    //Contact ****
    Route::resource('contact','api\ContactController');
    Route::post('contact/sendMail','api\ContactController@SendMailFormContact');

    //Order ****
    Route::resource('order','api\OrderController');
    Route::get('orderProducts/{order}','api\OrderController@orderProduct');
    Route::get('odersCancelEntregadas/{userId}','api\OrderController@ordersFinished');
    Route::get('odersActive/{userId}','api\OrderController@ordersActive');

    //OrderExpress
    Route::resource('orderExpress','api\OrderExpressController');
    Route::get('orderDetails/{order}','api\OrderExpressController@orderDetails');
    Route::post('deleteExpress','api\OrderExpressController@destroyExpress');

     //OrderMototaxi
     Route::resource('orderMototaxi','api\OrderMototaxiController');
     Route::get('orderDetailsMoto/{order}','api\OrderMototaxiController@orderDetailsMoto');
     Route::post('deleteMototaxi','api\OrderMototaxiController@destroyMototaxi');

     //OrderLoquesea
    Route::resource('orderloquesea','api\OrdersLoqueseaController');
    Route::get('orderDetails/{order}','api\OrdersLoqueseaController@orderDetails');
    Route::post('deleteloquesea','api\OrdersLoqueseaController@destroyLoquesea');

    //Country ****
    Route::resource('country','api\CountryController');
    Route::get('country/{country}/province','api\CountryController@countryPorvinces');

    //Municipie ****
    Route::resource('municipie','api\MunicipieController');
    Route::get('municipie/{municipie}/user','api\MunicipieController@municipieUsers');

    //Province ****
    Route::resource('province','api\ProvinceController');

    //Locality
    Route::resource('locality','api\LocalityController');
    Route::get('localities/{municipie_id}','api\LocalityController@localitiesMunicipie');

    //DeliveryCosts
    Route::resource('delivery','api\DeliveryCostController');
    Route::post('deliveryCost','api\DeliveryCostController@transportationCost');
    Route::get('deliveryLocation/{module}','api\DeliveryCostController@locationByModule');

    //Rols
    Route::get('admin/odersCancelEntregadas','api\RolController@ordersFinished');
    Route::get('admin/odersActive','api\RolController@ordersActive');
    Route::get('usersRol/{rol}', 'api\RolController@findUsersByRol');

    //Locations
    Route::resource('location','api\LocationController');
    Route::post('location/locationByOrder','api\LocationController@locationsOrder');

    //AplicationJobs
    Route::resource('appJob', 'api\ApplicationJobController');
    Route::get('aprove','api\ApplicationJobController@applicationJobsAprove');
    Route::post('deleteAplicationJob','api\ApplicationJobController@destroyAplicationJob');






});
