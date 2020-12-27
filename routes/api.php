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
*/

Route::options('/{any}', function() {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With');
})->where('any', '.*');

Route::group(['namespace' => 'Api', 'prefix' => 'front', 'as' => 'front.' ], function() {
    Route::get('/initialize', ['as' => 'initialize', 'uses' => 'FrontController@initialize']);
    Route::get('/status', ['as' => 'status', 'uses' => 'FrontController@status']);
    Route::get('/attack', ['as' => 'attack', 'uses' => 'FrontController@attack']);
    Route::get('/result', ['as' => 'result', 'uses' => 'FrontController@result']);
});

Route::group(['namespace' => 'Api', 'prefix' => 'processing', 'as' => 'processing.' ], function() {
    Route::post('/start', ['as' => 'start', 'uses' => 'ProcessingController@start']);
    Route::post('/data', ['as' => 'data', 'uses' => 'ProcessingController@data']);
    Route::post('/result', ['as' => 'result', 'uses' => 'ProcessingController@result']);
    Route::post('/end', ['as' => 'end', 'uses' => 'ProcessingController@end']);
});

Route::group(['namespace' => 'Api', 'prefix' => 'admin', 'as' => 'admin.' ], function() {
    Route::get('/result', ['as' => 'result', 'uses' => 'AdminController@result']);
});
