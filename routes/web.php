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

Route::get('/test', function () {
    return view('print-test');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


/*
 * Patients Route
 */

Route::resource('patients','PatientController');
Route::post('/old-records','PatientController@upload_old_files')->name('upload-records');
Route::get('/search-patient','PatientController@searchPatient')->name('searchPatient');
Route::get('/view-old-records/{id}','PatientController@viewOldRecord')->name('view-old-records');
Route::post('/upload-labs-scans/{id}','PatientController@uploadLabScanResult')->name('upload-labs-scans');
Route::post('/upload-detention-labs-scans/{id}','PatientController@uploadDetentionLabScanResult')->name('upload-detention-labs-scans');


/*
 * Registration Route
 */

Route::resource('registration','OpdRegistrationController');



/*
 * Registration reports
 */

Route::resource('reports','ReportsController');
Route::get('patients-reports','ReportsController@patient_report')->name('patients-reports');



Route::resource('vitals','VitalsController');
Route::get('/search-registration-for-vitals','VitalsController@searchRegistrationForVitals')->name('searchRegistrationForVitals');
Route::get('/search-vitals-by-date','VitalsController@vitalByDate')->name('vitalByDate');
Route::post('/add-vitals-detention','VitalsController@addVital')->name('addVital');


/*
 * consultation Route
 */

Route::resource('consultation','ConsultationController');
Route::get('/search-consultation','ConsultationController@searchConsultation')->name('searchConsultation');
Route::post('/patient-record','ConsultationController@patientRecord')->name('patientRecord');
Route::get('/edit-medication/{drug_id}/{med_id}','ConsultationController@editMedication')->name('editMedication');
Route::post('/edit-medication','ConsultationController@edit_med')->name('edit_med');
Route::post('/discharge-patient/{id}','ConsultationController@discharge')->name('discharge-patient');


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
Route::get('/upload_format','DrugController@downloadUploadFormat')->name('upload_format');
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
Route::get('search-detained','DetainedRecordsController@searchPatientForDrugDispersion')->name('searchPatientForDrugDispersion');
Route::get('out-standing-medication','DetainedRecordsController@outStandingMedications')->name('out-standing-medication');

Route::get('view-record/{patient_id}/{registration_id}','DetainedRecordsController@view_detention_record')->name('view_detention_record');
Route::post('view-record-by-date','DetainedRecordsController@view_detentionByDate')->name('view_detention');

Route::post('add-medication-only','DetainedRecordsController@addMedicationOnly')->name('add-medication-only');
Route::post('add-service-only','DetainedRecordsController@addServiceOnly')->name('add-service-only');






Route::post('change-password','UserController@change_password')->name('password-change');
Route::get('password-change','UserController@password_change')->name('change-password');


/*
 * Pharmacy -drugs types Route
 */

Route::resource('drugs-types','DrugTypeController');
Route::post('/bulk_deleteDrugType','DrugTypeController@bulk_deleteDrugType')->name('bulk_deleteDrugType');





/*
 * users Route
 */

Route::resource('users','UserController');
Route::post('/bulk_delete','UserController@bulk_delete')->name('bulk_delete');



/*
 * preferences Route
 */

Route::resource('preferences','PreferenceController');



/*
 * Insurance Route
 */
Route::resource('insurance','InsuranceController');
Route::post('/bulk-deleteInsurance','InsuranceController@bulk_deleteInsurance')->name('bulk_deleteInsurance');


/*
 * Insurance Route
 */
Route::resource('diagnoses','DiagnoseController');
Route::post('/bulk-deleteDiagnoses','DiagnoseController@bulk_deleteDiagnoses')->name('bulk_deleteDiagnoses');




/*
 * Charges Route
 */
Route::resource('charges','ChargeController');
Route::post('/bulk-deleteCharge','ChargeController@bulk_deleteCharge')->name('bulk_deleteCharge');



Route::resource('payment','PaymentController');
Route::post('pay-arrears','PaymentController@payArrears')->name('payArrears');


/*
 * Review Route
 */

Route::resource('review','ReviewController');