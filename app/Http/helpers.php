<?php
use App\User;
class Helper
{
        public static function getRole() {
        $userId = Auth::user()->id;
        $role = User::
              leftjoin('admin_details', 'users.id', '=', 'admin_details.user_id')
               ->leftjoin('master_users_sub_category', 'admin_details.sub_category_id', '=', 'master_users_sub_category.id')
                ->leftjoin ('master_users_category', 'users.cat_id', '=', 'master_users_category.id')
               ->where('users.id', $userId)
               ->first();
        return $role;
    }
	
	 public static function calculateOba($sequence_no,$met_id) {
             
             $openingBalance  = 0;
             $newTable = App\Models\MeterReading::select(DB::raw("SUM(CASE WHEN meter_reading.bill_no IS NULL THEN COALESCE(meter_reading.total_amount,0)-COALESCE(meter_reading.advance_amount,0) ELSE COALESCE((meter_reading.water_charge),0)+COALESCE((meter_reading.other_charges),0)+COALESCE((meter_reading.penalty),0) END) as demand"),DB::raw("COALESCE(SUM(payment_history.total_amount),0) paid_total"))
                     ->leftJoin("payment_history","payment_history.meter_reading_id","=","meter_reading.id")
                     ->where("meter_reading.sequence_number","LIKE","$sequence_no")
                     ->where('meter_reading.id',"<",$met_id)
                     ->where('meter_reading.is_olddata',0)
                     ->groupBy("meter_reading.sequence_number");
             $oldTable= App\Models\OldMeterReading::select(DB::raw("SUM(CASE WHEN old_meter_reading.bill_no IS NULL THEN COALESCE(old_meter_reading.total_amount,0)-COALESCE(old_meter_reading.advance_amount,0) ELSE COALESCE((old_meter_reading.water_charge),0)+COALESCE((old_meter_reading.other_charges),0)+COALESCE((old_meter_reading.penalty),0) END) as demand"),DB::raw("COALESCE(SUM(payment_history.total_amount),0) paid_total"))
                     ->leftJoin("payment_history","payment_history.meter_reading_id","=","old_meter_reading.id")
                     ->where("old_meter_reading.sequence_number","LIKE","$sequence_no")
                     ->where('old_meter_reading.id',"<",$met_id)
                     ->where('old_meter_reading.is_olddata',0)
                     ->groupBy("old_meter_reading.sequence_number");
             
             $meterReading =$newTable->unionAll($oldTable)->get();
             //
             if($meterReading->count()){
                 $demand = 0;
                 $collection = 0;
                 foreach ($meterReading as $reading){
                     $demand = $demand+round($reading->demand);
                     $collection = $collection+$reading->paid_total;
                 }
                    //echo $demand." - ".$collection;
                  $openingBalance = round($demand)- $collection;
             }
             //if no previous record
             else {
                $sql  = App\Models\MeterReading::select(DB::raw("SUM(CASE WHEN meter_reading.bill_no IS NULL THEN COALESCE(meter_reading.total_amount,0)-COALESCE(meter_reading.advance_amount,0) ELSE COALESCE((meter_reading.water_charge),0)+COALESCE((meter_reading.other_charges),0)+COALESCE((meter_reading.penalty),0) END) as demand"),DB::raw("COALESCE(SUM(payment_history.total_amount),0) paid_total"))
                     ->leftJoin("payment_history","payment_history.meter_reading_id","=","meter_reading.id")
                     ->where("meter_reading.sequence_number","LIKE","$sequence_no")
                     ->where('meter_reading.id',"=",$met_id)
                     ->where('meter_reading.is_olddata',0)
                    ->first();
                if(!$sql) {
                    $sql = App\Models\OldMeterReading::select(DB::raw("SUM(CASE WHEN old_meter_reading.bill_no IS NULL THEN COALESCE(old_meter_reading.total_amount,0)-COALESCE(old_meter_reading.advance_amount,0) ELSE COALESCE((old_meter_reading.water_charge),0)+COALESCE((old_meter_reading.other_charges),0)+COALESCE((old_meter_reading.penalty),0) END) as demand"),DB::raw("COALESCE(SUM(payment_history.total_amount),0) paid_total"))
                     ->leftJoin("payment_history","payment_history.meter_reading_id","=","old_meter_reading.id")
                     ->where("old_meter_reading.sequence_number","LIKE","$sequence_no")
                     ->where('old_meter_reading.id',"=",$met_id)
                     ->where('meter_reading.is_olddata',0)
                    ->first();
                }
                if(!$sql) {
                    return 0;
                }
                
                $openingBalance = round($sql->demand)-$sql->paid_total;
                
             }
           
             return ($openingBalance);
             
      /*$getcount= DB::table('meter_reading')->where('sequence_number',$sequence_no)->where('is_olddata',0)->groupBy('sequence_number')->count();
	
	  if($getcount > 1)
	  {
	   $getSecondRow = DB::table('meter_reading')
	           ->where('sequence_number',$sequence_no)
			   ->where('id','<',$met_id)
			   ->orderBy('id')->offset(0)->limit(1)->first();			  
		if(empty($getSecondRow)){
		
		 $getadvance= DB::table('meter_reading')->where('id',$met_id)->select('advance_amount')->first();
		 if($getadvance->advance_amount > 0)
		 {
			$calculate_amt=(-$getadvance->advance_amount);
		 }	
		else
		{ 
			$calculate_amt=0;
		}		 
		 return $calculate_amt;
		}
		else
		{
			
			$meter_read_id=$getSecondRow->id;
			$getpaidamnt= DB::table('payment_history')->where('meter_reading_id',$meter_read_id)->groupby('meter_reading_id')->sum('total_amount');
			
			if($getSecondRow->advance_amount > 0)
		   {
			 $advance_amount=$getSecondRow->advance_amount;
			 //$extra_amnt=$getSecondRow->extra_amount;
			$calculate_amt= ($getpaidamnt!= '' ?(-$advance_amount)+(-$getpaidamnt):(-$advance_amount));//+$extra_amnt;			
			 return $calculate_amt;
		   }
		   else if($getSecondRow->water_charge > 0)
		   {
				
			$calculate_amt=round($getSecondRow->water_charge+$getSecondRow->other_charges+$getSecondRow->penalty);
		   }
		   else
		   {
			 $calculate_amt=round($getSecondRow->total_amount);
		   }
			
			
			
			$diff=$calculate_amt-$getpaidamnt;
			return $diff;  
		}
	   
	}
	else
	{
		 $getadvance= DB::table('meter_reading')->where('id',$met_id)->select('advance_amount')->where('is_olddata',0)->first();
		 if($getadvance)
		 {
			 if($getadvance->advance_amount > 0)
			 {
				$calculate_amt=(-$getadvance->advance_amount);
			 }	
			else
			{ 
				$calculate_amt=0;
			}		 
			 return $calculate_amt;
		 }
		
		
	}*/
           
    }
   public static function get_agent_name($seq_number) {
           
        $agent_name= DB::table("meter_reading")->where('sequence_number',$seq_number)
                    ->select('users.name')->leftjoin('users','users.id','=','meter_reading.agent_id')
                    ->orderBy('meter_reading.date_of_reading','desc')->first(); 
        $send_agent_name=$agent_name->name;
        return $send_agent_name;
    }
    public static function check_old($seq_number)
    {
         $old_pay_status= DB::table("meter_reading")->where('meter_reading.sequence_number',$seq_number)
                       ->select('old_record.payment_status')
                      ->leftjoin(DB::raw('(select * from meter_reading where id in(select max(id) from meter_reading where active_record=0 group by meter_reading.sequence_number)) old_record '),'meter_reading.sequence_number','=','old_record.sequence_number')

                    ->orderBy('meter_reading.date_of_reading','desc')->first(); 
        $paystatus=$old_pay_status->payment_status;
        return $paystatus;
    }
    public static function check_new($seq_number)
    {
         $new_pay_status= DB::table("meter_reading")->where('meter_reading.sequence_number',$seq_number)
                       ->where('meter_reading.active_record',1)
                       ->select('meter_reading.payment_status')
                   

                    ->orderBy('meter_reading.date_of_reading','desc')->first(); 
        $newpaystatus=$new_pay_status->payment_status;
        return $newpaystatus;
    }
    
       
}