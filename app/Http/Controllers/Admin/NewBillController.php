<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\NewBill;
use Illuminate\Support\Facades\Validator;
use Response;
use Carbon\Carbon;
use App\Models\MeterStaus;
use App\Models\ConnectionRate;
use App\Models\ConnectionMinRate;
use App\Models\ConnectionStatus;
use App\Models\BillPayment;
use App\Models\ConsumerConnection;
use Datatables;
use DateTime;
use App\Models\MeterReading;
use App\Models\OldMeterReading;
use Illuminate\Support\Facades\Storage;
use Userlogs;
use Helper;
use App\Helpers\UserLogHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class NewBillController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {         
      
      $meter_status = MeterStaus::all(); 
      $userDesignation = ConnectionStatus::getUserDesignation();  
      return view('admin.generate_new_bill',['meter_status' => $meter_status,'approval'=>$userDesignation]);
    }
    
    public function getCustomerBillInfo(Request $request)
    {
       
        
        
	$meter_no=$request->meter_number;	
        $seq_no=$request->sequence_number;
        $door_no=$request->door_no;

        
        $get_date=NewBill::getReadingDate($meter_no,$seq_no,$door_no);
        
        if(!$get_date) {
            return array("message"=>"Sequence Number does not exists","billtype"=>1);
        }
        
        $reading_date=$get_date['date_of_reading'];
        $today_date=Carbon::now();
        $date1 = new DateTime($reading_date);
        $date2 = new DateTime($today_date);
        $diff = $date2->diff($date1)->format("%a");

        if($diff < 30)
        {
            return array("message"=>"Bill Already Generated","billtype"=>1);
        }
        
           $billinfo=NewBill::getPreviousBillData($meter_no,$seq_no,$door_no);
           
           if($billinfo->count())
             {
               
             
             
                  $billinfo->no_of_days_used =$diff;
                  $billinfo->billtype='2'; //Generate New Bill
                  $originalDate=$billinfo->previous_billing_date;
                  $newDate = date("m/d/Y", strtotime($originalDate));
                  $billinfo->previous_billing_date=$newDate;
                  $reading_date=$billinfo->date_of_reading;
                  $new_reading_date = date("m/d/Y", strtotime($reading_date));
                  $billinfo->date_of_reading=$new_reading_date;
                  $old_arrear=$billinfo['arrears'];
                  $advance_amount=intVal($billinfo['advance_amount']*(-1));
                  $arrear_amt=$billinfo['arrear_amt'];
                  $excess_amt=$billinfo['excess_amt'];  
                  $arrears=(int)$advance_amount+(int)$arrear_amt+(int)$excess_amt+(int)$old_arrear;
                  $billinfo->arrears=$arrears;
                  $i = 0;
                    $tmp = mt_rand(1,9);
                    do {
                        $tmp .= mt_rand(0, 9);
                    } while(++$i < 14);
                   
                  $billinfo->bill_no=$tmp; 
               
             
                return $billinfo;
                
             }
             else
             {
                  //return $billinfodata;
             }
            
        
     
    }
    
    /*
    public function getCustomerBillInfo(Request $request)
    {
       
	$meter_no=$request->meter_number;	
        $seq_no=$request->sequence_number;
        $door_no=$request->door_no;
        
        $get_date=NewBill::getReadingDate($meter_no,$seq_no,$door_no);

        $reading_date=$get_date['date_of_reading'];
        $today_date=Carbon::now();
        $date1 = new DateTime($reading_date);
        $date2 = new DateTime($today_date);
        $diff = $date2->diff($date1)->format("%a");

        if($diff < 30)
        {
           $billinfodata=NewBill::getCustomerBilldata($meter_no,$seq_no,$door_no);

           if($billinfodata->count())
             {
             foreach ($billinfodata as $billinfo) {
                 
                  $billinfo->billtype='1'; //Update Bill 
                  $originalDate=$billinfo->previous_billing_date;
                  $newDate = date("m/d/Y", strtotime($originalDate));
                  $billinfo->previous_billing_date=$newDate;
                  $reading_date=$billinfo->date_of_reading;
                  $new_reading_date = date("m/d/Y", strtotime($reading_date));
                  $billinfo->date_of_reading=$new_reading_date;
                  
                  return $billinfo;
               }
               
             }
             else
             {
                 return $billinfodata;
             }
           
        }
        else
        {
           $billinfodata=NewBill::getPreviousBillData($meter_no,$seq_no,$door_no);
   
           if($billinfodata->count())
             {

              foreach ($billinfodata as $billinfo) {
                  $billinfo->no_of_days_used =$diff;
                  $billinfo->billtype='2'; //Generate New Bill
                  $originalDate=$billinfo->previous_billing_date;
                  $newDate = date("m/d/Y", strtotime($originalDate));
                  $billinfo->previous_billing_date=$newDate;
                  $reading_date=$billinfo->date_of_reading;
                  $new_reading_date = date("m/d/Y", strtotime($reading_date));
                  $billinfo->date_of_reading=$new_reading_date;
                  $old_arrear=$billinfo['arrears'];
                  $advance_amount='-'.$billinfo['advance_amount'];
                  $arrear_amt=$billinfo['arrear_amt'];
                  $excess_amt=$billinfo['excess_amt'];  
                  $arrears=$advance_amount+$arrear_amt+$excess_amt+$old_arrear;
                  $billinfo->arrears=$arrears;
                  $i = 0;
                    $tmp = mt_rand(1,9);
                    do {
                        $tmp .= mt_rand(0, 9);
                    } while(++$i < 14);
                   
                  $billinfo->bill_no=$tmp; 
               }
             
                return $billinfo;
                
             }
             else
             {
                  return $billinfodata;
             }
            
        }
     
    }
    */
    
    public function waterChargeCalculate(Request $request)
    {
    
        $con_type=$request->con_type;	
        $total_used=$request->total_unit_used;
        $days_used=$request->days_used;
        $seq_no=$request->sequenceNumber; 
        $meter_status=$request->meter_status;
        $mnr_count=$request->mnr_count;
        $penalty=$request->penalty;
        $rate = ConnectionRate::getRate($con_type);
        $get_otherinfo=NewBill::billdueinfo($seq_no);
        
       // print_r($get_otherinfo); exit();
        
        $no_of_flats=$get_otherinfo->no_of_flats;
        $total_unit_used=$total_used;
        $bill_type =$request->bill_type;
        if($no_of_flats >=1 && $total_unit_used > 0)
        {
            $units_per_flat=$total_used/$no_of_flats; 
            $units_per_day=$units_per_flat/$days_used;
            $units_per_month=$units_per_day*30;    
            $total_unit_used=intval($units_per_month);
        }
   
        $amount1=0;
        $amount2=0;
        $water_charge_per_flat=0;
        $total_due=0;
        $round_total_due=0;
        $mnr_penalty=0;
         if($days_used > 0){
             
               $minRate = ConnectionMinRate::getMinRate($con_type);   
               $amount1 = (($days_used * $minRate->min_price)/30);
                if($meter_status==2)//MNR Case
                {
                    $amount1=$amount1*$no_of_flats;
                       /* if($bill_type==1)
                           {

                               if($mnr_count ==1) 
                               {
                                  
                                   $mnr_penalty=0;

                               }
                               else if($mnr_count ==2)
                               {
                                   $mnr_penalty_data_per_day=$minRate->min_price/30;              
                                   $mnr_penalty=$mnr_penalty_data_per_day*$days_used;

                               }
                               else if($mnr_count ==3)
                               {   
                                   $mnr_penalty_data_per_day=$minRate->min_price/30;              
                                   $mnr_penalty_data=$mnr_penalty_data_per_day*$days_used;
                                   $mnr_penalty=$mnr_penalty_data*2;
                               }
                               else if($mnr_count >=4)
                               {
                                   $mnr_penalty_data_per_day=$minRate->min_price/30;              
                                   $mnr_penalty_data=$mnr_penalty_data_per_day*$days_used;
                                   $mnr_penalty=$mnr_penalty_data*3;
                               }
                               $penalty=$mnr_penalty;
                       } */
             
                    if($bill_type==2)
                    {
 
                        if($mnr_count ==1) 
                        {
                            $mnr_penalty_data_per_day=$minRate->min_price/30;              
                            $mnr_penalty=$mnr_penalty_data_per_day*$days_used;

                        }
                        else if($mnr_count ==2)
                        {
                           $mnr_penalty_data_per_day=$minRate->min_price/30;              
						   $mnr_penalty_data=$mnr_penalty_data_per_day*$days_used;
						   $mnr_penalty=$mnr_penalty_data*2;

                        }
                        else if($mnr_count ==3)
                        {
                            $mnr_penalty_data_per_day=$minRate->min_price/30;              
                            $mnr_penalty_data=$mnr_penalty_data_per_day*$days_used;
                            $mnr_penalty=$mnr_penalty_data*3;
                        }
                        else if($mnr_count >=4)
                        {
                             $mnr_penalty_data_per_day=$minRate->min_price/30;              
                             $mnr_penalty_data=$mnr_penalty_data_per_day*$days_used;
                             $mnr_penalty=$mnr_penalty_data*3;
                        }
                        $penalty=$mnr_penalty*$no_of_flats;
                    }
                }
                else if($meter_status==4)
                {
                        
                    $remainingConsumption = 0;
                    $consumptions = 0;
                    switch($minRate){
                    case ($minRate->from_unit == 0 && $minRate->to_unit == 0):                   
                        $remainingConsumption = 0;
                        break;            
                    case ($minRate->from_unit == 0 && $minRate->to_unit == -1):                    
                        $amount1 = (($days_used * $minRate->min_price)/30);
                        $remainingConsumption = 0;                               
                        break;               
                    default :
                          if($total_unit_used <= $minRate->to_unit)
                            {
                                    $water_charges = (($days_used * $minRate->min_price)/30);                           
                                    $water_chare_for_all_flats=$water_charges*$no_of_flats;
                                    $amount1=$water_chare_for_all_flats;
                                    $remainingConsumption=0;
                            }
                           else
                           {
                                    $remainingConsumption = $total_unit_used;                        
                                    //if no min rate get the rate from slab
                                    $rate = ConnectionRate::getRate($con_type);
                                    $water_charges = $this->WaterCharge($rate,$remainingConsumption);
                                    $water_charge_per_day=$water_charges/30;
                                    $water_charge_total_days=$water_charge_per_day*$days_used;
                                    $water_chare_for_all_flats=$water_charge_total_days*$no_of_flats;
                                    $amount1=$water_chare_for_all_flats;

                           }
					 
                          
                        break;            
                    }
                   
                    
                }
             
               else 
                {
                   
                      $amount1=$amount1*$no_of_flats;
                }
          
                $meter_rent=$get_otherinfo->meter_rent;   
                $superv_charge=$request->superv_charge;	
               // $fixed_charges=$get_otherinfo->min_price;
                $returned_amount=$request->returned_amount;	
                $cess=$request->cess;
                $ugd_cess=$request->ugd_cess;

          
                $arrears=$request->arrears; 
                $water_charges=round($amount1,2);                
              
                $calculated_other_charge = ceil(($days_used * 1)/30);
                $total_due=$water_charges+$calculated_other_charge+$penalty+$arrears;
                $round_total_due=number_format((float)$total_due, 2, '.', '');
                $total_amount=round($round_total_due);
                $round_off=$total_amount-$round_total_due;
                $round_off_amount=number_format((float)$round_off, 2, '.', '');
                $filldata['total_unit_used']=$total_unit_used;  
                $filldata['penalty']=$penalty;  
                $filldata['days_used']=$days_used; 
                $filldata['total_due']=$round_total_due;  
                $filldata['water_charge']=$water_charges; 
                $filldata['other_charges']=$calculated_other_charge; 
               // $filldata['fixed_charges']=$fixed_charges; 
                $filldata['arrears']=$arrears; 
                $filldata['total_amount']=$total_amount;
                $filldata['round_off']=$round_off_amount; 
                return $filldata;
    }
  
 }
public function WaterCharge($rates,$consumption){
        $final_amt = 0;
        foreach($rates as $rate){
            $diff_count = 0;
            $fromUnit = $rate->from_unit;
            $toUnit = $rate->to_unit;
            $price = $rate->price;         
            if($consumption > $rate->from_unit){
                if($rate->from_unit == 0 && $rate->to_unit == -1){               
                    $diff_count = round($consumption/1000,2);
                    $final_amt += $diff_count * $price;                 
                }elseif($rate->to_unit == -1){                
                    $diff_count = round(($consumption-$fromUnit)/1000,2);
                    $final_amt += $diff_count * $price;                
                }else{
                    if($consumption > $rate->to_unit){                
                        $diff_count = round(($toUnit-$fromUnit)/1000,2);
                        $final_amt += $diff_count * $price;
                        
                    }else{
                        $diff_count = round(($consumption-$fromUnit)/1000,2);
                        $final_amt += $diff_count * $price;
                        
                    }
                }
            }      
        }     
        return $final_amt;
    }
    public function generateWaterBill(Request $request) {

        DB::beginTransaction();
        try {
            $bill_type = $request->bill_type;
            $sequence_no = $request->sequence_number;
            $bill_no = $request->bill_no;
            $consumer_name = $request->consumer_name;
            $door_no = $request->door_no;
            $date_of_reading = strtotime($request->date_of_reading);
            $meter_no = $request->meter_no;
            $meter_status = $request->meter_status;
            $meter_rent = $request->meter_rent;
            $payment_due_date = strtotime($request->payment_due_date);
            $previous_bill_date = strtotime($request->previous_billing_date);
            if ($previous_bill_date == '') {
                $previous_billing_date = NULL;
            } else {
                $previous_billing_date = Carbon::createFromTimestamp($previous_bill_date);
            }
            $total_use = $request->total_unit_used;
            $no_of_days_used = $request->no_of_days_used;
            $previous_reading = $request->previous_reading;
            $current_reading = $request->current_reading;
            $agent_id = $request->agent_id;
            $get_otherinfo = NewBill::billdueinfo($sequence_no);
            
            
            $meterReadingDate=$get_otherinfo->date_of_reading;
            $today_date=Carbon::now();
            $date1 = new DateTime($meterReadingDate);
            $date2 = new DateTime($today_date);
            $diff = $date2->diff($date1)->format("%a");

            if($diff < 30)
            {
                return Response::json(array(
                        'success' => 0,
                        'message' => "<strong><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Bill Already Generated</strong><label>&nbsp;</label> "
                )); // 400 being the HTTP code for an invalid request.
                
            }
            
            
            //print_r($get_otherinfo); exit();
            $no_of_flats = $get_otherinfo->no_of_flats;
            $water_charge = $request->water_charge;
            $supervisor_charge = $request->supervisor_charge;
            $other_charge = $request->other_charges;
            $refund_amount = $request->refund_amount;
            $penalty = $request->penalty;
            $cess = $request->cess;
            $ugd_cess = $request->ugd_cess;
            $arrears = $request->arrears;
            $total_due = $request->total_due;
            $round_off = $request->round_off;
            $ward_id = $request->ward_id;
            $corpward_id = $request->corpward_id;
            $total_amount = $request->total_amount;
            $advance_amount = 0;
            $generated_by = auth()->user()->id; //Logged in user id

            $role = Helper::getRole();

            if ($role->category_name == "SUPER ADMIN") {
                $reviewer = 0;
                $approved_by = $request->generated_by; //Approved user id from dropdpwn
            }
            if ($role->category_name == "MCC" && $role->sub_category_name == "Administrator") {
                $approved_by = 0;
                $reviewer = $request->reviewer;
            }
            //dump($role); exit();

            if ($total_amount < 0) {


                $paymentStat = 1;
            } else {


                $paymentStat = 0;
            }
            if ($arrears > 0) {
                $arrears = 0;
            }


            //NEWLY GENERATED BILL DETAIL
            $new_reading = array('sequence_number' => $sequence_no,
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
                'ward_id' => $ward_id,
                'corpward_id' => $corpward_id,
                'no_of_flats' => $no_of_flats,
                'water_charge' => $water_charge,
                'supervisor_charge' => $supervisor_charge,
                'other_charges' => $other_charge,
                'refund_amount' => $refund_amount,
                'penalty' => $penalty,
                'cess' => $cess,
                'ugd_cess' => $ugd_cess,
                'arrears' => $arrears,
                'total_due' => $total_due,
                'round_off' => $round_off,
                'total_amount' => $total_amount,
                'active_record' => 1,
                'meter_change_status' => 0,
                'payment_status' => $paymentStat,
                'advance_amount' => $advance_amount,
                'generated_by' => $generated_by,
                'approved_by' => $approved_by,
                'reviewer' => $reviewer);

            /*   if ($bill_type == 1) {
              //UPDATING THE EXISTING BILL IN IF BILL GENERATED WITHIN 30 DAYS
              $update_bill_data = MeterReading::where('sequence_number', '=', $sequence_no)->where('active_record', '=', '1')->update($new_reading);
              $update_meter_data = ConsumerConnection::where('sequence_number', '=', $sequence_no)
              ->update([ 'meter_status_id' => $meter_status]);
              $bill_updated = "Existing Bill Updated";
              UserLogHelper::createUserLog($sequence_no, 'billupdated', $bill_updated);

              return Response::json(['success' => '1']);
              } */
            if ($bill_type == 2) {
                MeterReading::where('sequence_number', '=', $sequence_no)
                        ->update(['active_record' => 0]);

                $check_bill_exist = NewBill::checkbillno($bill_no);
                if ($check_bill_exist->count()) {
                    
                } else {
                    
                      $meterReadingObj = MeterReading::getOldRecord($sequence_no);
                      if ($meterReadingObj->get()->count() == 2) {
                      //FETCHING OLDEST RECORD FROM THE TABLE
                      $oldData = $meterReadingObj->first()->toArray();
                      //INSERTING THE OLDEST RECORD TO ANOTHER TABLE
                      $inserted_data = OldMeterReading::create($oldData);
                      if (!empty($inserted_data)) {
                      MeterReading::where('id', $oldData['id'])->delete();
                      $response['reading'] = MeterReading::create($new_reading);
                      } else {
                      //RETURN ERROR MESSAGE
                      return Response::json(['success' => '0']);
                      }
                      } else { 
                        $response['reading'] = MeterReading::create($new_reading);
                        }

                    $update_meter_data = ConsumerConnection::where('sequence_number', '=', $sequence_no)
                            ->update(['meter_status_id' => $meter_status]);
                    
                    $bill_gen = "New Bill Generated";
                    UserLogHelper::createUserLog($sequence_no, 'billgenerated', $bill_gen);
                    
                    DB::commit();
                    
                    return Response::json(['success' => 2]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);

            return Response::json(array(
                        'success' => 0,
                        'message' => "<strong><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Bill Generate Failed!</strong><label>&nbsp;</label> "
            )); // 400 being the HTTP code for an invalid request.
        }
        
        
    }

    public function printNewBill(Request $request)
    {
       $arrParams = $request->all();
       $arrQueryParams = array();
	   $arrQueryParams['sequence_number'] = $arrParams['sn'];
	   $arrQueryParams['meter_number'] = $arrParams['mn'];

       $billinfo = BillPayment::getDuplicateBillInfo($arrQueryParams); 
       $view = View("admin/new_bill_print", ["billinfo" => $billinfo])->render();
        $headers = array('Content-Type' => 'application/pdf');
        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a4', 'portrait');
        //$pdf->setPaper([0, 0, 595, 870], 'landscape');
        return $pdf->stream('billinfo.pdf');
    }
    
    public function getReadingInfo(Request $request)
    {
        $seq_no=$request->sequence_number;
        $bill_type=$request->bill_type;
      
        $get_reading_info=NewBill::readingInfo($seq_no);
           foreach ($get_reading_info as $readinfo) {
                 
                   if($bill_type==2)
                   {
                       $readinfo->previous_reading=$readinfo->current_reading;
                       $readinfo->current_reading='';
                   }
                   return $readinfo;
               }


        
    }
    public function approveBill() {
        return view('admin.generate_bill.manage_bill_details');
    }

    public function billListForReview(Request $request) {
        $user_id = Auth::id();
        $role = Helper::getRole();
        $data = $request->all();
        //print_r($role);
        if($role->category_name=="EXECUTIVE") {
            $data['reviewer']=$user_id;
           
        }
        if($role->category_name=="MCC" && $role->sub_category_name=="Administrator") {
            
            $data['generated_by']=$user_id;
        }
        
        $applictaionList = NewBill::getBillListForReview($data);

        return Datatables::of($applictaionList)
                        ->addColumn('status', function ($applictaionList) {

                            $approvedBy = $applictaionList->approved_by;
                            if ($approvedBy == 'NULL' || $approvedBy == NULL) {
                                return $data = 'Pending';
                            } else {
                                return $data = 'Approved';
                            }
                        })
                        ->addColumn('edit_option', function ($applictaionList) {
                            $btn = '';

                            $approvedBy = $applictaionList->approved_by;
                            if ($approvedBy == 'NULL' || $approvedBy == NULL) {
                                $btn.= '<button type="button" class="btn btn-primary edit_pop_up"  data-application="' . $applictaionList->id . '" onclick="reviewBill(' . $applictaionList->id . ',\'' . $applictaionList->sequence_number . '\')">Review</button>';
                                
                                
                            } else {
                                $btn.='<button style="" type="button" class="btn btn-primary edit_pop_up" onclick="printBill(\'' . $applictaionList->sequence_number . '\',\''.$applictaionList->meter_no.'\')" data-application="' . $applictaionList->id . '" >Print</button>';
                            }


                            return $btn;
                        })
                        ->rawColumns(['edit_option' => 'edit_option'])
                        ->make(true);
    }
    
    public function getBillDetails(Request $request) {
        $data = $request->all();
        //$data['reviewer'] = Auth::id();
        $billDetails  = MeterReading::getBillDetails($data);
       
        return view('admin.generate_bill.bill_details',['billDetails' => $billDetails]);
    }
    
    public function approveBillDetails(Request $request) {
        //print_r($request->all());
        $userId = Auth::id();
        MeterReading::where("reviewer","=",$userId)->where("id","=",$request->id)->update(['approved_by'=>$userId]);
        
        $bill_updated="Bill Approved";
        UserLogHelper::createUserLog($request->sequenceNumber,'bill_approve',$bill_updated);
        
        return Response::json(array(
                    'success' => TRUE,
                    'message' => 'Approved',
                   
                        ), 200); // 400 being the HTTP code for an invalid request.
    }

}