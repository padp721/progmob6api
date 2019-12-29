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
	Route::post('detail', 'UserController@detailKaryawan');
	Route::post('edit/{id}', 'UserController@userEdit');
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

// Route::post('upload_bukti/{id}','SimpananController@uploadBukti');
Route::post('simpanan', 'SimpananController@store');
Route::post('verify/{pegawai}/{id}', 'SimpananController@update');
Route::post('hitung_bunga/{pegawai}', 'PerhitunganBungaController@create');
Route::post('not_verify_simpan','SimpananController@notVerifySimpan');
Route::get('not_verify_tarik','SimpananController@notVerifyTarik');

Route::group(['prefix'=>'report'],function(){

    Route::get('nasabah','ReportController@nasabah')->name('reportnasabah');
    Route::get('nasabah/{id}','ReportController@detailnasabah')->name('detailnasabah');
    Route::get('nasabah/setor/{id}','ReportController@detailnasabahsetor')->name('detailnasabahsetor');
    Route::get('nasabah/tarik/{id}','ReportController@detailnasabahtarik')->name('detailnasabahtarik');

    Route::get('harian','ReportController@harian')->name('reportharian');
    Route::post('harian','ReportController@harians')->name('reportharian');

    Route::post('mingguan','ReportController@mingguan')->name('reportmingguan');
    Route::get('mingguan','ReportController@mingguanNow')->name('reportmingguan');

    Route::post('bulanan','ReportController@bulanan')->name('reportbulanan');
    Route::get('bulanan','ReportController@bulananNow')->name('reportbulanan');

    Route::post('tahunan','ReportController@tahunan')->name('reporttahunan');
    Route::get('tahunan','ReportController@tahunanNow')->name('reporttahunan');
});

Route::get('notif/setoran','FirebaseNotificationController@setoran');
Route::get('notif/approval','FirebaseNotificationController@approval');
Route::get('notif','FirebaseNotificationController@allNotif');
