<?php

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


/*
 * Patients Route
 */

Route::resource('patients','PatientController');


/*
 * Patients Route
 */

Route::resource('users','UserController');
Route::post('/bulk_delete','UserController@bulk_delete')->name('bulk_delete');



/*
 * Patients Route
 */

Route::resource('preferences','PreferenceController');



/*
 * Insurance Route
 */
Route::resource('insurance','InsuranceController');
Route::post('/bulk_deleteInsurance','InsuranceController@bulk_deleteInsurance')->name('bulk_deleteInsurance');


/*
 * Charges Route
 */
Route::resource('charges','ChargeController');
Route::post('/bulk_deleteCharge','ChargeController@bulk_deleteCharge')->name('bulk_deleteCharge');


