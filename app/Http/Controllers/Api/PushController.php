<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Models\MeterReading;
use App\Models\OldMeterReading;
use App\Models\BillPayment;
use App\Models\Ward;
use App\Models\ConsumerConnection;
use Carbon\Carbon;
use Exception;

class PushController extends ApiController
{

    /**
     * To save POST data from Mobile APP
     *
     * @param  \Illuminate\Http\Request  $request 
     */
    public function store(Request $request)
    {
        
        //$path = storage_path() . "/json/test.json";
        //$value = json_decode(file_get_contents($path), true);
       // $value = json_decode($request->getContent(), true);
        $activeOneId = 0;
        $pulldate=$request->last_updated_date;
        $sequence_no = $request->sequence_number;
        $bill_no = $request->bill_no;
        $consumer_name = $request->consumer_name;
        $door_no = $request->door_no;
        $date_of_reading = $request->date_of_reading;
        $bill_no = $request->bill_no;
        $meter_no = $request->meter_no;
        $meter_status = $request->meter_status;
        $meter_rent = $request->meter_rent;
        $payment_due_date = $request->payment_due_date;
        if($request->previous_billing_date == '') {           
            $previous_billing_date = NULL;
        }else{
            $previous_billing_date = Carbon::createFromTimestamp($request->previous_billing_date);
        }
        $total_use = $request->total_use;
        $no_of_days_used = $request->no_of_days_used;
        $previous_reading = $request->previous_reading;
        $current_reading = $request->current_reading;
        $agent_id = $request->agent_id;
        $no_of_flats = $request->no_of_flats;
        $water_charge = $request->water_charge;
        $supervisor_charge = $request->supervisor_charge;
        $other_charge = $request->other_charge;
        $refund_amount = $request->refund_amount;
        $other_title_charge = $request->other_title_charge;
        $fixed_charge = $request->fixed_charge;
        $penalty = $request->penalty;
        $cess = $request->cess;
        $ugd_cess = $request->ugd_cess;
        $arrears = $request->arrears;
        $total_due = $request->total_due;
        $round_off = $request->round_off;
        $three_month_average = $request->average_amount;
       // $ward_name = $request->ward_id;
       // $wardId = Ward::select('id')->where('ward_name',$ward_name)->first();        
        $ward_id = $request->ward_id;       
        $corpward_id = $request->corp_ward_id;
        $total_amount = $request->total_amount;
        $advance_amount = $request->advance_amount;
        if($arrears < 0){
            $paymentStat = 1;
        }else{
            
            $arrears=0;
            $paymentStat = 0;
        }
        
        $checkReadingExists = MeterReading::where('sequence_number', $sequence_no)
                ->where('bill_no', $bill_no)
                ->first();
        if ($checkReadingExists) {
            $customArray = [];
            $customArray['reading'] = array($checkReadingExists);
            return $this->respondWithSuccess('Duplicate Record', $customArray);
        } else {
            $pull_date_format=Carbon::createFromTimestamp($pulldate);
             $checkpaymentdone=  BillPayment::checkPayment($pull_date_format,$sequence_no);
			 
          if($checkpaymentdone)
             {
                $extra_amount='-'.$checkpaymentdone->total_amount;
				
             }
             else
             {
                 $extra_amount=0;
             } 

            
            try{
            
            $activeOneId = MeterReading::where('sequence_number','=',$sequence_no)->where('active_record',1)->pluck('id')->first();            
            $newBillDetail = array('sequence_number' => $sequence_no,
                    'consumer_name' => $consumer_name,
                    'door_no' => $door_no,                      
                    'date_of_reading' => Carbon::createFromTimestamp($date_of_reading),
                    'bill_no' => $bill_no,
                    'meter_no' => $meter_no,
                    'meter_status' => $meter_status,
                    'meter_rent' => $meter_rent,
                    'payment_due_date' => Carbon::createFromTimestamp($payment_due_date),
                    'previous_billing_date' => $previous_billing_date,                        
                    'total_unit_used' => $total_use,
                    'no_of_days_used' => $no_of_days_used, 
                    'previous_reading' => $previous_reading,
                    'current_reading' => $current_reading,
                    'agent_id' => $agent_id,
                    'ward_id' => $ward_id,
                    'corpward_id' => $corpward_id,
                    'no_of_flats' => $no_of_flats,
                    'water_charge' => $water_charge,
                    'supervisor_charge' => $supervisor_charge,
                    'other_charges' => $other_charge,
                    'refund_amount' => $refund_amount,
                    'other_title_charge' => $other_title_charge,
                    'fixed_charge' => $fixed_charge,   
                    'penalty' => $penalty,                        
                    'cess' => $cess,  
                    'ugd_cess' =>$ugd_cess,
                    'arrears' => $arrears,
                    'total_due' => $total_due, 
                    'round_off' => $round_off, 
                    'three_month_average' => $three_month_average,
                    'total_amount' => $total_amount,   
                    'extra_amount' => $extra_amount, 
                    'active_record' => 1,
                    'meter_change_status' => 0,
                    'excess_amount_flag' =>1,
                    'payment_status' => $paymentStat,
                    'advance_amount' => $advance_amount);
            
            $response['reading'] = MeterReading::create($newBillDetail);
            
            MeterReading::where('id', '=', $activeOneId)->update(['active_record' => 0]);
           
            //FETCHING RECORDs HAVING active_record = 0
            $meterReadingObj = MeterReading::getOldRecord($sequence_no);
            
            //CHECKING FOR 3rd ROW IN THE DB
            if ($meterReadingObj->get()->count() == 2) {
               $oldData = $meterReadingObj->first()->toArray();               
               //IF DB HAS 3 ROWS MOVING OLDEST RECORD TO ANOTHER TABLE
               $inserted_data = OldMeterReading::create($oldData);               
               if (!empty($inserted_data)) {
                    MeterReading::where('id', $oldData['id'])->delete();
               }
            }     
            
            ConsumerConnection::where('sequence_number','=',$sequence_no)->update(['meter_status_id' => $meter_status]);            
            return $this->respondWithSuccess('Record Saved successfully', []);
            }
            catch(\Exception $e) {
                return $this->respondWithError('Error occured', []);
            }            
        }
    }

}
