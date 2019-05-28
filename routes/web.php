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
Route::post('/searchPatient','PatientController@searchPatient')->name('searchPatient');




/*
 * Registration Route
 */

Route::resource('registration','OpdRegistrationController');


/*
 * Registration Route
 */

Route::resource('vitals','VitalsController');
Route::post('/searchRegistrationForVitals','VitalsController@searchRegistrationForVitals')->name('searchRegistrationForVitals');


/*
 * consultation Route
 */

Route::resource('consultation','ConsultationController');
Route::post('/searchConsultation','ConsultationController@searchConsultation')->name('searchConsultation');
Route::post('/patientRecord','ConsultationController@patientRecord')->name('patientRecord');


/*
 * Pharmacy -drugs Route
 */

Route::resource('drugs','DrugController');
Route::post('/bulk_deleteDrug','DrugController@bulk_deleteDrug')->name('bulk_deleteDrug');
Route::post('/searchConsultation','ConsultationController@searchConsultation')->name('searchConsultation');


/*
 * Suppliers route
 */
Route::resource('suppliers','SupplierController');
Route::post('/bulk_deleteSupplier','SupplierController@bulk_deleteSupplier')->name('bulk_deleteSupplier');






/*
 * Pharmacy -drugs types Route
 */

Route::resource('drugs-types','DrugTypeController');
Route::post('/bulk_deleteDrugType','DrugTypeController@bulk_deleteDrugType')->name('bulk_deleteDrugType');





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
 * Insurance Route
 */
Route::resource('diagnoses','DiagnoseController');
Route::post('/bulk_deleteDiagnoses','DiagnoseController@bulk_deleteDiagnoses')->name('bulk_deleteDiagnoses');




/*
 * Charges Route
 */
Route::resource('charges','ChargeController');
Route::post('/bulk_deleteCharge','ChargeController@bulk_deleteCharge')->name('bulk_deleteCharge');


