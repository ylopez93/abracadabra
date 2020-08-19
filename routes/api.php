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

    Route::post('logout', 'api\JWTAuthController@logout');
    Route::post('refresh', 'api\JWTAuthController@refresh');
    Route::post('user', 'api\JWTAuthController@getAuthUser');
    Route::resource('product','api\ProductController');
    Route::resource('category','api\ProductCategoryController');
    Route::resource('messenger','api\MessengerController');
    Route::resource('contact','api\ContactController');
    Route::resource('order','api\OrderController');
    Route::resource('country','api\CountryController');
    Route::resource('municipie','api\MunicipieController');
    //Route::get('municipie/{municipie}/user','api\MunicipieController@municipieUsers');
    Route::get('country/{country}/province','api\CountryController@countryPorvinces');
    Route::get('product/{category}/category','api\ProductCategoryController@categoryProduct');
    Route::get('category/products','api\ProductCategoryController@categoryProductAll');

});
