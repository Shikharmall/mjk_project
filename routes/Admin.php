<?php
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Admin', 'prefix' => 'panel', 'as' => 'panel.'], function () {

    Route::get('/', function (){
        return redirect()->route('panel.auth.login');
    });

    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', 'Logincontroller@login')->name('login');
        Route::post('login', 'Logincontroller@submit')->name('submit')->middleware('actch');
        Route::get('logout', 'Logincontroller@logout')->name('logout');
        
    });

    Route::group(['prefix' => 'Invoice', 'as' => 'Invoice.'], function () {
        Route::get('sample-info/{id}', 'InvoiceController@sample_info')->name('sample-info');
    });

    

    Route::group(['middleware' => ['admin']], function () {

        Route::get('/','DashboardController@dashboard')->name('dashboard');

        
        Route::group(['prefix' => 'User', 'as' => 'User.'], function () {
            Route::get('list', 'UserController@list')->name('list');
            Route::get('user', 'UserController@user')->name('user');
            Route::post('add-user', 'UserController@add_user')->name('add-user');
            Route::get('edit/{id}','UserController@edit')->name('edit');
            Route::get('delete/{id}','UserController@delete')->name('delete');
            Route::get('approve/{id}','UserController@approve')->name('approve');
            Route::get('disapprove/{id}','UserController@disapprove')->name('disapprove');
            Route::get('district-assign/{id}', 'UserController@district_assign')->name('district-assign');
            Route::get('lab-assign/{id}', 'UserController@lab_assign')->name('lab-assign');
            Route::get('hospital-assign/{id}', 'UserController@hospital_assign')->name('hospital-assign');
            Route::post('assign-district', 'UserController@assign_district')->name('assign-district');
            Route::post('assign-lab', 'UserController@assign_lab')->name('assign-lab');
            Route::post('assign-hospital', 'UserController@assign_hospital')->name('assign-hospital');
            Route::get('delete-district/{id}','UserController@delete_district')->name('delete-district');
            Route::get('delete-lab/{id}','UserController@delete_lab')->name('delete-lab');
            Route::get('delete-hospital/{id}','UserController@delete_hospital')->name('delete-hospital');

            Route::get('staff-activity-report/{id}', 'UserController@staff_activity_report')->name('staff-activity-report');
            Route::get('map/{id}', 'UserController@map')->name('map');
            Route::post('filter', 'UserController@filter')->name('filter');
            Route::get('reset/{id}', 'UserController@reset')->name('reset');
        });

        Route::group(['prefix' => 'Hospital', 'as' => 'Hospital.'], function () {
            Route::get('list', 'HospitalController@list')->name('list');
            Route::get('hospital', 'HospitalController@hospital')->name('hospital');
            Route::post('add-hospital', 'HospitalController@add_hospital')->name('add-hospital');
            Route::get('edit/{id}','HospitalController@edit')->name('edit');
            Route::get('delete/{id}','HospitalController@delete')->name('delete');
            Route::get('approve/{id}','HospitalController@approve')->name('approve');
            Route::get('disapprove/{id}','HospitalController@disapprove')->name('disapprove');
        });

        Route::group(['prefix' => 'District', 'as' => 'District.'], function () {
            Route::get('list', 'DistrictController@list')->name('list');
            Route::get('district', 'DistrictController@district')->name('district');
            Route::post('add-district', 'DistrictController@add_district')->name('add-district');
            Route::get('edit/{id}','DistrictController@edit')->name('edit');
            Route::get('delete/{id}','DistrictController@delete')->name('delete');
        });

        Route::group(['prefix' => 'Lab', 'as' => 'Lab.'], function () {
            Route::get('list', 'LabController@list')->name('list');
            Route::get('lab', 'LabController@lab')->name('lab');
            Route::post('add-lab', 'LabController@add_lab')->name('add-lab');
            Route::get('edit/{id}','LabController@edit')->name('edit');
            Route::get('delete/{id}','LabController@delete')->name('delete');
            Route::get('approve/{id}','LabController@approve')->name('approve');
            Route::get('disapprove/{id}','LabController@disapprove')->name('disapprove');
        });

        Route::group(['prefix' => 'Specimen', 'as' => 'Specimen.'], function () {
            Route::get('list', 'SpecimenController@list')->name('list');
            Route::get('specimen', 'SpecimenController@specimen')->name('specimen');
            Route::post('add-specimen', 'SpecimenController@add_specimen')->name('add-specimen');
            Route::get('edit/{id}','SpecimenController@edit')->name('edit');
            Route::get('delete/{id}','SpecimenController@delete')->name('delete');
        });

        Route::group(['prefix' => 'Test', 'as' => 'Test.'], function () {
            Route::get('list', 'TestController@list')->name('list');
            Route::get('test', 'TestController@test')->name('test');
            Route::post('add-test', 'TestController@add_test')->name('add-test');
            Route::get('edit/{id}','TestController@edit')->name('edit');
            Route::get('delete/{id}','TestController@delete')->name('delete');
        });

        Route::group(['prefix' => 'TodayReport', 'as' => 'TodayReport.'], function () {
            Route::get('today-collection', 'TodayReportController@today_collection')->name('today-collection');
            Route::get('today-collection-sample/{id}', 'TodayReportController@today_collection_sample')->name('today-collection-sample');
            Route::get('today-submitted', 'TodayReportController@today_submitted')->name('today-submitted');
            Route::get('today-submitted-sample/{id}', 'TodayReportController@today_submitted_sample')->name('today-submitted-sample');
            Route::get('today-collected-report', 'TodayReportController@today_collected_report')->name('today-collected-report');
            Route::get('today-collected-sample/{id}', 'TodayReportController@today_collected_sample')->name('today-collected-sample');
            Route::get('today-submitted-report', 'TodayReportController@today_submitted_report')->name('today-submitted-report');
            Route::get('today-submitted-report-sample/{id}', 'TodayReportController@today_collected_report_sample')->name('today-submitted-report-sample');
        });

        Route::group(['prefix' => 'AllReport', 'as' => 'AllReport.'], function () {
            Route::get('all-collection', 'AllReportController@all_collection')->name('all-collection');
            Route::get('collection-sample/{id}', 'AllReportController@collection_sample')->name('collection-sample');
            Route::get('all-submitted', 'AllReportController@all_submitted')->name('all-submitted');
            Route::get('submitted-sample/{id}', 'AllReportController@submitted_sample')->name('submitted-sample');
            Route::get('all-collected-report', 'AllReportController@all_collected_report')->name('all-collected-report');
            Route::get('collected-sample/{id}', 'AllReportController@collected_sample')->name('collected-sample');
            Route::get('all-submitted-report', 'AllReportController@all_submitted_report')->name('all-submitted-report');
            Route::get('submitted-report-sample/{id}', 'AllReportController@submitted_report_sample')->name('submitted-report-sample');

            Route::get('sample-report', 'AllReportController@sample_report')->name('sample-report');
            Route::post('filter', 'AllReportController@filter')->name('filter');
            Route::get('filter/reset', 'AllReportController@filter_reset');
        });

        Route::group(['prefix' => 'Invoice', 'as' => 'Invoice.'], function () {
            Route::get('invoice', 'InvoiceController@invoice')->name('invoice');
            Route::get('sample-invoice/{district_id}/{rate}', 'InvoiceController@sample_invoice')->name('sample-invoice');
            Route::get('kilometer-invoice/{district_id}/{rate}', 'InvoiceController@kilometer_invoice')->name('kilometer-invoice');
            Route::get('sample-collection-info/{id}', 'InvoiceController@sample_collection_info')->name('sample-collection-info');
            Route::get('sample-submittion-info/{id}', 'InvoiceController@sample_submittion_info')->name('sample-submittion-info');
            Route::get('sample-collectted-info/{id}', 'InvoiceController@sample_collectted_info')->name('sample-collectted-info');
            Route::get('sample-submitted-info/{id}', 'InvoiceController@sample_submitted_info')->name('sample-submitted-info');
            Route::post('filter', 'InvoiceController@filter')->name('filter');
            Route::get('reset', 'InvoiceController@reset')->name('reset');
            Route::post('kfilter', 'InvoiceController@kfilter')->name('kfilter');
            Route::get('kreset', 'InvoiceController@kreset')->name('kreset');

            Route::get('sample-generated-invoice-list', 'InvoiceController@sample_generated_invoice_list')->name('sample-generated-invoice-list');
            Route::get('kilometer-generated-invoice-list', 'InvoiceController@kilometer_generated_invoice_list')->name('kilometer-generated-invoice-list');

            Route::get('sample-invoice-detail/{id}', 'InvoiceController@sample_invoice_detail')->name('sample-invoice-detail');

            Route::get('kilometer-invoice-detail/{id}', 'InvoiceController@kilometer_invoice_detail')->name('kilometer-invoice-detail');
        });


        Route::group(['prefix' => 'Reports', 'as' => 'Reports.'], function () {
             // Route::get('generate-sample-invoice/{district_id}/{staff_id}', 'InvoiceController@generate_sample_invoice')->name('generate-sample-invoice');
            // Route::get('generate-kilometer-invoice/{district_id}/{staff_id}', 'InvoiceController@generate_kilometer_invoice')->name('generate-kilometer-invoice');
            Route::get('sample-report', 'InvoiceController@generate_sample_invoice')->name('sample-report');
            Route::post('sample-collected', 'InvoiceController@sample_collected')->name('sample-collected');
            Route::post('sample-submitted', 'InvoiceController@sample_submitted')->name('sample-submitted');
            Route::post('sample-collection', 'InvoiceController@sample_collection')->name('sample-collection');
            Route::post('sample-submittion', 'InvoiceController@sample_submittion')->name('sample-submittion');
            Route::get('staff-sample-report/{id}', 'InvoiceController@get_sample_staff')->name('staff-sample-report');





            

            Route::get('district-sample-report/{id}', 'InvoiceController@get_sample_district')->name('district-sample-report');





            Route::post('filter', 'InvoiceController@sample_filter')->name('filter');
            Route::get('reset', 'InvoiceController@sample_reset')->name('reset');
            Route::get('reset-all', 'InvoiceController@sample_reset_all')->name('reset-all');

            Route::get('kilometer-report', 'InvoiceController@generate_kilometer_invoice')->name('kilometer-report');
            Route::post('kfilter', 'InvoiceController@kilometer_filter')->name('kfilter');
            Route::get('kreset', 'InvoiceController@kilometer_reset')->name('kreset');
            Route::get('staff-kilometer-report/{id}', 'InvoiceController@get_kilometer_staff')->name('staff-kilometer-report');
            Route::get('district-kilometer-report/{id}', 'InvoiceController@get_kilometer_district')->name('district-kilometer-report');
            Route::get('distance-check/{id}', 'InvoiceController@distance_check')->name('distance-check');


            Route::get('report-sample/{id}', 'InvoiceController@report_sample')->name('report-sample');
            
        });

        Route::group(['prefix' => 'Search', 'as' => 'Search.'], function () {
            Route::get('nikshay-search', 'AllReportController@nikshay_search')->name('nikshay-search');
        });

        

    });
});

