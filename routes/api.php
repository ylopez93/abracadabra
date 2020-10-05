<?php

use App\User;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

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

});

Route::group(['middleware' => 'auth:api','CORS',
              'prefix' => 'auth'

], function ($router) {

    //Users
    Route::post('logout', 'api\JWTAuthController@logout');
    Route::post('refresh', 'api\JWTAuthController@refresh');
    Route::post('user', 'api\JWTAuthController@getAuthUser');

    //Products
    Route::resource('product','api\ProductController');
    Route::post('product/update/{product}','api\ProductController@update');
    Route::get('product/{category}/category','api\ProductCategoryController@categoryProduct');

    //UserPorduct_Cart
    Route::get('/cart/{userId}','api\UserProductController@getContent');
    Route::post('/cart/add','api\UserProductController@addItem');
    Route::get('/cart/count/{userId}','api\UserProductController@countCart');
    Route::get('/cart/totalprice/{userId}','api\UserProductController@TotalPricetCart');
    Route::put('/cart/update','api\UserProductController@updateCart');
    Route::post('/cart/delete','api\UserProductController@deleteProductCart');
    Route::get('/cart/clear/{userId}','api\OrderController@clearCart');


    //Category
    Route::get('category/products','api\ProductCategoryController@categoryProductAll');
    Route::resource('category','api\ProductCategoryController');
    Route::post('category/update/{category}','api\ProductCategoryController@update');
    Route::get('category_modulo/{module}','api\ProductCategoryController@getCategoryModule');
    Route::get('category/products/{category}','api\ProductCategoryController@byCategoryProductAll');


    //Messenger
    Route::resource('messenger','api\MessengerController');
    Route::post('messenger/update/{messenger}','api\MessengerController@update');

    //Contact
    Route::resource('contact','api\ContactController');

    //Order
    Route::resource('order','api\OrderController');

    //Country
    Route::resource('country','api\CountryController');
    Route::get('country/{country}/province','api\CountryController@countryPorvinces');

    //Municipie
    Route::resource('municipie','api\MunicipieController');
    Route::get('municipie/{municipie}/user','api\MunicipieController@municipieUsers');

    //Province
    Route::resource('province','api\ProvinceController');

});
