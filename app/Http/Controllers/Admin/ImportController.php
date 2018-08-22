<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\ConsumerConnection;
use App\Models\MeterReading;
use App\Models\OldMeterReading;
use App\Models\PaymentHistory;
use App\Models\SuspensePayments;
use App\Models\UnknownMeterReading;
use App\Models\BankInfo;
use App\Models\UnknownPayments;
use App\Models\ConsumerAddress;
use App\Models\Ward;
use App\Models\CorpWard;
use App\Models\ConnectionType;
use App\Models\ConnectionStatus;
use App\Models\MeterStatus;
use App\Models\BillPayment;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use DB;

class ImportController extends Controller {

    public function importExcelOrCSV(){
        return view('admin.import');
    }

    public function importConsumerInfoInfile(Request $request){
        if($request->hasFile('consumer_file')){
            $path = $request->file('consumer_file')->getRealPath();
            $extension = $request->file('consumer_file')->getClientOriginalExtension();
            if ($extension != 'csv') {
                $validator->errors()->add('consumer_file', 'Only csv file accepted!');
                return redirect()->back()->withInput(Input::all())->withErrors($validator->errors());
            }
            $file_name = 'ConsumerInfo';
            $file_path = 'public/uploads/import/';
            $file_name = $file_name . "." . $extension;
            $request->file('consumer_file')->move($file_path, $file_name);
            $full_path = $file_path . $file_name;
            $query = "LOAD DATA LOCAL INFILE '" . $full_path . "' INTO TABLE test FIELDS TERMINATED BY '|' ENCLOSED BY '\"' LINES TERMINATED BY '\\n'";
            $count_insert = DB::connection()->getpdo()->exec($query);
            echo $count_insert;
        }
    }

    public function importConsumerInfoToDB(Request $request){
       // $date = '08/10/1968';
       // $newdate = Carbon::createFromFormat('d/m/Y H:i:s', $date.' 00:00:00');
       // echo $newdate;
      //  exit;
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $consumerConnection = array();
        $consumerAddress = array();
        $consumerConnectionInsert = array();
        $consumerAddressInsert = array();
      //  $duplicateSeqInConnection = array();
       // $duplicateSeqInAddress = array();

        $validator = Validator::make($request->all(), [
        ]);
        if($request->hasFile('consumer_file')){
            $path = $request->file('consumer_file')->getRealPath();
            $file = $request->file('consumer_file')->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($extension, array('xls', 'xlsx','csv'))) {
                $validator->errors()->add('consumer_file', 'Only csv, xls and xlsx file accepted!');
                return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
            }
            $data = \Excel::load($path)->get();
           // $data = \Excel::load($path, function($reader) {
            //})->get();
            if($data->count()){
                $dataConsumer = $data->toArray();

                $imported_file_keys = array_keys($dataConsumer[0]);

                $total_count = count($dataConsumer);



            $key_error = array_diff($import_file_keys, $imported_file_keys);
            if (sizeof($key_error) > 0) {
                $validator->errors()->add('consumer_file', 'Columns doesnot match in the uploaded file');
                return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
            }
                $existingConnectionRecord = 0;
                $existingConsumerAdRecord = 0;
                foreach ($dataConsumer as $data) {
                    $consumerConnection = [];
                    $consumerAddress = [];
                    //echo $data['connection_id'];

                  //  $date = Carbon::parse(date_format($data['connection_date'],'d/m/Y H:i:s'));
                   //exit;
                    $seq_no = '';
                    $seqNoCheck = '';
                    $seqNoCheckAddress = '';
                    $seq_no = $data['connection_id'];
                    $seqNoCheck = ConsumerConnection::where('sequence_number','=',"$seq_no")->get();
                    if($seqNoCheck->isEmpty()){
                        $consumerConnection['sequence_number'] = $seq_no;
                        $consumerConnection['connection_type'] = $data['connection_type'];
                        $arr = explode("/", $data['connection_date'], 2);
                        $first = $arr[0];
                        if((strlen((string)$first) == 2) || (strlen((string)$first) == 1)){
                            $consumerConnection['connection_date'] = Carbon::createFromFormat('d/m/Y H:i:s', $data['connection_date'].' 00:00:00');
                        }else {
                            $consumerConnection['connection_date'] = Carbon::createFromFormat('Y/m/d H:i:s', $data['connection_date'].' 00:00:00');
                        }
                        $consumerConnection['name'] = $data['customer_name'];
                        $consumerConnection['mobile_no'] = isset($data['phone']) ? $data['phone'] : NULL;
                        $consumerConnection['door_no'] = $data['house_no'];
                      //  $consumerConnection['khata_no'] = $data['khata_no'];
                        $consumerConnection['no_of_flats'] = $data['no_of_flats'];
                        $consumerConnection['ward_name'] = $data['ward'];
                        $consumerConnection['corp_ward'] = $data['corp_ward'];
                        $consumerConnection['connection_status'] = $data['master_status'];
                        $consumerConnection['meter_status'] = $data['ledger_status'];
                        $consumerConnection['meter_no'] = $data['meter_no'];
                        $consumerConnection['meter_sanctioned_date'] = isset($data['meter_sanctioned_date']) ?  Carbon::createFromFormat('d/m/Y H:i:s', $data['meter_sanctioned_date'].' 00:00:00') : NULL;
                        $validator = Validator::make($consumerConnection, $validator_set, $validator_msg);
                        if ($validator->fails()) {
                          //  return back()->withInput(Input::all())->with('error', $validator->errors());
                            return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
                        }
                        $consumerConnection['connection_type_id'] = ConnectionType::where('connection_name',$consumerConnection['connection_type'])->value('id');
                        $consumerConnection['ward_id'] = Ward::where('ward_name',$consumerConnection['ward_name'])->value('id');
                        $consumerConnection['corp_ward_id'] =  CorpWard::where('corp_name',$consumerConnection['corp_ward'])->value('id');
                         $consumerConnection['connection_status_id'] = ConnectionStatus::where('status','like', '%' . $consumerConnection['connection_status'] . '%')->value('id');
                        $consumerConnection['meter_status_id'] =    MeterStatus::where('meter_status',$consumerConnection['meter_status'])->value('id');

                        unset($consumerConnection['ward_name']);
                        unset($consumerConnection['corp_ward']);
                        unset($consumerConnection['connection_type']);
                        unset($consumerConnection['connection_status']);
                        unset($consumerConnection['meter_status']);
                      //  $consumerConnectionInsert[] = $consumerConnection;
                    }else {
                      //  $duplicateSeqInConnection[] = $seq_no;
                        $existingConnectionRecord++;
                    }

                    $seqNoCheckAddress = ConsumerAddress::where('sequence_number','=',"$seq_no")->get();
                    if($seqNoCheckAddress->isEmpty()){
                        // $consumerAddress['upload_by'] = Auth::user()->staff_name;
                        // $consumerAddress['corp_ward_id'] = $data['corp_ward'];
                        // unset($data['corp_ward']);
                        $consumerAddress['sequence_number'] = $seq_no;
                        $consumerAddress['premises_owner_name'] = $data['customer_name'];
                        $consumerAddress['premises_address'] = isset($data['address']) ?$data['address'] : NULL;
                       // $consumerAddress['premises_street'] = $data['premises_street'];
                       // $consumerAddress['premises_city'] = $data['premises_city'];
                       // $consumerAddress['premises_state'] = $data['premises_state'];
                       // $consumerAddress['premises_zip'] = $data['premises_zip'];
                     //   $consumerAddress['comm_name'] = $data['customer_name'];
                       // $consumerAddress['comm_house_no'] = $data['comm_house_no'];
                       // $consumerAddress['comm_street'] = $data['comm_street'];
                      //  $consumerAddress['comm_address'] = $data['address'];
                       // $consumerAddress['comm_city'] = $data['comm_city'];
                       // $consumerAddress['comm_state'] = $data['comm_state'];
                       // $consumerAddress['comm_zip'] = $data['comm_zip'];
                      //  $consumerAddress['comm_mobile_no'] = $data['phone'];
                      //  $consumerAddress['comm_email'] = $data['comm_email'];

                       // $consumerAddressInsert[] = $consumerAddress;

                    }else {
                       // $duplicateSeqInAddress[] = $seq_no;
                        $existingConsumerAdRecord++;
                    }
                    ConsumerConnection::insert($consumerConnection);
                    ConsumerAddress::insert($consumerAddress);
                }
            //    print_r($consumerConnectionInsert); exit;
                //if(!empty($consumerConnectionInsert)){
                  //  try {
                   //     foreach (array_chunk($consumerConnectionInsert,1000) as $recordconn) {
                    //        ConsumerConnection::insert($recordconn);
                            //\DB::table('consumer_connection')->insert($recordconn);
                    //    }
                  //  } catch (\Exception $e) {
                  //      return redirect()->back()->with("error",$e);
                  //  }
                //}

               // if(!empty($consumerAddressInsert)){
                   // try {
                 //       foreach (array_chunk($consumerAddressInsert,1000) as $recordadd) {
                       //     ConsumerAddress::insert($recordadd);
                            //\DB::table('consumer_address')->insert($record);
                   //     }
                   // } catch (\Exception $e) {
                   //     return redirect()->back()->with("error","Something went wrong. Please try again");
                  //  }
               // }
                return redirect()->back()->with('success','Imported Successfully. Total number of records in excel '.$total_count.'------- Duplicate Connection data '.$existingConnectionRecord.'------- Duplicate Consumer data '.$existingConsumerAdRecord);
            }
            return redirect()->back()->with("error","Selected file is empty");
        }
        return redirect()->back()->with("error","Please select file to Import");
    }


    public function importReadingInfoToDB(Request $request){

        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $consumerReading = array();
        $consumerPayment = array();
        $consumerReadingInsert = array();
        $consumerPaymentInsert = array();

        $validator = Validator::make($request->all(), [
        ]);
        if($request->hasFile('reading_file')){
            $path = $request->file('reading_file')->getRealPath();
            $file = $request->file('reading_file')->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($extension, array('xls', 'xlsx','csv'))) {
                $validator->errors()->add('reading_file', 'Only csv, xls and xlsx file accepted!');
                return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
            }
           $data = \Excel::load($path)->get();

            if($data->count()){
                $dataReading = $data->toArray();

                $imported_file_keys = array_keys($dataReading[0]);

                $total_count = count($dataReading);
               $validator_set = Array('sequence_number' => 'required');

            $validator_msg = array('sequence_number.required' => 'Sequence Number is Empty');
                $existingReadingRecord = 0;
                $existingPaymentRecord = 0;
                foreach ($dataReading as $data) {
                    $tot_amount = 0;
                    $advance_amt = 0;
                    $payment_status = 0;
                    $seqNoCheck = '';
                    $seq_no = $data['sequence_no'];

                    $seqNoCheck = ConsumerConnection::where('sequence_number','=',"$seq_no")->first();

                if($seqNoCheck){

                    $CheckReading = MeterReading::where('sequence_number',$seq_no)->get();
                    if($CheckReading->isEmpty()){
                        if($data['total_amount'] < 0){
                            $tot_amount = 0;
                            $advance_amt = abs($data['total_amount']);
                            $payment_status = 1;
                        }else{
                            $tot_amount = $data['total_amount'];
                            $advance_amt = 0;
                            $payment_status = 0;
                        }
                        $consumerReading['sequence_number'] = $seq_no;
                        $consumerReading['consumer_name'] = $seqNoCheck->name;
                        $consumerReading['date_of_reading'] = isset($data['date_of_reading']) ?  Carbon::createFromFormat('d/m/Y H:i:s', $data['date_of_reading'].' 00:00:00') : NULL;
                        $consumerReading['door_no'] = $seqNoCheck->door_no;
                        $consumerReading['meter_no'] = $seqNoCheck->meter_no;
                        $consumerReading['meter_status'] = $seqNoCheck->meter_status_id;
                        $consumerReading['current_reading'] = $data['current_reading'];
                        $consumerReading['ward_id'] = $seqNoCheck->ward_id;
                        $consumerReading['corpward_id'] = $seqNoCheck->corp_ward_id;
                        $consumerReading['no_of_flats'] = $seqNoCheck->no_of_flats;
                        $consumerReading['meter_no'] = $seqNoCheck->meter_no;
                        $consumerReading['total_due'] = 0;
                        $consumerReading['total_amount'] = $tot_amount;
                        $consumerReading['advance_amount'] = $advance_amt;
                        $consumerReading['active_record'] = 1;
                        $consumerReading['extra_amount'] = 0;
                        $consumerReading['meter_change_status'] = 0;
                        $consumerReading['payment_status'] = $payment_status;
                     //   $validator = Validator::make($consumerReading, $validator_set, $validator_msg);
                      //  if ($validator->fails()) {
                      //      return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
                      //  }
                        MeterReading::insert($consumerReading);
                       // $consumerReadingInsert[] = $consumerReading;
                    }else {
                        $existingReadingRecord++;
                    }
                }
               // else{
               //     \DB::table('test')->insert(
               //         ['sequence_number' => $seq_no]
               //     );

               // }
                }
               // if(!empty($consumerReadingInsert)){
                   // try {
                  //      foreach (array_chunk($consumerReadingInsert,1000) as $recordreading) {
                  //          MeterReading::insert($recordreading);
                            //\DB::table('consumer_connection')->insert($recordconn);
                  //      }
                  //  } catch (\Exception $e) {
                  //      return redirect()->back()->with("error","Something went wrong. Please try again");
                  //  }
               // }
                return redirect()->back()->with('success','Imported Successfully. Total number of records in excel '.$total_count.'------- Duplicate Reading data '.$existingReadingRecord);
            }
            return redirect()->back()->with("error","Selected file is empty");
        }
        return redirect()->back()->with("error","Please select file to Import");
    }

    public function downloadExcel($type){
        $meterreading = MeterReading::get()->toArray();
        return \Excel::create('expertphp_demo', function($excel) use ($meterreading) {
            $excel->sheet('sheet name', function($sheet) use ($meterreading)
            {
                $sheet->fromArray($meterreading);
            });
        })->download($type);
    }


    public function importWardCorp(Request $request){

        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        if($request->hasFile('ward_file')){
            $path = $request->file('ward_file')->getRealPath();
            $file = $request->file('ward_file')->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($extension, array('xls', 'xlsx','csv'))) {
                $validator->errors()->add('ward_file', 'Only csv, xls and xlsx file accepted!');
                return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
            }
            $data = \Excel::load($path)->get();
           // $data = \Excel::load($path, function($reader) {
            //})->get();
            if($data->count()){
                $dataWard = $data->toArray();
                foreach ($dataWard as $data) {
                    $id = '';
                    if($data['ward_id'] != NULL && $data['ward_id'] != '#'){
                    $wardCheck = Ward::where('ward_name',$data['ward_id'])->first();
                        if(!$wardCheck){
                            $record['ward_name'] = $data['ward_id'];
                            $record['zone_id'] = 1;
                            $id = Ward::insert($record);
                            if($data['corp_ward_id'] != NULL && $data['corp_ward_id'] != '#'){
                                $corpwardCheck = CorpWard::where('corp_name',$data['corp_ward_id'])->get();
                                if($corpwardCheck->isEmpty()){
                                    $recordCorp['corp_name'] = $data['corp_ward_id'];
                                    $recordCorp['ward_id'] = $id;
                                    $cid = CorpWard::insert($recordCorp);
                                }
                            }
                        }else {
                            if($data['corp_ward_id'] != NULL && $data['corp_ward_id'] != '#'){
                                $corpwardCheck = CorpWard::where('corp_name',$data['corp_ward_id'])->get();
                                if($corpwardCheck->isEmpty()){
                                    $recordCorp['corp_name'] = $data['corp_ward_id'];
                                    $recordCorp['ward_id'] = $wardCheck->id;
                                    $cid = CorpWard::insert($recordCorp);
                                }
                            }

                        }
                    }
                }

                return redirect()->back()->with('success','Imported Successfully');
            }
            return redirect()->back()->with("error","Selected file is empty");
        }
        return redirect()->back()->with("error","Please select file to Import");
    }

    public function importPaymentInfoToDB(Request $request) {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $consumerPayment = array();
        $consumerPaymentInsert = array();
        $imported_file_keys = array();
        $validator = Validator::make($request->all(), [
        ]);
        if ($request->hasFile('payment_file')) {
            $path = $request->file('payment_file')->getRealPath();
            $file = $request->file('payment_file')->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($extension, array('xls', 'xlsx', 'csv'))) {
                $validator->errors()->add('payment_file', 'Only csv, xls and xlsx file accepted!');
                return redirect()->back()->withInput(Input::all())->with("errors", $validator->errors());
            }
            $data = \Excel::load($path)->get();
            if ($data->count()) {
                $paymentData = $data->toArray();
                $imported_file_keys = array_keys($paymentData[0]);
                //$import_file_keys = Array('sl_no','center_name','name','tran_no','tran_date','pay_mode','sequence_no','net_amount');
                $import_file_keys = Array('center_name', 'name', 'tran_no', 'tran_date', 'pay_mode', 'sequence_no', 'net_amount');
                $key_error = array_diff($import_file_keys, $imported_file_keys);
                if (sizeof($key_error) > 0) {
                    $validator->errors()->add('payment_file', 'Columns doesnot match in the uploaded file');
                    return redirect()->back()->withInput(Input::all())->with("errors", $validator->errors());
                }

                $total_records_in_excel = count($paymentData);
                $total_count = 0;
                $skipped = 0;
                $skipped_array = array();
                // $validator_set = Array('sequence_number' => 'required');
                // $validator_msg = array('sequence_number.required' => 'Sequence Number is Empty');
                foreach ($paymentData as $data) {
                    $activeOneRecord = '';
                    $activeZeroRecord = '';
                    $seqNoCheck = '';
                    $extraAmt = 0;
                    $transaction_date = '';
                    $paymentMode = '';
                    $seq_no = trim($data['sequence_no']);
                    $consumer_name = str_replace(' ','',$data['name']);
                    $transaction_no = trim($data['tran_no']);
                    (($data['pay_mode'] == 'Cash') ? $paymentMode = 1 : $paymentMode = 2);
                    $transactionNoCheck = DB::table('bank_info')->where('transaction_number', '=', "$transaction_no")->first();
                    if (!$transactionNoCheck) {
                        echo $seq_no."<br>";
                        $totalPaidExcl = $data['net_amount'];
                        $trans_date = $data['tran_date'];
                        $ReadingRecordObj = MeterReading::where('sequence_number', 'like', "$seq_no")
                                ->whereRaw("REPLACE(consumer_name, ' ','') like \"".$consumer_name."\"") 
                                ->orderBy('id','DESC')->get();
                        $transaction_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['tran_date'])));
                        $ReadingRecordCount = $ReadingRecordObj->count();
                        if ($ReadingRecordCount > 0) {
                            $ReadingRecordDetail = $ReadingRecordObj->toArray();
                            if($ReadingRecordDetail[0]['import_flag'] == 1){
                                $extra_amount = $ReadingRecordDetail[0]['extra_amount'] - $totalPaidExcl;
                            }else{
                                $extra_amount = $ReadingRecordDetail[0]['total_amount'] - $totalPaidExcl;
                            } 
                            
                            $flag = 0;
                            //FINDING METER READING ID
                            if($ReadingRecordDetail[0]['date_of_reading'] <= $transaction_date){
                                $meter_reading_id = $ReadingRecordDetail[0]['id'];
                            }else if($ReadingRecordCount > 1 && $ReadingRecordDetail[1]['date_of_reading'] <= $transaction_date){
                                $meter_reading_id = $ReadingRecordDetail[1]['id'];
                            }else{
                                $oldMeterData = OldMeterReading::where('sequence_number','like',"$seq_no")
                                        ->where('date_of_reading','<=',"$transaction_date")
                                        ->where('is_olddata',0)
                                        ->orderBy('id','DESC')
                                        ->first();
                                if($oldMeterData){
                                    $flag = 1;
                                   $meter_reading_id = $oldMeterData->id; 
                                }else{
                                   $meter_reading_id = $ReadingRecordDetail[0]['id']; 
                                }
                            }
                            $tableName = ($flag == 1) ? "old_meter_reading" : "meter_reading";
                            
                            //UPDATING PAYMENTS
                            DB::table($tableName)->where('id',$meter_reading_id)                                   
                                    ->update(['payment_status' => 1, 'import_flag' => 1]);
                            
                            DB::table('meter_reading')->where('sequence_number','like', "$seq_no")
                                    ->where('active_record', 1)
                                    ->update(['extra_amount' => $extra_amount, 'import_flag' => 1]);
                            
                            
                            $paymentId = DB::table('payment_history')->insertGetId(
                                            ['sequence_number' => $seq_no, 'meter_reading_id' => $meter_reading_id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                    );
                            
                            DB::table('bank_info')->insert(
                                            ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                            );
                            $total_count ++;                            
                        }else{
                            //IF NO RECORD FOUND ADD TO SUSPENSE
                            if($ReadingRecordCount > 2){
                                $reason = "More than 2 meter reading record";
                            }else{
                                $reason = "Sequence-name combination not found";
                            }                            
                            $suspense_data[] = array('sequence_number' => $seq_no,
                                                'consumer_name' => $data['name'],
                                                'paid_amount' => $data['net_amount'],
                                                'transaction_number' =>$transaction_no,
                                                'reason_to_skip' => $reason);                            
                            $skipped_array[] = $seq_no;
                            $skipped++;
                        }
                    }
                }
                if(!empty($suspense_data)){
                    SuspensePayments::insert($suspense_data);
                }
                return redirect()->back()->with('success', 'Successfully Imported! Total number of records pesent in excel ' . $total_count . 'out of '.$total_records_in_excel.' |'.$skipped.' records skipped: detail:'.implode(',',$skipped_array));
            }
            return redirect()->back()->with("error", "Selected file is empty");
        }
        return redirect()->back()->with("error", "Please select file to Import");
    }
    
    
    public function importPaymentInfoToDB_Old(Request $request) {

        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $consumerPayment = array();
        $consumerPaymentInsert = array();
        $imported_file_keys = array();
        $validator = Validator::make($request->all(), [
        ]);
        if ($request->hasFile('payment_file')) {
            $path = $request->file('payment_file')->getRealPath();
            $file = $request->file('payment_file')->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($extension, array('xls', 'xlsx', 'csv'))) {
                $validator->errors()->add('payment_file', 'Only csv, xls and xlsx file accepted!');
                return redirect()->back()->withInput(Input::all())->with("errors", $validator->errors());
            }
            $data = \Excel::load($path)->get();
            if ($data->count()) {
                $paymentData = $data->toArray();
                $imported_file_keys = array_keys($paymentData[0]);
                //$import_file_keys = Array('sl_no','center_name','name','tran_no','tran_date','pay_mode','sequence_no','net_amount');
                $import_file_keys = Array('center_name', 'name', 'tran_no', 'tran_date', 'pay_mode', 'sequence_no', 'net_amount');
                $key_error = array_diff($import_file_keys, $imported_file_keys);
                if (sizeof($key_error) > 0) {
                    $validator->errors()->add('payment_file', 'Columns doesnot match in the uploaded file');
                    return redirect()->back()->withInput(Input::all())->with("errors", $validator->errors());
                }

                $total_records_in_excel = count($paymentData);
                $total_count = 0;
                $skipped = 0;
                $skipped_array = array();
                // $validator_set = Array('sequence_number' => 'required');
                // $validator_msg = array('sequence_number.required' => 'Sequence Number is Empty');
                foreach ($paymentData as $data) {
                    $activeOneRecord = '';
                    $activeZeroRecord = '';
                    $seqNoCheck = '';
                    $extraAmt = 0;
                    $transaction_date = '';
                    $paymentMode = '';
                    $seq_no = trim($data['sequence_no']);
                    $consumer_name = str_replace(' ','',$data['name']);
                    $transaction_no = trim($data['tran_no']);
                    (($data['pay_mode'] == 'Cash') ? $paymentMode = 1 : $paymentMode = 2);
                    $transactionNoCheck = DB::table('bank_info')->where('transaction_number', '=', "$transaction_no")->first();
                    if (!$transactionNoCheck) {
                       

                        $ReadingRecordObj = DB::table('meter_reading')
                                ->where('sequence_number', 'like', "$seq_no")
                                ->whereRaw("REPLACE(consumer_name, ' ','') like \"".$consumer_name."\"")    
                                ->get();
                        $ReadingRecordCount = $ReadingRecordObj->count();
                        $trans_date = $data['tran_date'];
                        /*
                        $arr = explode("-", $trans_date, 2);
                        $first = $arr[0];
                         if((strlen((string)$first) == 2) || (strlen((string)$first) == 1)){
                          $transaction_date = Carbon::createFromFormat('d-m-Y H:i:s', $trans_date.' 00:00:00');
                          }else {
                          $transaction_date = Carbon::createFromFormat('Y-m-d H:i:s', $trans_date.' 00:00:00');
                          } */
                        $transaction_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $trans_date)));
                        if ($ReadingRecordCount == 1) {
                            //echo 'One loop'; exit;
                            $activeOneRecord = DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->first();
                            switch ($activeOneRecord) {
                                case ($activeOneRecord->payment_status == 1 && $activeOneRecord->advance_amount != 0 && $activeOneRecord->total_amount == 0):
                                    $totalPaidExcl = $data['net_amount'];
                                    // extra += $diffAmount
                                    (($activeOneRecord->import_flag == 1) ? $extraAmt = $activeOneRecord->extra_amount + (-$totalPaidExcl) : $extraAmt = -$totalPaidExcl);

                                    if (static::checkDuplicateTransactionNo($data['tran_no'])) {
                                        continue;
                                    }

                                    DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->update(['extra_amount' => $extraAmt, 'import_flag' => 1, 'payment_status' => 1]);
                                    $paymentId = DB::table('payment_history')->insertGetId(
                                            ['sequence_number' => $seq_no, 'meter_reading_id' => $activeOneRecord->id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                    );
                                    DB::table('bank_info')->insert(
                                            ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                                    );
                                    $total_count ++;
                                    break;

                                case ( $activeOneRecord->advance_amount == 0 && $activeOneRecord->total_amount != 0):
                                    $totalAmountDB = round($activeOneRecord->total_amount);
                                    $totalPaidExcl = $data['net_amount'];
                                    $diffAmount = $totalAmountDB - $totalPaidExcl;

                                    if ($diffAmount != 0) {
                                        // extra += $diffAmount
                                        (($activeOneRecord->import_flag == 1) ? $extraAmt = $activeOneRecord->extra_amount + (-$totalPaidExcl) : $extraAmt = $diffAmount);

                                        if (static::checkDuplicateTransactionNo($data['tran_no'])) {
                                            continue;
                                        }

                                        DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->update(['extra_amount' => $extraAmt, 'import_flag' => 1, 'payment_status' => 1]);
                                        $paymentId = DB::table('payment_history')->insertGetId(
                                                ['sequence_number' => $seq_no, 'meter_reading_id' => $activeOneRecord->id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                        );
                                        DB::table('bank_info')->insert(
                                                ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                                        );
                                    } else {
                                        (($activeOneRecord->import_flag == 1) ? $extraAmt = $activeOneRecord->extra_amount + (-$totalPaidExcl) : $extraAmt = $activeOneRecord->extra_amount);
                                        // make payment status = 1 of active_record = 1

                                        if (static::checkDuplicateTransactionNo($data['tran_no'])) {
                                            continue;
                                        }

                                        DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->update(['payment_status' => 1, 'import_flag' => 1, 'extra_amount' => $extraAmt]);
                                        $paymentId = DB::table('payment_history')->insertGetId(
                                                ['sequence_number' => $seq_no, 'meter_reading_id' => $activeOneRecord->id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                        );
                                        DB::table('bank_info')->insert(
                                                ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                                        );
                                    }
                                    $total_count ++;
                                    break;

                                case ( $activeOneRecord->advance_amount == 0 && $activeOneRecord->total_amount == 0):
                                    $totalPaidExcl = $data['net_amount'];
                                    (($activeOneRecord->import_flag == 1) ? $extraAmt = $activeOneRecord->extra_amount + (-$totalPaidExcl) : $extraAmt = -$totalPaidExcl);

                                    if (static::checkDuplicateTransactionNo($data['tran_no'])) {
                                        continue;
                                    }

                                    DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->update(['extra_amount' => $extraAmt, 'import_flag' => 1, 'payment_status' => 1]);
                                    $paymentId = DB::table('payment_history')->insertGetId(
                                            ['sequence_number' => $seq_no, 'meter_reading_id' => $activeOneRecord->id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                    );
                                    DB::table('bank_info')->insert(
                                            ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                                    );
                                    $total_count ++;


                                    break;
                                default :
                                    break;
                            }
                        } elseif ($ReadingRecordCount == 2) {
                            //if there are two records in meter reading
                            $activeOneRecord = DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->first();
                            $activeZeroRecord = DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 0)->first();
                            $dateOfReading = Carbon::createFromFormat('Y-m-d H:i:s', $activeOneRecord->date_of_reading)->toDateString();
                            // echo $dateOfReading; exit;
                            //date of reading check with payment date of excel
                            if ($dateOfReading <= $transaction_date) {
                                $totalAmountDB = round($activeOneRecord->total_amount);
                                $totalPaidExcl = $data['net_amount'];
                                $diffAmount = $totalAmountDB - $totalPaidExcl;
                                if ($diffAmount != 0) {
                                    // extra += $diffAmount
                                    (($activeOneRecord->import_flag == 1) ? $extraAmt = $activeOneRecord->extra_amount + $diffAmount : $extraAmt = $diffAmount);

                                    if (static::checkDuplicateTransactionNo($data['tran_no'])) {
                                        continue;
                                    }
                                    DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->update(['extra_amount' => $extraAmt, 'payment_status' => 1, 'import_flag' => 1]);
                                    DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 0)->update(['import_flag' => 1]);
                                    $paymentId = DB::table('payment_history')->insertGetId(
                                            ['sequence_number' => $seq_no, 'meter_reading_id' => $activeOneRecord->id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                    );
                                    DB::table('bank_info')->insert(
                                            ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                                    );
                                    $total_count ++;
                                } else {
                                    (($activeOneRecord->import_flag == 1) ? $extraAmt = $activeOneRecord->extra_amount + (-$totalPaidExcl) : $extraAmt = $activeOneRecord->extra_amount);
                                    // make payment status = 1 of active_record = 1

                                    if (static::checkDuplicateTransactionNo($data['tran_no'])) {
                                        continue;
                                    }

                                    DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->update(['payment_status' => 1, 'import_flag' => 1, 'extra_amount' => $extraAmt]);
                                    DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 0)->update(['import_flag' => 1]);
                                    $paymentId = DB::table('payment_history')->insertGetId(
                                            ['sequence_number' => $seq_no, 'meter_reading_id' => $activeOneRecord->id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                    );
                                    DB::table('bank_info')->insert(
                                            ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                                    );
                                    $total_count ++;
                                }
                            } else {
                                switch ($activeZeroRecord) {
                                    case ($activeZeroRecord->payment_status == 1 && $activeZeroRecord->advance_amount != 0 && $activeZeroRecord->total_amount == 0):
                                        $activeOneRecord = DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->first();
                                        $totalPaidExcl = $data['net_amount'];
                                        // extra += $diffAmount
                                        (($activeOneRecord->import_flag == 1) ? $extraAmt = $activeOneRecord->extra_amount + (-$totalPaidExcl) : $extraAmt = -$totalPaidExcl);

                                        if (static::checkDuplicateTransactionNo($data['tran_no'])) {
                                            continue;
                                        }

                                        DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->update(['extra_amount' => $extraAmt, 'import_flag' => 1]);
                                        DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 0)->update(['import_flag' => 1, 'payment_status' => 1]);
                                        $paymentId = DB::table('payment_history')->insertGetId(
                                                ['sequence_number' => $seq_no, 'meter_reading_id' => $activeZeroRecord->id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                        );
                                        DB::table('bank_info')->insert(
                                                ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                                        );
                                        $total_count ++;
                                        break;

                                    case ($activeZeroRecord->advance_amount == 0 && $activeZeroRecord->total_amount != 0):
                                        $activeOneRecord = DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->first();
                                        $totalAmountDB = round($activeZeroRecord->total_amount);
                                        $totalPaidExcl = $data['net_amount'];

                                        (($activeOneRecord->import_flag == 1) ? $extraAmt = $activeOneRecord->extra_amount + (-$totalPaidExcl) : $extraAmt = -$totalPaidExcl);

                                        if (static::checkDuplicateTransactionNo($data['tran_no'])) {
                                            continue;
                                        }

                                        DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->update(['import_flag' => 1, 'extra_amount' => $extraAmt]);
                                        DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 0)->update(['import_flag' => 1, 'payment_status' => 1]);
                                        $paymentId = DB::table('payment_history')->insertGetId(
                                                ['sequence_number' => $seq_no, 'meter_reading_id' => $activeZeroRecord->id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                        );
                                        DB::table('bank_info')->insert(
                                                ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                                        );
                                        $total_count ++;
                                        break;

                                    case ( $activeZeroRecord->advance_amount == 0 && $activeZeroRecord->total_amount == 0):
                                        $totalPaidExcl = $data['net_amount'];
                                        (($activeOneRecord->import_flag == 1) ? $extraAmt = $activeOneRecord->extra_amount + (-$totalPaidExcl) : $extraAmt = -$totalPaidExcl);

                                        if (static::checkDuplicateTransactionNo($data['tran_no'])) {
                                            continue;
                                        }

                                        DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 1)->update(['extra_amount' => $extraAmt, 'import_flag' => 1]);
                                        DB::table('meter_reading')->where('sequence_number', $seq_no)->where('active_record', 0)->update(['import_flag' => 1, 'payment_status' => 1]);
                                        $paymentId = DB::table('payment_history')->insertGetId(
                                                ['sequence_number' => $seq_no, 'meter_reading_id' => $activeZeroRecord->id, 'payment_date' => $transaction_date, 'total_amount' => $totalPaidExcl, 'payment_mode' => $paymentMode, 'payment_status' => 1]
                                        );
                                        DB::table('bank_info')->insert(
                                                ['payment_id' => $paymentId, 'branch_name' => $data['center_name'], 'transaction_number' => $data['tran_no']]
                                        );
                                        $total_count ++;
                                        break;
                                    default :
                                        break;
                                }
                            }
                        } else {                            
                            if($ReadingRecordCount > 2){
                                $reason = "More than 2 meter reading record";
                            }else{
                                $reason = "Sequence-name combination not found";
                            }                            
                            $suspense_data = array('sequence_number' => $seq_no,
                                                'consumer_name' => $data['name'],
                                                'paid_amount' => $data['net_amount'],
                                                'transaction_number' =>$transaction_no,
                                                'reason_to_skip' => $reason);
                            DB::table('suspense_payments')->insert($suspense_data);
                            $skipped_array[] = $seq_no;
                            $skipped++;
                            //insert missing seq no to DB
                            //echo 'Sequence number not present in meter reading table'; exit;
                        }
                        // exit;
                    }
                    else {
                         /*$suspense_data = array('sequence_number' => $seq_no,
                                                'consumer_name' => $data['name'],
                                                'paid_amount' => $data['net_amount'],
                                                'transaction_number' =>$transaction_no,
                                                'reason_to_skip' => 'Already Imported');
                         DB::table('suspense_payments')->insert($suspense_data);*/
                    }
                }
                return redirect()->back()->with('success', 'Imported Successfully. Total number of records in excel ' . $total_count . 'out of '.$total_records_in_excel.' |'.$skipped.' records skipped: detail:'.implode(',',$skipped_array));
            }
            return redirect()->back()->with("error", "Selected file is empty");
        }
        return redirect()->back()->with("error", "Please select file to Import");
    }

    private static function checkDuplicateTransactionNo($transactionNo) {
         $transactionNo = trim($transactionNo);
         $count = BankInfo::where("transaction_number",'=',"$transactionNo")->count();
         if($count==0) {
             return FALSE;
         }
         else {
            // echo "skipped <br/>";
             return TRUE;
         }
    }
    
    public function testPaymentImport(){
        return view('admin.test_payment_import');
    }

    public function getPaymentInfo(Request $request)
        {

        $seq_no=$request->sequence_number;
       // $ReadingRecordCount = DB::table('meter_reading')->where('sequence_number',$seq_no)->count();
        $ReadingRecord = DB::table('meter_reading')->where('sequence_number',$seq_no)->get();
        if($ReadingRecord)
        {
            return Response::json(['success' => 'success', 'records' => $ReadingRecord]);
        }
        else
        {
           return Response::json(['errors' => 'Error Occured']);
        }
    }

    /**
        public function importReadingInfoToDB(Request $request){

        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $consumerReading = array();
        $consumerPayment = array();
        $consumerReadingInsert = array();
        $consumerPaymentInsert = array();
      //  $duplicateSeqInConnection = array();
       // $duplicateSeqInAddress = array();

        $validator = Validator::make($request->all(), [
        ]);
        if($request->hasFile('reading_file')){
            $path = $request->file('reading_file')->getRealPath();
            $file = $request->file('reading_file')->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($extension, array('xls', 'xlsx','csv'))) {
                $validator->errors()->add('reading_file', 'Only csv, xls and xlsx file accepted!');
                return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
            }
            $data = \Excel::load($path)->get();
           // $data = \Excel::load($path, function($reader) {
            //})->get();
            if($data->count()){
                $dataReading = $data->toArray();

                $imported_file_keys = array_keys($dataReading[0]);

                $total_count = count($dataReading);

            //    $import_file_keys = Array('sequence_no','date_of_reading','current_reading','total_due','total_amount');

               $validator_set = Array('sequence_number' => 'required');

            $validator_msg = array('sequence_number.required' => 'Sequence Number is Empty');

          //  $key_error = array_diff($import_file_keys, $imported_file_keys);
          //  if (sizeof($key_error) > 0) {
           //     $validator->errors()->add('reading_file', 'Columns doesnot match in the uploaded file');
           //     return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
           // }
                $existingReadingRecord = 0;
                $existingPaymentRecord = 0;
                foreach ($dataReading as $data) {

                    $seq_no = '';
                    $seqNoCheck = '';
                    $seq_no = $data['sequence_no'];

                    $seqNoCheck = ConsumerConnection::where('sequence_number',$seq_no)->first();
                   // if(!$seqNoCheck){
                    //    return redirect()->back()->with("error","For Sequence - ".$seq_no." Consumer Information needs to be added");
                    //}
                if($seqNoCheck){
                    $CheckReading = MeterReading::where('sequence_number',$seq_no)->get();
                    if($CheckReading->isEmpty()){
                        $consumerReading['sequence_number'] = $seq_no;
                        $consumerReading['consumer_name'] = $seqNoCheck->name;
                        $consumerReading['date_of_reading'] = isset($data['date_of_reading']) ?  Carbon::createFromFormat('d/m/Y H:i:s', $data['date_of_reading'].' 00:00:00') : NULL;
                        $consumerReading['door_no'] = $seqNoCheck->door_no;
                      //  $consumerReading['bill_no'] = $data['phone'];
                        $consumerReading['meter_no'] = $seqNoCheck->meter_no;
                        $consumerReading['meter_status'] = $seqNoCheck->meter_status_id;
                      //  $consumerReading['meter_rent'] = $data['meter_rent'];
                       // $consumerReading['payment_due_date'] = isset($data['meter_sanctioned_date']) ?  Carbon::createFromFormat('d/m/Y H:i:s', $data['meter_sanctioned_date'].' 00:00:00') : NULL;
                      //  $consumerReading['previous_billing_date'] = isset($data['previous_billing_date']) ?  Carbon::createFromFormat('d/m/Y H:i:s', $data['previous_billing_date'].' 00:00:00') : NULL;
                      //  $consumerReading['total_unit_used'] = $data['total_unit_used'];
                      //  $consumerReading['no_of_days_used'] = $data['no_of_days_used'];
                      //  $consumerReading['previous_reading'] = $data['meter_no'];
                        $consumerReading['current_reading'] = $data['current_reading'];
                      //  $consumerReading['agent_id'] = $data['meter_no'];
                        $consumerReading['ward_id'] = $seqNoCheck->ward_id;
                        $consumerReading['corpward_id'] = $seqNoCheck->corp_ward_id;
                        $consumerReading['no_of_flats'] = $seqNoCheck->no_of_flats;
                        $consumerReading['meter_no'] = $seqNoCheck->meter_no;
                       // $consumerReading['water_charge'] = $data['meter_no'];
                       // $consumerReading['supervisor_charge'] = $data['supervisor_charge'];
                       // $consumerReading['other_charges'] = $data['other_charges'];
                      //  $consumerReading['refund_amount'] = $data['refund_amount'];
                      //  $consumerReading['other_title_charge'] = $data['other_title_charge'];
                      //  $consumerReading['fixed_charge'] = $data['fixed_charge'];
                      //  $consumerReading['penalty'] = $data['penalty'];
                      //  $consumerReading['cess'] = $data['cess'];
                      //  $consumerReading['ugd_cess'] = $data['ugd_cess'];
                      //  $consumerReading['arrears'] = $data['arrears'];
                        $consumerReading['total_due'] = $data['total_due'];
                      //  $consumerReading['round_off'] = $data['round_off'];
                      //  $consumerReading['three_month_average'] = $data['three_month_average'];
                        $consumerReading['total_amount'] = $data['total_amount'];
                        $consumerReading['active_record'] = 1;
                        $consumerReading['extra_amount'] = 0;
                        $consumerReading['meter_change_status'] = 0;
                        $consumerReading['payment_status'] = 0;
                        $validator = Validator::make($consumerReading, $validator_set, $validator_msg);
                        if ($validator->fails()) {
                          //  return back()->withInput(Input::all())->with('error', $validator->errors());
                            return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
                        }
                      //  $consumerReading['connection_type_id'] = ConnectionType::where('connection_name',$consumerReading['connection_type'])->value('id');
                      //  $consumerReading['ward_id'] = Ward::where('ward_name',$consumerReading['ward_name'])->value('id');
                     //   $consumerReading['corpward_id'] =  CorpWard::where('corp_name',$consumerReading['corp_ward'])->value('id');
                      //  $consumerReading['connection_status_id'] = ConnectionStatus::where('status','like', '%' . $consumerReading['connection_status'] . '%')->value('id');
                    //    $consumerReading['meter_status'] =    MeterStatus::where('meter_status',$consumerReading['meter_status_name'])->value('id');

                      //  unset($consumerReading['ward_name']);
                     //   unset($consumerReading['corp_ward']);
                      //  unset($consumerReading['connection_type']);
                      //  unset($consumerReading['connection_status']);
                     //   unset($consumerReading['meter_status_name']);
                        $consumerReadingInsert[] = $consumerReading;
                    }else {
                      //  $duplicateSeqInConnection[] = $seq_no;
                        $existingReadingRecord++;
                    }
                }else{
                    \DB::table('test')->insert(
                        ['sequence_number' => $seq_no]
                    );

                }

                  /*  $CheckPayment = ConsumerAddress::where('sequence_number',$seq_no)->get();
                    if($CheckPayment->isEmpty()){
                        $consumerPayment['sequence_number'] = $seq_no;
                        $consumerPayment['meter_reading_id'] = $data['meter_reading_id']; // keep flag in reading table insertId and use to get reading id. next import clear the reading id and set again
                        $consumerPayment['payment_date'] = $data['payment_date'];
                        $consumerPayment['total_amount'] = $data['total_amount'];
                        $consumerPayment['payment_mode'] = $data['payment_mode'];
                        $consumerPayment['payment_status'] = $data['payment_status'];
                        $consumerPayment['premises_zip'] = $data['premises_zip'];

                        $consumerPaymentInsert[] = $consumerPayment;

                    }else {
                       // $duplicateSeqInAddress[] = $seq_no;
                        $existingPaymentRecord++;
                    }  */
              /**  }
                if(!empty($consumerReadingInsert)){
                   // try {
                        foreach (array_chunk($consumerReadingInsert,1000) as $recordreading) {
                            MeterReading::insert($recordreading);
                            //\DB::table('consumer_connection')->insert($recordconn);
                        }
                  //  } catch (\Exception $e) {
                  //      return redirect()->back()->with("error","Something went wrong. Please try again");
                  //  }
                }

             /*   if(!empty($consumerPaymentInsert)){
                    try {
                        foreach (array_chunk($consumerPaymentInsert,1000) as $recordpay) {
                            BillPayment::insert($recordpay);
                            //\DB::table('consumer_address')->insert($record);
                        }
                    } catch (\Exception $e) {
                        return redirect()->back()->with("error","Something went wrong. Please try again");
                    }
                }
              return redirect()->back()->with('success','Imported Successfully. Total number of records in excel '.$total_count.'------- Duplicate Reading data '.$existingReadingRecord.'------- Duplicate Payment data '.$existingPaymentRecord);

              *  */
              /**  return redirect()->back()->with('success','Imported Successfully. Total number of records in excel '.$total_count.'------- Duplicate Reading data '.$existingReadingRecord);
            }
            return redirect()->back()->with("error","Selected file is empty");
        }
        return redirect()->back()->with("error","Please select file to Import");
    } */

    public function importOldPaymentData(Request $request){
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        DB::connection()->disableQueryLog();
        $time1 = date("Y-m-d H:i:s");
        if($request->hasFile('payment_file')){
            $file = Input::file('payment_file');
            $name = time() . '-' . $file->getClientOriginalName();
            $moved = $file->move(public_path() . '/uploads/csv/payment', $name);
            $data = \Excel::load($moved)->get()->toArray();//dd($data);
            /*$success = $this->file_get_contents_chunked($moved, 4096, function($chunk, &$handle, $iteration) {
               echo "<pre>";print_r($chunk);echo "</pre>";
                
            }); */
           foreach($data as $payment_data){
               $transaction_no = $payment_data['refno'];
               $seq_no = $payment_data['sequence_number'];
               //$banck_info = BankInfo::where('transaction_number','like',"$transaction_no")->first();  
               $seqNoCheck = ConsumerConnection::where('sequence_number','like',"$seq_no")->first();
                if($payment_data['sequence_number'] == "" || $payment_data['sequence_number'] == "NULL" || $transaction_no == "" || !$seqNoCheck){
                    $unknown_payments[] = $payment_data;
                }else{
                    $date_of_payment = date("Y-m-d H:s:i", strtotime($payment_data['date_of_payment']));
                    $db_input_data = array('sequence_number' => $payment_data['sequence_number'],
                                           'meter_reading_id' => NULL,
                                           'payment_date' => $date_of_payment,
                                           'total_amount' => $payment_data['total_amount'],
                                           'payment_mode' => 1,
                                           'payment_status' => 1,
                                           'is_olddata' => 1);

                    $payment_id = PaymentHistory::create($db_input_data)->id;

                    $bank_info_data[] = array('payment_id' => $payment_id,
                                              'bank_name' => $payment_data['bank_name'],
                                              'branch_name' => $payment_data['branchcenter_name'],
                                              'transaction_number' => $transaction_no,
                                              'is_olddata' => 1);
                }
            }

            if(!empty($bank_info_data)){
               try {
                   foreach (array_chunk($bank_info_data,1000) as $bank_info) {
                       BankInfo::insert($bank_info);
                   }

               } catch (\Exception $e) {
                   return redirect()->back()->with("error","Something went wrong while adding bank info. Please try again!".$e->getMessage());
               }
            }

            if(!empty($unknown_payments)){
               try {
                   foreach (array_chunk($unknown_payments,1000) as $unknown_payments_info) {
                       UnknownPayments::insert($unknown_payments_info);
                   }

               } catch (\Exception $e) {
                   return redirect()->back()->with("error","Something went wrong. Please try again!".$e->getMessage());
               }
            }
            $time2 = date("Y-m-d H:i:s");
            $hourdiff = round((strtotime($time2) - strtotime($time1))/60, 1);
            return redirect()->back()->with('success','Imported Successfully. Start time:'.$time1.'End time:'.$time2.', Execution time:'.$hourdiff.' Minutes');
        }
    }    
   
    public function importOldReadingInfoToDB(Request $request){
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        DB::connection()->disableQueryLog();
        $time1 = date("Y-m-d H:i:s");

        $consumerReading = array();
        $unknown = array();

        $validator = Validator::make($request->all(), []);

        if($request->hasFile('reading_old_file')){
            //$path = $request->file('reading_old_file')->getRealPath();
            //$file = $request->file('reading_old_file')->getClientOriginalName();

            $file = Input::file('reading_old_file');
            $name = time() . '-' . $file->getClientOriginalName();

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            if (!in_array($extension, array('xls', 'xlsx','csv'))) {
                $validator->errors()->add('reading_old_file', 'Only csv, xls and xlsx file accepted!');
                return redirect()->back()->withInput(Input::all())->with("errors",$validator->errors());
            }
            $meter_status = MeterStatus::all()->toArray();
            foreach($meter_status as $status){
                $status_array[$status["meter_status"]] = $status["id"];
            }
            $status_array["NL"] = 1;
            $insertionCount = 0;
            
            //$data = \Excel::load($path)->get()->toArray();//dd($data);
            $moved = $file->move(public_path() . '/uploads/csv', $name);
            $data = \Excel::load($moved)->get()->toArray();

            $imported_file_keys = array_keys($data[0]);
            $validator_set = Array('sequence_number' => 'required');
            $validator_msg = array('sequence_number.required' => 'Sequence Number is Empty');
            foreach($data as $val){
                $tot_amount = 0;
                $advance_amt = 0;
                $payment_status = 0;
                $seqNoCheck = '';
                $seq_no = $val['sequence_number'];
                $seqNoCheck = ConsumerConnection::where('sequence_number','like',"$seq_no")->first();
                if($seqNoCheck && array_key_exists($val['meter_status'],$status_array)){
                    $meter_status = $status_array[$val['meter_status']];
                    $date_of_reading = ($val['date_of_reading'] == "0000-00-00") ? NULL : date("Y-m-d H:s:i", strtotime(str_replace('/','-',$val['date_of_reading'])));
                    $previous_billing_date = ($val['previous_billing_date'] == "0000-00-00") ? NULL : date("Y-m-d H:s:i", strtotime(str_replace('/','-',$val['previous_billing_date'])));

                    $consumerReading[] = array('sequence_number' => $seq_no,
                                             'consumer_name' => $seqNoCheck->name,
                                             'date_of_reading' => $date_of_reading,
                                             'door_no' => $seqNoCheck->door_no,
                                             'meter_no' => $seqNoCheck->meter_no,
                                             'meter_status' => $meter_status,
                                             'current_reading' => $val['current_reading'],
                                             'previous_reading' => $val['previous_reading'],
                                             'total_unit_used' => $val['total_unit_used'],
                                             'previous_billing_date' => $previous_billing_date,
                                             'ward_id' => $seqNoCheck->ward_id,
                                             'corpward_id' => $seqNoCheck->corp_ward_id,
                                             'no_of_flats' => $seqNoCheck->no_of_flats,
                                             'total_due' => 0,
                                             'water_charge' => $val['water_charge'],
                                             'other_charges' => $val['other_charge'],
                                             'total_amount' => $val['total_amount'],
                                             'penalty' => $val['penalty'],
                                             'arrears' => $val['arrears'],
                                             'refund_amount' => 0,
                                             'advance_amount' => 0,
                                             'active_record' => 0,
                                             'extra_amount' => 0,
                                             'meter_change_status' => 0,
                                             'payment_status' => 0,
                                             'is_olddata' => 1);
                    $insertionCount++;
                }else{
                    $unknown[] = $val;
                }
            }

            if(!empty($consumerReading)){
               try {
                   foreach (array_chunk($consumerReading,2500) as $recordreading) {
                       OldMeterReading::insert($recordreading);
                   }
               } catch (\Exception $e) {
                   return redirect()->back()->with("error","Something went wrong. Please try again!".$e->getMessage());
               }
            }
            if(!empty($unknown)){
               try {
                 if(count($unknown) > 2500){
                   foreach (array_chunk($unknown,2500) as $unknown_reading) {
                       UnknownMeterReading::insert($unknown_reading);
                   }
                 }else{
                      UnknownMeterReading::insert($unknown);
                 }

               } catch (\Exception $e) {
                   return redirect()->back()->with("error","Couldnt keep the track!!".$e->getMessage());
               }
            }
            $time2 = date("Y-m-d H:i:s");
            $hourdiff = round((strtotime($time2) - strtotime($time1))/60, 1);
            return redirect()->back()->with('success','Imported Successfully. Start time:'.$time1.'End time:'.$time2.', Total number of records inserted:'.$insertionCount.', Execution time:'.$hourdiff.' Minutes');
        }else{
          return redirect()->back()->with("error","Please select file to Import");
        }
    }

    public function format_date($date){
        $standardDate = (strlen($date) < 10) ? substr($date,6,2) . "-" . substr($date,3,2) . "-" . substr($date,0,2) : str_replace('/','-',$date);
        $d=strtotime($standardDate);
        $format = date("Y-m-d h:s:i", $d);
        return $format;
    }

    public function checkDateFormat($date){
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return true;
        } else {
            return false;
        }
    }

    public function importreadingInfoInfile(Request $request) {
        if ($request->hasFile('reading_file')) {
            $path = $request->file('reading_file')->getRealPath();
            $extension = $request->file('reading_file')->getClientOriginalExtension();
            if ($extension != 'csv') {
                $validator->errors()->add('reading_file', 'Only csv file accepted!');
                return redirect()->back()->withInput(Input::all())->withErrors($validator->errors());
            }
            $file_name = 'ReadingDump';
            $file_path = 'public/uploads/import/';
            $file_name = $file_name . "." . $extension;
            $request->file('reading_file')->move($file_path, $file_name);
            $full_path = $file_path . $file_name;
            $query = "LOAD DATA LOCAL INFILE '" . $full_path . "' INTO TABLE test FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\\n'";
            $count_insert = DB::connection()->getpdo()->exec($query);
            echo $count_insert;
        }
    }
    
    public function file_get_contents_chunked($file,$chunk_size,$callback)
{
    try
    {
        $handle = fopen($file, "r");
        $i = 0;
        while (!feof($handle))
        {
            call_user_func_array($callback,array(fread($handle,$chunk_size),&$handle,$i));
            $i++;
        }

        fclose($handle);
    }
    
    catch(Exception $e)
    {
         trigger_error("file_get_contents_chunked::" . $e->getMessage(),E_USER_NOTICE);
         return false;
    }

    return true;
}

public function test(Request $request) {
        $success = file_get_contents_chunked("my/large/file", 4096, function($chunk, &$handle, $iteration) {
            
        });

        if (!$success) {
            
        }
    }
    
    public static function paymentImportErrorCorrection() {
        //PORPOSE : CORRECT DOUBLE INSERTION
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        //FETCHING DUPLICATE RECORDS FROM BANK INFO
        $bank_info_result = BankInfo::select('transaction_number')
                        ->addSelect(DB::raw('COUNT(*) as cnt'))
                        ->whereNotNull('transaction_number')
                        ->where('is_olddata',0)
                        ->groupBy('transaction_number')
                        ->Having('cnt', '>', 1)->get()->toArray();

        if (!empty($bank_info_result)) {
            foreach ($bank_info_result as $data) {
                $transaction_number = $data['transaction_number'];
                //FINDING THE TRASACTION NUMBER IN DB
                $bank_info_detail = BankInfo::select('bank_info.id as bank_info_id', 'payment_history.id as payment_history_id', 'bank_info.transaction_number', 'payment_history.total_amount', 'payment_history.meter_reading_id', 'payment_history.sequence_number', 'payment_history.created_at')
                                ->leftJoin('payment_history', 'payment_history.id', '=', 'bank_info.payment_id')
                                ->where('bank_info.transaction_number', 'like', $transaction_number)
                                ->orderBy('payment_history.id')
                                ->get()->toArray();
                $correction_amount = $bank_info_detail[0]['total_amount'];
                $meter_reading_id = $bank_info_detail[0]['meter_reading_id'];
                $paid_amount = $bank_info_detail[0]['total_amount'];
                //UPDATING METER READING TABLE (EXTRA_AMOUNT)
                $meter_reading_detail = MeterReading::where('id', "$meter_reading_id")->first()->toArray();
                $meter_reading_total_amount = $meter_reading_detail['total_amount'];
                $meter_reading_extra_amount = $meter_reading_total_amount - $paid_amount;

                $meterReadingCorrectionArray = array('extra_amount' => $meter_reading_extra_amount);
                MeterReading::where('id', $meter_reading_id)->update($meterReadingCorrectionArray);
                unset($bank_info_detail[0]);
                $bank_info_ids = array();
                $payment_history_ids = array();

                //DELETING DUPLICATE ENTRIES FROM PAYMENT HISTORY AND BANK INFO            
                $payment_history_ids = array_column($bank_info_detail, 'payment_history_id');
                PaymentHistory::whereIn('id', $payment_history_ids)->delete();

                //$bank_info_ids = array_column($bank_info_detail, 'bank_info_id');        
                //BankInfo::whereIn('id',$bank_info_ids)->delete();
            }
            dd("SCRIPT EXECUTED SUCCESSFULLY!!");
        } else {
            dd("COULD NOT CORRECT YOUR DATA!! TRY AGAIN");
        }
    }        
}
