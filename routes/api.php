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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
// 	Route::post('edit/{id}', 'UserController@userEdit');
// 	Route::post('detail', 'UserController@details');
//     return $request->user();
// });
Route::post('register', 'UserController@registerUser');
Route::post('login', 'UserController@userLogin');
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('detail', 'UserController@details');
});

// Route::post('register', 'UserController@registerUser');
// Route::post('login', 'UserController@userLogin');
// Route::post('edit/{id}', 'UserController@userEdit');
// Route::post('detail', 'UserController@details');
// // Route::get('bunga', 'BungaController@index');

Route::post('registerKaryawan', 'UserController@registerKaryawan');
Route::post('editKaryawan/{id}', 'UserController@updateKaryawan');



Route::resource('bunga', 'BungaController', [
        'names' => [
            'index' => 'indexbunga',
        ]
    ]);

// Route::resource('simpanan', 'SimpananController', [
//         'names' => [
//             'index' => 'indexsimpanan',
//         ]
//     ]);

Route::post('simpanan', 'SimpananController@store');
Route::post('verify/{pegawai}/{id}', 'SimpananController@update');
Route::post('hitung_bunga/{pegawai}', 'PerhitunganBungaController@create');