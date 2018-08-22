<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
//Route::group(['namespace' => 'Api'], function() {

    Route::post('auth/register', 'Api\LoginController@register');
    Route::post('auth/login', 'Api\LoginController@login');
    Route::post('refresh_token', 'Api\LoginController@refreshToken');
    Route::get('agent_logins', 'Api\LoginController@getAgentLogins');
    Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('user', 'Api\LoginController@getAuthUser');
    Route::post('pushdata','Api\PushController@store');
    Route::post('logout', 'Api\LoginController@logout');
       
    //Pull data Api Routes
    Route::post('agentpulldata', 'Api\PullDataController@getBillInfo');
    Route::post('agentwisereport', 'Api\InspectorReportController@getReport');
    });
	
//});
  