<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\DeleteBill;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Response;
use Carbon\Carbon;
use App\Models\MeterReading;
use App\Models\OldMeterReading;
use App\Models\PaymentHistory;
use App\Models\ConsumerConnection;
use DB;
use Illuminate\Support\Facades\Log;

class CommonController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        return view('admin.delete_record');
    }

    public static function moveOldMeeterReading() {
        $meterReadingObj = MeterReading::getOldMeeterRecords();
        DB::beginTransaction();
        $count = 0;
        $file = "old_meter_reading_move_" . date("d-m-Y_H-i") . ".log";

        try {
            foreach ($meterReadingObj as $obj) {

                $oldData = $obj->toArray();
                //print_r($oldData);
                $inserted_data = OldMeterReading::create($oldData);

                //print_r($obj);

                if (!empty($inserted_data)) {
                    MeterReading::where('id', $oldData['id'])->delete();
                    $details = "id:" . $oldData['id'] . " sequence_number:" . $oldData['sequence_number'];
                    $log = new \Monolog\Logger(__METHOD__);
                    $log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path() . '/logs/' . $file));
                    $log->addInfo($details);

                    $count++;
                } else {

                }
            }
        } catch (Exception $e) {
            DB::rollback();
        }
        DB::commit();

        echo $count . " Records removed from meter_reading table <br/> Log saved at " . storage_path() . '/logs/' . $file;

        exit();
    }

    public static function getFaultyData($id) {
        //$faultyData = array('51738','31290','36666','13373');
        //$faultyData = array('50418','60704','34773');
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
       
        //FETCHING ALL ERRANIOUS DADTA
        if($id == 1){
            $faultyData = DB::select("SELECT meter_reading.sequence_number,meter_reading.total_amount,meter_reading.water_charge,meter_reading.penalty,meter_reading.other_charges,meter_reading.no_of_days_used,payment_info.total,ROUND(meter_reading.total_amount) AS m_total,ROUND(meter_reading.extra_amount) AS extra FROM `meter_reading`
                                JOIN (SELECT sequence_number,SUM(total_amount) AS total FROM `payment_history` WHERE is_olddata = 0 GROUP BY sequence_number) AS payment_info ON payment_info.sequence_number= meter_reading.sequence_number
                                WHERE meter_reading.active_record = 1
                                AND (meter_reading.current_reading >= meter_reading.previous_reading OR (meter_reading.current_reading = 0 AND meter_reading.previous_reading > 0))
                                AND meter_reading.water_charge <> 0.00
                                AND ROUND(meter_reading.total_amount) - payment_info.total <> ROUND(meter_reading.extra_amount)
                                AND ROUND( meter_reading.water_charge + other_charges + penalty ) <> ROUND( meter_reading.total_amount )");
        }else if($id == 2){
            $faultyData = DB::select("SELECT meter_reading.sequence_number,meter_reading.total_amount,meter_reading.water_charge,meter_reading.penalty,meter_reading.other_charges,meter_reading.no_of_days_used,payment_info.total,ROUND(meter_reading.total_amount) AS m_total,ROUND(meter_reading.extra_amount) AS extra,meter_reading.payment_status,meter_reading.import_flag FROM `meter_reading`
                                JOIN (SELECT sequence_number,SUM(total_amount) AS total FROM `payment_history` WHERE is_olddata = 0 GROUP BY sequence_number) AS payment_info ON payment_info.sequence_number= meter_reading.sequence_number
                                WHERE meter_reading.active_record = 1
                                AND (meter_reading.current_reading >= meter_reading.previous_reading OR (meter_reading.current_reading = 0 AND meter_reading.previous_reading > 0))
                                AND meter_reading.water_charge <> 0.00
                                AND ROUND(meter_reading.total_amount) - payment_info.total <> ROUND(meter_reading.extra_amount)
                                AND meter_reading.import_flag = 1");
        }else{
            dd("WRONG ID");
        }

        $output_Data = array();
        if (!empty($faultyData)) {
            foreach ($faultyData as $data) {
                $sequence_number = $data->sequence_number;
                //FETCHING FIRST RECORDS EXTRA AMOUNT OR ADVANCE AMOUNT
                $firstREc = MeterReading::select('total_amount','extra_amount', 'advance_amount')->where('sequence_number', 'like', "$sequence_number")->whereNull('bill_no')->first()->toArray();

                /*if($data->other_charges == NULL || $data->other_charges == "0.00" || $data->other_charges == ""){
                    $skip_othercharge_update = 0;

                    $total = round($firstREc['extra_amount'] + $firstREc['total_amount'] - $firstREc['advance_amount'] + $data->water_charge+$data->penalty);
                    if($total == $data->total_amount){
                        $skip_othercharge_update = 1;
                        continue;
                    }else{
                        echo $sequence_number ."<br>";
                        continue;
                    }

                    if($skip_othercharge_update == 0){
                        $other_charge = ceil($data->no_of_days_used / 30);
                        MeterReading::where('sequence_number', 'like', "$sequence_number")
                                ->where('active_record', 1)->update(['other_charges'=>$other_charge]);
                    }
                }*/

                //FETCHING SUM OF DEMANT I.E NET AMOUNT TO BE PAID BY CONSUMER
                $correctionFieldObj = MeterReading::select(DB::raw("SUM(water_charge) as wc, SUM(other_charges) as oc, SUM(penalty) as penalty"))
                                ->where('sequence_number', 'like', "$sequence_number")
                                ->groupBy('sequence_number')->first()->toArray();

                //FETCHING TOTAL BILL PAID BY THE CONSUMER SO FAR
                $totalPaymentsObj = PaymentHistory::select(DB::raw('SUM(total_amount) as paid_amount'))
                                ->addSelect('sequence_number')
                                ->where('sequence_number', 'like', "$sequence_number")
                                ->where('is_olddata', 0)
                                ->groupBy('sequence_number')->first()->toArray();

                if(!empty($correctionFieldObj)){
                    $demand = $correctionFieldObj['oc'] + $correctionFieldObj['wc'] + $correctionFieldObj['penalty'];
                }else{
                    $demand = 0;
                }

                $previous_carry = $firstREc['total_amount'] - $firstREc['advance_amount'];
                $total_bill = $demand + ($previous_carry);

                //CALCULATOIN NET OUTSTANDING AMOUNT
                $updatedata['extra_amount'] = round($total_bill) - $totalPaymentsObj['paid_amount'];
                
                /*  UN COMMENT AND RE-RUN TO VERIFY THE SCRIPT EXECUTION
                 if($updatedata['extra_amount'] != $data->extra){
                    echo '->'.$sequence_number."->NEW AMT = ".$updatedata['extra_amount']." | OLD AMT = ".$data->extra."<br>";
                }else{
                    continue;
                }*/

                $UpdateStatus = MeterReading::where('sequence_number', 'LIKE', "$sequence_number")->where('active_record', 1)->update($updatedata);

                MeterReading::where('sequence_number', 'LIKE', "$sequence_number")->update(['import_flag'=> 1]);
                if (!$UpdateStatus) {
                    echo "<label style='color:red'>sequence_number=>" . $sequence_number . "|extra_amount=>" . $updatedata['extra_amount'] . " FAILED</label><br>";
                } else {
                    $output_Data[] = array('sequence_number' => $sequence_number, 'extra_amount' => $updatedata['extra_amount']);
                    //echo "sequence_number=>" . $sequence_number . "|extra_amount=>" . $updatedata['extra_amount'] . " UPDATED<br>";
                }
            }
            if (!empty($output_Data)) {
                //GENERATING CSV REPORT
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
                $columns = array('Sequence number', 'Out standing amount');
                $callback = function() use ($output_Data, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($output_Data as $review) {
                        fputcsv($file, array($review['sequence_number'], $review['extra_amount']));
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            }
        } else {
            echo "DONT WORRY.ALL DATAS ARE CORRECT";
        }
    }

    public static function getcheckAfterImport() {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        //FETCHING ALL ERRANIOUS DADTA
        $faultyData = DB::select("SELECT meter_reading.sequence_number,meter_reading.total_amount,meter_reading.water_charge,meter_reading.penalty,meter_reading.other_charges,meter_reading.no_of_days_used,payment_info.total,ROUND(meter_reading.total_amount) AS m_total,ROUND(meter_reading.extra_amount) as extra FROM `meter_reading`
                                JOIN (SELECT sequence_number,SUM(total_amount) as total FROM `payment_history` WHERE is_olddata = 0 GROUP BY sequence_number) as payment_info on payment_info.sequence_number= meter_reading.sequence_number
                                where meter_reading.active_record = 1
                                AND meter_reading.current_reading >= meter_reading.previous_reading
                                AND meter_reading.water_charge <> 0.00
                                AND ROUND(meter_reading.total_amount) - payment_info.total <> ROUND(meter_reading.extra_amount)
                                AND ROUND( meter_reading.water_charge + other_charges + penalty ) <> ROUND( meter_reading.total_amount )");
        $output_Data = array();
        if (!empty($faultyData)) {
            foreach ($faultyData as $data) {
                $sequence_number = $data->sequence_number;
                $meter_prev_rec = MeterReading::where('sequence_number', 'LIKE', "$sequence_number")->where('active_record', 0)->first()->toArray();
                if($meter_prev_rec['extra_amount'] < 0){
                    $total = $meter_prev_rec['extra_amount'] + $data->water_charge+$data->other_charges+$data->penalty;
                    if($total == $data->total_amount){

                    }else{
                        $output_Data[] = array('sequence_number' => $sequence_number);
                    }
                }

            }

            //GENERATING CSV REPORT
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
                $columns = array('Sequence number');
                $callback = function() use ($output_Data, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($output_Data as $review) {
                        fputcsv($file, array($review['sequence_number']));
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
        }
    }

    public static function deleteFaultyMeterReadings() {
        $faultyData = DB::select("SELECT `sequence_number`, `id`, `water_charge`, `other_charges`, `penalty`, `total_amount`, `previous_reading`, `current_reading`, `meter_status`
          from `meter_reading`
          where `previous_reading` > current_reading
          and `bill_no` is not null
          ORDER BY `id` DESC");
      /*
      2 = MNR => CURR = 0
      3 = NA / ABNORMAL => CURR = 0
      -----------------------------------
      4 = NORMAL => AS DEFINED
      -----------------------------------
      1 = NOT LEGIBLE / NL => CURR=PREV
      5 = RNT => CURR=PREV
      6 = DL => CURR=PREV
      7 = DC => CURR=PREV
      8 = ML => CURR=PREV
      */

        if(!empty($faultyData)){
            foreach ($faultyData as $value) {
                switch ($value->meter_status) {
                  case 1:
                  case 5:
                  case 6:
                  case 7:
                  case 8:
                          $update_sql = MeterReading::where('id',$value->id)->update(['current_reading' => $value->previous_reading]);
                            echo $value->id." equaled: status ".$value->meter_status."<br>";
                  break;
                  case 2:
                          if($value->current_reading > 0){
                            $update_sql = MeterReading::where('id',$value->id)->update(['current_reading' => 0]);
                            echo $value->id." updated: status ".$value->meter_status."<br>";
                          }
                  break;
                  case 4:
                        if($value->previous_reading > $value->current_reading ){
                            $update_data = array();
                            $paymentDetail = PaymentHistory::select(DB::raw("SUM(total_amount) as paid_amount"))
                            ->where('sequence_number','like',$value->sequence_number)
                            ->where('is_olddata',0)
                            ->groupBy('sequence_number')->first();

                            MeterReading::where('id',$value->id)->delete();
                            $meterReadinDetail = MeterReading::select('id','total_amount','extra_amount','meter_status')
                              ->where('sequence_number','like',$value->sequence_number)
                              ->orderBy('id','DESC')
                              ->first()
                              ->toArray();

                            if(!empty($paymentDetail)){
                              $net_amount = $meterReadinDetail['total_amount'] - $paymentDetail->paid_amount;
                              $update_data = array('extra_amount' => $net_amount,
                                                'active_record' => 1,
                                                'payment_status' => 1,
                                                'import_flag' => 1);                             
                              
                              PaymentHistory::where('sequence_number','like',$value->sequence_number)->update(['meter_reading_id'=> $meterReadinDetail['id']]);
                              echo $value->id." deleted: status ".$value->meter_status."<br>";                              
                            }else{
                                $update_data = array('active_record' => 1);
                                echo $value->id." deleted:No payments status ".$value->meter_status."<br>";
                            }
                            MeterReading::where('id',$meterReadinDetail['id'])->update($update_data);
                            ConsumerConnection::where('sequence_number','like',$value->sequence_number)->update(['meter_status_id' => $meterReadinDetail['meter_status']]);
                        }
                  break;
                  default : echo $value->id." ignored: status ".$value->meter_status."<br>";    
                  break;
                }
            }
            echo "<br>execution completed";
        }
    }
    
    public static function correctLedgerData() {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $fileName = 'ledger_data.csv';
        
        $path = public_path() . '/uploads/ledger_data/'.$fileName;
        if(file_exists($path)){
            $connectionTypes = DB::table('master_connections_type')->select('id','connection_name')->get()->toArray();
            foreach($connectionTypes as $connections){
                $connectionType[$connections->connection_name] = $connections->id;
            }
           
            $data = \Excel::load($path)->get()->toArray();
            foreach($data as $value){
                $seq_no = $value['connection_id'];
                $consumer_name = trim($value['customer_name']);
                $consumerDetail = ConsumerConnection::select('id')
                                ->where('sequence_number', 'like', "$seq_no")
                                ->first();
                if($consumerDetail){                    
                    if(!(array_key_exists($value['connection_type'], $connectionType)) && ($value["customer_name"] == "" || $value["customer_name"] == "No Name")){
                        echo "Incomplete data for ".implode("|", $value)."<br>";
                    }else{
                        $update_array = array('name'=> $consumer_name,
                                          'connection_type_id' => $connectionType[$value['connection_type']],
                                          'no_of_flats' => $value['no_of_flats']
                                        );   
                        ConsumerConnection::where('id',$consumerDetail->id)->update($update_array);
                        $output_Data[] = array('sequence_number' => $seq_no);
                    }                    
                }else{
                    echo "No detail availavle for ".implode("|", $value)."<br>";
                }                
            }  
            unlink($path);
            //EXPORT
            $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=ledgerupdatereport.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
                $columns = array('Sequence number');
                $callback = function() use ($output_Data, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($output_Data as $review) {
                        fputcsv($file, array($review['sequence_number']));
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            
        }else{
            echo "File not fount in "."/uploads/ledger_data";
        }
        
    }
    
    public function calculateOpeningBalance(){
        $sql ="SELECT
    COALESCE(SUM(water_charge),0)+COALESCE(SUM(other_charges),0)+COALESCE(SUM(penalty),0) AS demand,COALESCE(SUM(payment_history.total_amount),0) as collection
    
FROM
    `meter_reading`
LEFT JOIN payment_history ON payment_history.meter_reading_id = meter_reading.id
WHERE
    `date_of_reading` < '2018-08-01' AND bill_no IS NOT NULL
group by meter_reading.id

union all

SELECT
   SUM(IFNULL(meter_reading.total_amount,0))-SUM(IFNULL(meter_reading.advance_amount,0))  AS demand,COALESCE(SUM(payment_history.total_amount),0) as collection
    
FROM
    `meter_reading`
LEFT JOIN payment_history ON payment_history.meter_reading_id = meter_reading.id
WHERE
    `date_of_reading` < '2018-08-01' AND bill_no IS NULL
group by meter_reading.id

union all

SELECT
    COALESCE(SUM(water_charge),0)+COALESCE(SUM(other_charges),0)+COALESCE(SUM(penalty),0) AS demand,COALESCE(SUM(payment_history.total_amount),0) as collection
    
FROM
    `old_meter_reading`
LEFT JOIN payment_history ON payment_history.meter_reading_id = old_meter_reading.id
WHERE
    `date_of_reading` < '2018-08-01' AND bill_no IS NOT NULL AND old_meter_reading.is_olddata =0
group by old_meter_reading.id

union all

SELECT
   SUM(IFNULL(old_meter_reading.total_amount,0))-SUM(IFNULL(old_meter_reading.advance_amount,0))  AS demand,COALESCE(SUM(payment_history.total_amount),0) as collection
    
FROM
    `old_meter_reading`
LEFT JOIN payment_history ON payment_history.meter_reading_id = old_meter_reading.id
WHERE
    `date_of_reading` < '2018-08-01' AND bill_no IS NULL AND old_meter_reading.is_olddata =0
group by old_meter_reading.id";
        $records = DB::select($sql);
        $demand = 0;
        $collection = 0; 
        foreach ($records as $value) {
            $demand=$demand+($value->demand);
            $collection=$collection+($value->collection);
        }
        echo $demand."-".$collection."<br/>";
        echo $demand-$collection;
    }

}
