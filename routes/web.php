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
/*Route::resource('patients', 'PhotoController')->names([
    'create' => 'photos.build'
]);*/
Route::post('/searchPatient','PatientController@searchPatient')->name('searchPatient');




/*
 * Registration Route
 */

Route::resource('registration','OpdRegistrationController');


/*
 * Registration Route
 */

Route::resource('vitals','VitalsController');
Route::post('/search-registration-for-vitals','VitalsController@searchRegistrationForVitals')->name('searchRegistrationForVitals');


/*
 * consultation Route
 */

Route::resource('consultation','ConsultationController');
Route::post('/searchConsultation','ConsultationController@searchConsultation')->name('searchConsultation');
Route::post('/patientRecord','ConsultationController@patientRecord')->name('patientRecord');
Route::get('/edit-medication/{drug_id}/{med_id}','ConsultationController@editMedication')->name('editMedication');
Route::post('/edit-medication','ConsultationController@edit_med')->name('edit_med');


//edit_consultation_charge
Route::get('/edit-diagnosis/{diagnosis_id}','ConsultationController@editDiagnosis')->name('editDiagnosis');
Route::post('/edit-diagnosis','ConsultationController@edit_diagnosis')->name('edit_diagnosis');

//edit_consultation_charge
Route::get('/edit-service/{service_id}','ConsultationController@edit_service')->name('edit_service');
Route::post('/edit-service','ConsultationController@edit_service_charge')->name('edit_service_charge');

/*
 * Pharmacy -drugs Route
 */

Route::resource('drugs','DrugController');
Route::post('/bulk-delete-drug','DrugController@bulk_deleteDrug')->name('bulk_deleteDrug');
//Route::post('/search-consultation','ConsultationController@searchConsultation')->name('searchConsultation');
Route::post('/bulk-upload','DrugController@upload_drug')->name('upload_drug');


/*
 * Suppliers route
 */
Route::resource('suppliers','SupplierController');
Route::post('/bulk_deleteSupplier','SupplierController@bulk_deleteSupplier')->name('bulk_deleteSupplier');



/*
 * Detained Records Controller
 */
Route::resource('records','DetainedRecordsController');



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



Route::resource('payment','PaymentController');