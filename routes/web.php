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
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    // return what you want
});
Route::get('/','HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
$locale = Request::segment(1);

if ($locale ==  'admin') {
    Route::group(array('prefix' => $locale), function() {
        Route::get('/dashboard', 'Admin\DashboardController@index');
        Route::get('/usermanage', 'Admin\UsermanageController@index');
        Route::post('/register', 'Admin\UsermanageController@register');
        Route::get('/getUsers', 'Admin\UsermanageController@getUsers');

        Route::post('/getUserInfo', 'Admin\UsermanageController@getUserInfo');
        Route::post('/updateUser', 'Admin\UsermanageController@updateUserInfo');
        Route::post('/deleteUser', 'Admin\UsermanageController@deleteUser');
        Route::get('/changepassword','Admin\UsermanageController@changePassword');
        Route::post('/resetpassword', 'Admin\UsermanageController@resetPassword');
        Route::get('/ward','Admin\WardController@index');
        Route::get('/getWard','Admin\WardController@getWard');
        Route::post('saveWard','Admin\WardController@saveWard');
        Route::get('/corpward','Admin\CorpWardController@index');
        Route::get('/getCorpWard','Admin\CorpWardController@getCorpWard');
        Route::post('/saveCorpWard','Admin\CorpWardController@saveCorpWard');
        Route::get('/connectionrate','Admin\ConnectionRateController@index');
        Route::post('/saveConnectionRate','Admin\ConnectionRateController@saveConnectionRate');
        Route::get('/getConnectionRate','Admin\ConnectionRateController@getConnectionRate');
        Route::post('/getConnectionRateInfo','Admin\ConnectionRateController@getConnectionRateInfo');
        Route::post('/updateConnectionRate','Admin\ConnectionRateController@updateConnectionRate');
        Route::get('/connectiontype','Admin\ConnectionTypeController@index');
        Route::get('/getConnectionType','Admin\ConnectionTypeController@getConnectionType');
        Route::post('/saveConnectionType','Admin\ConnectionTypeController@saveConnectionType');
        Route::get('/inspector','Admin\InspectorController@index');
        Route::get('/getInspector','Admin\InspectorController@getInspector');
        Route::post('/saveInspector','Admin\InspectorController@saveInspector');
        Route::post('/changeInspectorStatus','Admin\InspectorController@changeInspectorStatus');
        Route::get('/applicationstatus','Admin\ApplicationStatusController@index');
        Route::get('/getApplicationStatus','Admin\ApplicationStatusController@getApplicationStatus');
        Route::post('/saveApplicationStatus','Admin\ApplicationStatusController@saveApplicationStatus');
        Route::get('/connectionstatus','Admin\ConnectionStatusController@index');
        Route::post('/saveConnectionStatus','Admin\ConnectionStatusController@saveConnectionStatus');
        Route::get('/getConnectionStatus','Admin\ConnectionStatusController@getConnectionStatus');
        Route::get('/agent','Admin\AgentController@index');
        Route::get('/getAgents','Admin\AgentController@getAgents');
        Route::post('/saveAgent','Admin\AgentController@saveAgent');
        ROute::post('/changeAgentStatus','Admin\AgentController@changeAgentStatus');
        Route::get('/plumber','Admin\PlumberController@index');
        Route::get('/getPlumber','Admin\PlumberController@getPlumber');
        Route::post('/savePlumber','Admin\PlumberController@savePlumber');
        Route::post('/getInspectorWard','Admin\AgentController@getInspectorWard');
        Route::get('/connectionDetail','Admin\ConnectionDetailController@index');
       // Route::get('/getConnectionDetail','Admin\ConnectionDetailController@getConnectionDetail');
        Route::post('/getConnectionDetail','Admin\ConnectionDetailController@getConnectionDetail');
        Route::post('/getConnectionInfo','Admin\ConnectionDetailController@getConnectionInfo');
        Route::post('/connection_search','Admin\ConnectionDetailController@connection_search');
        Route::get('/connectionStatusChange','Admin\ConnectionStatusController@connectionStatusChange');
        Route::post('/disconnect_reconnect_search_all','Admin\ConnectionDetailController@disconnectReconnectSearchAll');
        Route::post('/disconnect_reconnect_search','Admin\ConnectionDetailController@disconnectReconnectSearch');
        Route::post('/disconnect_reconnect_log_search','Admin\ConnectionDetailController@disconnectReconnectLogSearch');
        Route::post('/reconnectConnection','Admin\ConnectionStatusController@reconnectConnection');
        Route::post('/disconnectConnection','Admin\ConnectionStatusController@disconnectConnection');
        Route::post('/downloadFile','Admin\ConnectionStatusController@downloadFile');
        Route::get('/meterNameChange','Admin\MeterNameChangeController@index');

        Route::post('/meter_name_change_search_all','Admin\MeterNameChangeController@meterNameChangeSearchAll');
        Route::post('/meter_name_change_search','Admin\MeterNameChangeController@meterNameChangeSearch');
        Route::post('/meter_namechange_log_search','Admin\MeterNameChangeController@meterNameChangeLogSearch');
        Route::get('/addConnectionDetail','Admin\ConnectionDetailController@addConnectionDetail');
        Route::post('/saveConnectionDetail','Admin\ConnectionDetailController@saveConnectionDetail');
        Route::post('/getCorpWardForWard','Admin\ConnectionDetailController@getCorpWardForWard');
        Route::post('/nameChange','Admin\MeterNameChangeController@saveNameChange');
        Route::post('/meterChange','Admin\MeterNameChangeController@saveMeterChange');
        Route::get('/tariffChange','Admin\ConnectionTypeController@tariffChange');
        Route::post('/tariffchange_log_search','Admin\ConnectionTypeController@tariffchangeLogSearch');
        Route::post('/getTariffInfo','Admin\ConnectionTypeController@getTariffInfo');
        Route::post('/getConnectionMinRate','Admin\ConnectionRateController@getConnectionMinRate');
        Route::post('/saveTariffChange','Admin\ConnectionTypeController@saveTariffChange');

        //Edit Consumer
        Route::get('/EditConsumer/{id}','Admin\ConnectionDetailController@editConsumer');
        Route::post('/updateConnectionDetail','Admin\ConnectionDetailController@updateConnectionDetail');

        //Import
        Route::get('/import_data','Admin\ImportController@importExcelOrCSV');
        Route::post('/import-consumer-info','Admin\ImportController@importConsumerInfoToDB');
        Route::post('/import-reading-info','Admin\ImportController@importReadingInfoToDB');
        Route::post('/import-old-reading-info','Admin\ImportController@importOldReadingInfoToDB');
        Route::post('/import-oldpayment-info','Admin\ImportController@importOldPaymentData');
        Route::post('/import-ward-info','Admin\ImportController@importWardCorp');
        Route::get('/downloadExcel/{type}','Admin\ImportController@downloadExcel');
        Route::post('/import-payment-info','Admin\ImportController@importPaymentInfoToDB');

        //Payment Routes
        Route::get('/billpayment', 'Admin\BillPaymentController@index');

        Route::post('/getpaymentdetails','Admin\BillPaymentController@getDetails');
        Route::post('/payment_search','Admin\BillPaymentController@paymentSearch');
        Route::post('/getConsumerInfo','Admin\BillPaymentController@viewPaymentInfo');
        Route::post('/paybill','Admin\BillPaymentController@payBillPage');
        Route::post('/addnewpayment','Admin\BillPaymentController@addNewPayment');
        Route::post('/addnewbankpayment','Admin\BillPaymentController@addNewBankPayment');
        Route::post('/verifypayment','Admin\BillPaymentController@verifyPayment');
        Route::post('/updateverifypayment','Admin\BillPaymentController@updateVerifyPayment');
        Route::get('/duplicateBill','Admin\BillPaymentController@duplicateBill');
        Route::post('/getDuplicateBillInfo','Admin\BillPaymentController@getDuplicateBillInfo');
        Route::get('/printDuplicateBill','Admin\BillPaymentController@printDuplicateBill');
        Route::get('/print_challan_data','Admin\BillPaymentController@printChallanData');


        //Report Routes
        Route::get('/consumer_billing_report', 'Admin\BillingReportController@index');
        Route::post('/billing_report_search','Admin\BillingReportController@billReportSearch');
        Route::get('/billing_report_print','Admin\BillingReportController@billReportPrint');
        Route::get('/connection_report', 'Admin\ConnectionReportController@index');
        Route::post('/connection_report_search','Admin\ConnectionReportController@ConnectionReportSearch');
        Route::get('/connection_report_print','Admin\ConnectionReportController@ConnectionReportPrint');
        Route::get('/corp_ward_pending_report', 'Admin\CorpWardReportController@index');
        Route::post('/corp_ward_report_search','Admin\CorpWardReportController@CorpWardReportSearch');
        Route::get('/corp_ward_report_print','Admin\CorpWardReportController@CorpWardReportPrint');
        Route::get('/wardwise_dcb_report', 'Admin\WardDCBReportController@index');
        Route::post('/dcb_report_search','Admin\WardDCBReportController@DCBReportSearch');
        Route::get('/dcb_report_print','Admin\WardDCBReportController@DCBReportPrint');
        Route::get('/generate_excel','Admin\BillingReportController@excelReport');
	    Route::get('/generate_dcb_excel','Admin\WardDCBReportController@excelReport');
        Route::get('/corp_ward_report_excel','Admin\CorpWardReportController@corpWardPendingExcel');


        //Application Routes
        Route::get('/addNewApplication', 'Admin\NewApplicationController@index');
        Route::post('/saveNewApplication','Admin\NewApplicationController@saveData');
        Route::get('/addApplicationDetail','Admin\ApplicationDetailsController@index');
        Route::get('/getapplicationlist','Admin\ApplicationDetailsController@getList');
        Route::post('/getApplicationInfo','Admin\ApplicationDetailsController@getAppInfo');
        Route::post('/getInspectorList','Admin\ApplicationDetailsController@getInspectorList');
        Route::post('/getInspectorAgent','Admin\ApplicationDetailsController@getInspectorAgent');
        Route::post('/save_ledger_details','Admin\ApplicationDetailsController@saveLedgerDetails');
        Route::post('/save_meter_details','Admin\ApplicationDetailsController@saveMeterDetails');
        Route::post('/save_approval_details','Admin\ApplicationDetailsController@saveApprovalDetails');
        Route::post('/approve_appalication','Admin\ApplicationDetailsController@approveApplication');
        Route::post('/sequence_number_show','Admin\ApplicationDetailsController@showSuccessPage');
        Route::post('/reject_appalication','Admin\ApplicationDetailsController@rejectApplication');
        Route::post('/hold_appalication','Admin\ApplicationDetailsController@holdApplication');
        Route::post('/check_ledger_details','Admin\ApplicationDetailsController@checkLedger');
        Route::post('/check_meter_details','Admin\ApplicationDetailsController@checkMeterDetails');
        Route::post('/check_approval_details','Admin\ApplicationDetailsController@checkApprovalDetails');
        Route::post('/appdownloadFile','Admin\ApplicationDetailsController@downloadFile');

        //Generate New Bill
        Route::get('/newbill','Admin\NewBillController@index');
        Route::post('/customer_bill_Info','Admin\NewBillController@getCustomerBillInfo');
        Route::post('/calculate_water_charge','Admin\NewBillController@waterChargeCalculate');
        Route::post('/generate_water_bill','Admin\NewBillController@generateWaterBill');
        Route::get('/print_new_bill','Admin\NewBillController@printNewBill');
        Route::post('/get_meter_reading_info','Admin\NewBillController@getReadingInfo');

        Route::get('/approvebill','Admin\NewBillController@approveBill');
        Route::post('/getBillListForReview','Admin\NewBillController@billListForReview');
        Route::get('/getBillDetails','Admin\NewBillController@getBillDetails');
        Route::post('/approve_bill_details','Admin\NewBillController@approveBillDetails');

        Route::get('/deletebill','Admin\DeleteBillController@index');
        Route::post('/deleteBillInfo','Admin\DeleteBillController@deleteBillInfo');
        Route::post('/getBasicInfo','Admin\DeleteBillController@getBasicInfo');

        //Industrial Bill
        Route::get('/industrialbill','Admin\IndustrialBillController@index');
        Route::post('/getIndustialBillInfo','Admin\IndustrialBillController@getIndustialBillInfo');

        //Challan and payment Report
        Route::get('/challan_payment_report','Admin\ChallanReportController@index');
        Route::get('/collection_report','Admin\ChallanReportController@index');
        Route::post('/challan_report_search','Admin\ChallanReportController@reportSearch');
        Route::post('/getUserDesignation','Admin\UsermanageController@getUserDesignation');
        Route::get('/challan_report_print','Admin\ChallanReportController@printReport');
        Route::get('/generate_challan_excel','Admin\ChallanReportController@printExcelReport');
        Route::post('/total_collection','Admin\ChallanReportController@getTotalCollection');

        //Payment import test
        Route::get('/test_payment_import','Admin\ImportController@testPaymentImport');
        Route::post('/getPaymentInfo','Admin\ImportController@getPaymentInfo');
        Route::get('/correctPaymentImport','Admin\ImportController@paymentImportErrorCorrection');
        Route::get('/correctNullTransactionImport','Admin\ImportController@nullTransNumberImportErrorCorrection');

        Route::get('/moveOldMeeterReading','Admin\CommonController@moveOldMeeterReading');
        Route::get('/checkPayment/{id}','Admin\CommonController@getFaultyData');
        Route::get('/checkAfterImport','Admin\CommonController@getcheckAfterImport');
        Route::get('/checkFaultyData','Admin\CommonController@deleteFaultyMeterReadings');
        Route::get('/updateLedger','Admin\CommonController@correctLedgerData');
        Route::post('/getLogData','Admin\DashboardController@getLogData');
        
        Route::get('/calculateOpeningBalance','Admin\CommonController@calculateOpeningBalance');
    });
}

if($locale ==  'user') {
    Route::group(array('prefix' => $locale), function() {
        Route::get('/dashboard', 'User\DashboardController@index');
    });
}

//CONSUMER LOGIN
Route::post('consumer/login','Auth\LoginController@authenticateConsumer');
Route::group(['middleware' => ['consumer']], function () {
Route::get('consumer/dashboard','User\DashboardController@index');
});
