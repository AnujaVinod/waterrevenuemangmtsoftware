<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MeterReading;
use App\Models\BankInfo;
use App\Models\AdminDetail;
use DB;
class BillPayment extends Model
{
 
     protected $fillable = [
       'sequence_number', 'meter_reading_id', 'payment_date', 'total_amount', 'payment_mode', 'payment_status'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_history';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
   /* public static function getPaymentInfo()
    {
        
       $data=  MeterReading::
                leftjoin('consumer_connection','consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')  
                ->leftjoin('payment_history','payment_history.sequence_number', '=', 'meter_reading.sequence_number')  
                ->select(                   
              'meter_reading.id','payment_history.payment_date','consumer_connection.sequence_number','consumer_connection.name',
              'consumer_connection.meter_no','meter_reading.payment_due_date',DB::raw('(CASE WHEN meter_reading.payment_status = 1 THEN 0.00 ELSE meter_reading.total_amount END) AS total_amount'),'meter_reading.payment_status'
                )
               ->where('meter_reading.active_record','=','1')
               ->groupBy('meter_reading.id')
               
               ->get();
        return $data;

    } */
   public static function getPaymentInfo($start,$limit,$order,$dir,$search)
    {
         $data   = MeterReading::
                 leftjoin('consumer_connection','consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')  
                ->leftjoin('payment_history','payment_history.meter_reading_id', '=', 'meter_reading.id')         
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)->where('meter_reading.active_record','=','1');
                if(!empty($search))
                {
                     $data->where(function($data)use ($search){
                
                     $data->orwhere('consumer_connection.name','LIKE',"%{$search}%")
                    ->orWhere('consumer_connection.sequence_number', 'LIKE',"%{$search}%")
                    ->orWhere('consumer_connection.meter_no', 'LIKE',"%{$search}%");
                  
                    });
                }
                
              $data_result =  $data->select(
                   'meter_reading.id','payment_history.payment_date','consumer_connection.sequence_number','consumer_connection.name',
              'consumer_connection.meter_no','meter_reading.date_of_reading','meter_reading.payment_due_date',DB::raw('(CASE WHEN meter_reading.payment_status = 1 THEN  IFNULL(meter_reading.extra_amount,0)+(-(IFNULL(meter_reading.advance_amount,0))) ELSE IFNULL(meter_reading.extra_amount,0)+(IFNULL(meter_reading.total_amount,0))
			    END) AS total_amount'),'meter_reading.payment_status'
                    
                )
               ->get();
        
     
        return $data_result;

    }
    public static function getPaymentCount()
    {
        $data   = MeterReading::
                 leftjoin('consumer_connection','consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')  
                ->leftjoin('payment_history','payment_history.meter_reading_id', '=', 'meter_reading.id')         
                ->where('meter_reading.active_record','=','1')
               ->count();
        
     
        return $data;
    }

    public static function getPaymentInfoCount($search){
        $query   = MeterReading::
                  leftjoin('consumer_connection','consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')  
              ->leftjoin('payment_history','payment_history.meter_reading_id', '=', 'meter_reading.id')                   
                    ->where('meter_reading.active_record','=','1');
                   
                    $query->where(function($query)use ($search){
                
                    $query->orwhere('consumer_connection.name','LIKE',"%{$search}%")
                    ->orWhere('consumer_connection.sequence_number', 'LIKE',"%{$search}%")
                    ->orWhere('consumer_connection.meter_no', 'LIKE',"%{$search}%");
                  
                    });

              $data =  $query->count();
               
        return $data;       
    }
    public static function getSearchResult($seq_number,$name,$meter_no)
    {
        $data=    MeterReading::
                leftjoin('consumer_connection','consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')  
                  ->leftjoin('payment_history','payment_history.meter_reading_id', '=', 'meter_reading.id');
                $data->where('meter_reading.active_record','=','1');
                if($seq_number != ''){
                    $data->where('consumer_connection.sequence_number', $seq_number);
                }
                if($meter_no != ''){
                    $data ->where('consumer_connection.meter_no', $meter_no);
                }
               $data ->select('meter_reading.id', 'payment_history.payment_date',                 
              'consumer_connection.sequence_number','consumer_connection.name','consumer_connection.meter_no','meter_reading.payment_due_date','meter_reading.date_of_reading',DB::raw('(CASE WHEN meter_reading.payment_status = 1 THEN  IFNULL(meter_reading.extra_amount,0)+(-(IFNULL(meter_reading.advance_amount,0))) ELSE IFNULL(meter_reading.extra_amount,0)+(IFNULL(meter_reading.total_amount,0))
			    END) AS total_amount'),'meter_reading.payment_status')
              
               ->groupBy('meter_reading.id')
               ->get();
        return $data;

    }

   public static function getviewPaymentInfo($meter_reading_id)
   {
       
        $data=  MeterReading::
                 leftjoin('payment_history', 'payment_history.meter_reading_id', '=', 'meter_reading.id') 
                 ->leftjoin('consumer_connection', 'consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')  
                ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                 ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                ->leftjoin('bank_info','bank_info.payment_id','=','payment_history.id')
                ->leftjoin('master_meter_status','master_meter_status.id','=','consumer_connection.meter_status_id')
                ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                ->where('meter_reading.id', $meter_reading_id)  
                ->select(                   
                        'payment_history.id','payment_history.payment_date','payment_history.payment_status',
                        'consumer_connection.sequence_number','consumer_connection.name','consumer_connection.door_no','consumer_connection.meter_no',
                        'master_ward.ward_name',
                        'master_corp.corp_name',
                        'master_meter_status.meter_status',
                        'master_connections_type.connection_name',
                        'bank_info.bank_name','bank_info.branch_name','bank_info.cheque_dd','bank_info.challan_no','bank_info.remarks',
                        'meter_reading.date_of_reading','meter_reading.bill_no',
                        'meter_reading.meter_rent','meter_reading.payment_due_date',DB::raw('(CASE WHEN meter_reading.previous_billing_date IS NULL
            THEN consumer_connection.connection_date
            ELSE meter_reading.previous_billing_date
            END
        ) AS previous_billing_date'),
                        'meter_reading.total_unit_used','meter_reading.no_of_days_used',
                        'meter_reading.previous_reading','meter_reading.current_reading','meter_reading.no_of_flats',
                        'meter_reading.water_charge','meter_reading.supervisor_charge','meter_reading.other_charges',
                        'meter_reading.refund_amount','meter_reading.other_title_charge','meter_reading.fixed_charge',
                        'meter_reading.penalty','meter_reading.cess',
                        'meter_reading.ugd_cess','meter_reading.arrears','meter_reading.total_due',
                        'meter_reading.round_off','meter_reading.total_amount','meter_reading.advance_amount'
                )
               ->get();
        return $data;
   } 
   public static function getPayBillInfo($meter_reading_id)
   {
       
       $data=MeterReading::
                 leftjoin('consumer_connection', 'consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')  
               ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
               ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                ->leftjoin('master_meter_status','master_meter_status.id','=','consumer_connection.meter_status_id')
               ->where('meter_reading.id', $meter_reading_id)  
               ->select('meter_reading.id','consumer_connection.sequence_number','consumer_connection.name','master_ward.ward_name','master_corp.corp_name',
                        'meter_reading.bill_no','meter_reading.door_no','meter_reading.meter_no',
                       'meter_reading.payment_due_date',DB::raw('(CASE WHEN meter_reading.previous_billing_date IS NULL
            THEN consumer_connection.connection_date
            ELSE meter_reading.previous_billing_date
            END
        ) AS previous_billing_date'),'meter_reading.total_unit_used',DB::raw('round(meter_reading.total_due-(IFNULL(meter_reading.water_charge,0)+IFNULL(meter_reading.other_charges,0)+IFNULL(meter_reading.penalty,0)
		+(IFNULL(-meter_reading.advance_amount,0)))) as arrears'),
                       'meter_reading.no_of_days_used','meter_reading.previous_reading','meter_reading.current_reading',
                       'meter_reading.water_charge','meter_reading.supervisor_charge','meter_reading.other_charges','meter_reading.refund_amount',
                       'meter_reading.fixed_charge','meter_reading.penalty',
                       'meter_reading.cess','meter_reading.ugd_cess','meter_reading.total_due','meter_reading.round_off','meter_reading.total_amount','master_meter_status.meter_status','meter_reading.date_of_reading','master_connections_type.connection_name','meter_reading.total_amount','meter_reading.advance_amount')
               ->get();
       return $data;
   }
   public static function getPaymentPdfInfo($seq_num,$meter_reading_id)
           
   {
       $data=MeterReading::
                 leftjoin('payment_history', 'meter_reading.id', '=', 'payment_history.meter_reading_id')  
               ->leftjoin('bank_info','bank_info.payment_id','=','payment_history.id')
               ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
               ->leftjoin('consumer_address','consumer_address.sequence_number','=','consumer_connection.sequence_number')
               ->leftjoin('master_meter_status','master_meter_status.id','=','consumer_connection.meter_status_id')
                 ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
               ->where('meter_reading.id', $meter_reading_id)           
               ->select('master_ward.ward_name','consumer_address.premises_address','consumer_connection.name','consumer_connection.sequence_number',
                       'meter_reading.meter_no','meter_reading.door_no','meter_reading.bill_no','meter_reading.date_of_reading','payment_history.payment_date','meter_reading.other_charges',
                       DB::raw('(CASE WHEN meter_reading.previous_billing_date IS NULL
            THEN consumer_connection.connection_date
            ELSE meter_reading.previous_billing_date
            END
        ) AS previous_billing_date'),'meter_reading.water_charge','meter_reading.supervisor_charge','meter_reading.other_charges','meter_reading.refund_amount',
                       'meter_reading.fixed_charge','meter_reading.penalty',
                       'meter_reading.cess','meter_reading.ugd_cess','meter_reading.arrears','meter_reading.total_due','meter_reading.round_off','meter_reading.total_amount',
                       'bank_info.challan_no','bank_info.bank_name','bank_info.branch_name','meter_reading.advance_amount')
               ->get();
          return $data;
     
   }
   public static function getBankInfo($meter_reading_id)
   {
       $data=BankInfo::
                 leftjoin('payment_history', 'payment_history.id', '=', 'bank_info.payment_id')  
                 ->where('payment_history.meter_reading_id', $meter_reading_id)           
                 ->select('bank_info.bank_name','bank_info.branch_name','payment_history.total_amount','bank_info.payment_id','payment_history.payment_date')
               ->get();
       
          return $data;
          
   }
   public static function checkPayment($pulldate,$sequence_no)
   {
       $checkpayment=BillPayment::select('total_amount')
               ->where('payment_date', '>', $pulldate)
               ->where('sequence_number', '=' , $sequence_no)->first();
      
       return $checkpayment;
   }
   
   /**
     * To generate duplicate bill
     * @Params array
     * @return 
	 * created : Santhosh Kumar / 16-04-2018
     */
   public static function getDuplicateBillInfo($arrParams = array()) {
        $sql = "SELECT m.sequence_number, cc.name as consumer_name,DATE_FORMAT(m.date_of_reading, '%d-%m-%Y %H:%i:%s') as date_of_reading, cc.door_no, m.bill_no, cc.meter_no,m.meter_rent,
				DATE_FORMAT(m.payment_due_date, '%d-%m-%Y %H:%i:%s') as payment_due_date, DATE_FORMAT(m.previous_billing_date, '%d-%m-%Y %H:%i:%s') as previous_billing_date, m.total_unit_used, m.no_of_days_used, m.previous_reading, m.current_reading,
				m.ward_id, m.no_of_flats, m.corpward_id, m.water_charge, m.supervisor_charge, m.other_charges, m.refund_amount,
				m.other_title_charge, m.fixed_charge, m.penalty, m.cess, m.ugd_cess, round(m.total_due-(IFNULL(m.water_charge,0)+IFNULL(m.other_charges,0)+IFNULL(m.penalty,0)+(IFNULL(-m.advance_amount,0)))) as arrears, m.total_due, m.round_off, m.three_month_average,
				m.total_amount, m.active_record, m.extra_amount, m.meter_change_status, m.payment_status, m.created_at, m.updated_at, w.ward_name, c.corp_name,ms.meter_status, m.reviewer,m.approved_by,
				ct.connection_name FROM meter_reading m";
        
                $sql .= " LEFT JOIN consumer_connection cc ON cc.sequence_number=m.sequence_number";
		$sql .= " LEFT JOIN master_ward w ON cc.ward_id=w.id";
                $sql .= " LEFT JOIN master_meter_status ms ON m.meter_status=ms.id";
		$sql .= " LEFT JOIN master_corp c ON cc.corp_ward_id=c.id 
					INNER JOIN master_connections_type ct ON cc.connection_type_id =ct.id WHERE 1=1";		
        if (array_key_exists('meter_number', $arrParams) && $arrParams['meter_number'] != '') {
            $sql .= " AND m.meter_no = '" . $arrParams['meter_number']."'";
        }

        if (array_key_exists('sequence_number', $arrParams) && $arrParams['sequence_number'] != '') {
            $sql .= " AND m.sequence_number = '" . $arrParams['sequence_number']."'";
        }
		
		$sql .= " AND m.active_record = 1";

        //$sql .= " ORDER BY ";

        if (array_key_exists('limit', $arrParams) && $arrParams['limit'] != '') {
            $sql .= " LIMIT '" . $arrParams['limit'] . "'";
        }
        $billinfo = DB::select(DB::raw($sql));
        return $billinfo;
    }
    
    public static function getBankUserDetails($user_id)
    {
        
        $data=AdminDetail::select('bank_name','bank_branch')->where('user_id','=',$user_id)->first();
        return $data;
    }
}
