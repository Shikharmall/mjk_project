<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SampleController;
use App\Http\Controllers\API\FileUploadController;
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

Route::post('signin',[AuthController::class,'signin']);
Route::get('get-specimen',[AuthController::class,'get_specimen']);
Route::get('get-test',[AuthController::class,'get_test']);
Route::get('get-sample',[AuthController::class,'get_sample']);
Route::get('odometer-status',[AuthController::class,'odometer_status']);
Route::post('files-upload',[FileUploadController::class,'file_upload']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('get-hospital',[AuthController::class,'get_hospital']);
    Route::get('search-hospital',[AuthController::class,'search_hospital']);
    Route::get('get-lab',[AuthController::class,'get_lab']);
    Route::get('check-status',[AuthController::class,'check_status']);
    Route::get('all-collection-count',[SampleController::class,'all_collection_count']);
    Route::post('add-sample-box-item',[SampleController::class,'add_sample_box_item']);
    Route::get('sample-box-item',[SampleController::class,'sample_box_item']);
    Route::post('add-sample',[SampleController::class,'add_sample']);
    Route::get('submit-collection-lab',[SampleController::class,'submit_collection_lab']);
    Route::get('search-collection-lab',[SampleController::class,'search_collection_lab']);
    // Route::post('add-sample-collection',[SampleController::class,'add_sample_collection']);
    Route::get('all-collection-detail',[SampleController::class,'all_collection_detail']);
    Route::get('search-collection-hospital',[SampleController::class,'search_collection_hospital']);
    Route::get('all-hospital-sample-collected',[SampleController::class,'all_hospital_sample_collected']);
    // Route::post('update-to-sample-collection',[SampleController::class,'update_to_sample']);
    Route::get('lab-collection-point',[SampleController::class,'lab_collection_point']);
    // Route::post('add-sample-selected',[SampleController::class,'add_sample_selected']);
    // Route::get('collection-point',[SampleController::class,'collection_point']);
    Route::post('add-collected-report',[SampleController::class,'add_collected_report']);
    Route::get('collected-report-lab',[SampleController::class,'collected_report_lab']);
    Route::get('all-submitted-count',[SampleController::class,'all_submitted_count']);
    Route::get('search-collected-report-lab',[SampleController::class,'search_collected_report_lab']);
    Route::get('submitted-report-detail',[SampleController::class,'submitted_report_detail']);
    Route::get('submitted-lab-sample',[SampleController::class,'submitted_lab_sample']);
    Route::post('add-submmitted-sample-images',[SampleController::class,'add_submitted_sample_images']);
    Route::get('submitted-sample-images',[SampleController::class,'submitted_sample_images']);
    Route::post('add-submitted-collected-report',[SampleController::class,'add_submitted_collected_report']);
    Route::get('all-collected-report-count',[SampleController::class,'all_collected_report_count']);
    Route::get('collect-submitted-report-lab',[SampleController::class,'collect_submitted_report_lab']);
    Route::get('search-collect-submitted-report-lab',[SampleController::class,'search_collect_submitted_report_lab']);
    Route::get('collect-submitted-report-detail',[SampleController::class,'collect_submitted_report_detail']);
    Route::get('collected-report-hospital',[SampleController::class,'collected_report_hospital']);
    Route::get('search-collected-report-hospital',[SampleController::class,'search_collect_report_hospital']);
    Route::get('submitted-hospital-sample',[SampleController::class,'submitted_hospital_sample']);
    Route::post('add-submitted-report',[SampleController::class,'add_submitted_report']);
    Route::get('all-submitted-report-count',[SampleController::class,'all_submitted_report_count']);
    Route::get('submitted-report-hospital',[SampleController::class,'submitted_report_hospital']);
    Route::get('search-submitted-report-hospital',[SampleController::class,'search_submitted_report_hospital']);
    Route::get('submitted-report-hospital-detail',[SampleController::class,'submitted_report_hospital_detail']);
    Route::get('scan-qr-info',[SampleController::class,'scan_qr_info']);

});