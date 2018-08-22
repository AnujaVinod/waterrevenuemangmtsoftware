<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Ward;
use App\Models\ConnectionType;
use App\Models\ConnectionRate;
use App\Models\MeterReading;
use App\Models\ConnectionMinRate;
use App\Models\LogConnectionChange;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use DB;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Input;
use App\Helpers\UserLogHelper;

class ConnectionRateController extends Controller
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
        $conn_types = ConnectionType::all();
        return view('admin.connection_rate', ['connectionTypes' => $conn_types]);
    }

    public function getConnectionRate()
    {
        $connectionRates = ConnectionRate::retrieveConnectionRates();
        return Datatables::of($connectionRates)
                        ->addColumn('action', function ($connectionRates) {
                            return '<p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" id="editBtn" data-value="' . $connectionRates->id . '" data-title="Edit" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></button></p>';
                        })
                        ->rawColumns(['action' => 'action'])
                        ->make(true);
    }

    public function saveConnectionRate(Request $request)
    {
        $messages = array(
            'to_range.greater_than_field' => 'To range value should be greater than From range value',
            'price.regex' => 'Lenght of price field is too long'
        );

        $validator = $this->validate($request, [
            'connection_type' => 'required',
            'from_range' => 'required|numeric',
            'to_range' => 'required|numeric|greater_than_field:from_range',
            'price' => 'required|regex:/^(\d{1,8})(\.\d{1,2})?$/'
                ], $messages);

        $data = ConnectionRate::retrieveConnPrice($request->connection_type);

        foreach ($data as $connPrice) {
            if (($request->from_range >= $connPrice->from_unit) && ($request->from_range <= $connPrice->to_unit)) {
                return redirect()->back()->with("error", "Entered range already exists!");
            }
            if (($request->to_range >= $connPrice->from_unit) && ($request->to_range <= $connPrice->to_unit)) {
                return redirect()->back()->with("error", "Entered range already exists!");
            }
        }

        $rates = ConnectionRate::create([
                    'connection_type_id' => $request->connection_type,
                    'from_unit' => trim($request->from_range),
                    'to_unit' => trim($request->to_range),
                    'price' => trim($request->price),
        ]);
        $action="New connection rate added";            
        UserLogHelper::createUserLog(0, 'connection rates', $action);
        return redirect()->back()->with("success", "Connection Rates added successfully !");
    }

    public function getConnectionRateInfo(Request $request)
    {
        $connRate = ConnectionRate::getConnectionRateInfo($request->conId);
        if ($connRate) {
            return Response::json(['success' => 'success', 'conndata' => $connRate]);
        } else {
            return Response::json(['errors' => 'Error Occured']);
        }
    }

    public function updateConnectionRate(Request $request)
    {
        $messages = array(
            'from_range.integer' => 'Invalid From Range value',
            'from_range.min' => 'Invalid From Range value',
            'to_range.numeric' => "Invalid To Range value",
            'to_range.greater_than_field' => 'To range value should be greater than From range value',
            'price.regex' => 'Lenght of price field is too long'
        );

        $validator = Validator::make($request->all(), [
                    'connection_type' => 'required',
                    'from_range' => 'required|integer|min:0',
                    'to_range' => 'required|numeric|greater_than_field:from_range',
                    'price' => 'required|regex:/^(\d{1,8})(\.\d{1,2})?$/'
                        ], $messages);
        if ($validator->passes()) {
            $connection_rate_obj = ConnectionRate::getConnectionRateInfo($request->connRateId);
            ConnectionRate::where('id', '=', $request->connRateId)
                    ->update(['connection_type_id' => trim($request->connection_type), 'from_unit' => trim($request->from_range), 'to_unit' => trim($request->to_range), 'price' => trim($request->price), 'updated_by' => auth()->user()->id]);
            $sequenceNo = '';
            $action="Connection rate ".$connection_rate_obj->connection_name."(".$connection_rate_obj->from_unit." to ".$connection_rate_obj->to_unit.") is updated";            
            UserLogHelper::createUserLog($sequenceNo, 'connection rates', $action);
            return Response::json(['success' => '1']);
        }        
        return Response::json(['errors' => $validator->errors()]);
    }

    public function getConnectionMinRate(Request $request)
    {
        /*
        $arreas_amt = 0;
        $excess_amt = 0;
        $difference = 0;
        $from_date = '';
        $tot_consumption = 0;
        $newConnTypeId = $request->newConnTypeId;
        $extConnTypeId = $request->extConnTypeId;
        $deposite_amount = $request->deposite_amount;
        $ischecked = $request->ischecked;
        $seq_no = $request->seq_no;
        $oldcon_type_period_in_days = 0;
        $newcon_type_period_in_days = 0;
        $paid_amount = 0;
        $validator = Validator::make($request->all(), [
        ]);
        // user selected required date to timestamp
        $req_from_date = Carbon::parse($request->req_from_date)->format('d/m/Y');
        $req_from_date1 = Carbon::createFromFormat('d/m/Y H:i:s', $req_from_date . ' 00:00:00');

        //check if type is already changed. If so get the last date of change otherwise connection date as from date
        $lastDateofChange = '';
        $changeDate = LogConnectionChange::getConnectionChangeDate($seq_no);
        if ($changeDate) {
            $lastDateofChange = $changeDate->required_from_date;
            $from_date = $changeDate->required_from_date;
        } else {
            $lastDateofChange = '';
            $from_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->conn_date . ' 00:00:00');
        }

        $compareDate = $req_from_date1->min($from_date);
        if ($compareDate == $req_from_date1) {
            $validator->errors()->add('req_from_date', 'Selected date is either before the connection date or previously connection changed date');
            return Response::json(['error' => $validator->errors()]);
        }
        $tot_consumption = $request->tot_consumption;
        $last_date_of_reading = ConnectionRate::getLastBillIssedate($seq_no);
        if ($last_date_of_reading) {


            $last_bill_issued_date = $last_date_of_reading->date_of_reading;
            $compareDate2 = $req_from_date1->min($last_bill_issued_date);
            // if change date is greater than the last bill issued date
            if ($compareDate2 == $req_from_date1) {
                $oldcon_type_period_in_days = $this->dateDiff($from_date, $req_from_date1);
                $newcon_type_period_in_days = $this->dateDiff($req_from_date1, $last_bill_issued_date);
                $total_period_in_days = $oldcon_type_period_in_days + $newcon_type_period_in_days;

                $oldcon_type_period_consumption = round((($oldcon_type_period_in_days * $tot_consumption) / $total_period_in_days));
                $newcon_type_period_consumption = round((($newcon_type_period_in_days * $tot_consumption) / $total_period_in_days));
                $get_otherinfo = $billinfo = NewBill::billdueinfo($seq_no);
                $no_of_flats = $get_otherinfo->no_of_flats;
                if ($no_of_flats >= 1) {
                    $units_per_flat_const = $oldcon_type_period_consumption / $no_of_flats;
                    $units_per_day_const = $units_per_flat_const / $oldcon_type_period_in_days;
                    $units_per_month_const = $units_per_day_const * 30;
                    $oldcon_type_period_consumption = intval($units_per_month_const);

                    $units_per_flat_dom = $newcon_type_period_consumption / $no_of_flats;
                    $units_per_day_dom = $units_per_flat_dom / $newcon_type_period_in_days;
                    $units_per_month_dom = $units_per_day_dom * 30;
                    $newcon_type_period_consumption = intval($units_per_month_dom);
                }

                $amount1 = 0;
                $watercharge = 0;
                if ($oldcon_type_period_in_days > 0) {
                    $minRate = ConnectionMinRate::getMinRate($extConnTypeId);
                    $remainingConsumption = 0;
                    $consumptions = 0;
                    switch ($minRate) {
                        case ($minRate->from_unit == 0 && $minRate->to_unit == 0):
                            $remainingConsumption = 0;
                            break;
                        case ($minRate->from_unit == 0 && $minRate->to_unit == -1):
                            $amount1 = (($oldcon_type_period_in_days * $minRate->min_price) / 30);
                            $remainingConsumption = 0;
                            break;
                        default :
                            if($oldcon_type_period_consumption <= $minRate->to_unit){
                                
                                $water_charges = (($oldcon_type_period_in_days * $minRate->min_price)/30);                           
                                $water_chare_for_all_flats=$water_charges*$no_of_flats;
                                $amount1=$water_chare_for_all_flats;
                                $remainingConsumption=0;
                                
                                
                            } else {
                                $remainingConsumption = $oldcon_type_period_consumption;   
                                
                                $rate = ConnectionRate::getRate($extConnTypeId);
                                $watercharge = $this->calculateWaterCharge($rate, $remainingConsumption); 
                                $water_charge_per_day_const = $watercharge / 30;
                                $water_charge_total_days_const = $water_charge_per_day_const * $oldcon_type_period_in_days;
                                $water_chare_for_all_flats_const = $water_charge_total_days_const * $no_of_flats;
                                $amount1 = $water_chare_for_all_flats_const;
                               
                            }
                            break;
                    }
                }

                $amount2 = 0;
                if ($newcon_type_period_in_days > 0) {
                    $minRate = ConnectionMinRate::getMinRate($newConnTypeId);

                    $remainingConsumption = 0;
                    $consumptions = 0;
                    switch ($minRate) {
                        case ($minRate->from_unit == 0 && $minRate->to_unit == 0):
                            $remainingConsumption = 0;
                            break;

                        case ($minRate->from_unit == 0 && $minRate->to_unit == -1):
                            $amount2 = (($newcon_type_period_in_days * $minRate->min_price) / 30);
                            $remainingConsumption = 0;
                            break;
                        default :
                            if($newcon_type_period_consumption <= $minRate->to_unit){
                                
                                $water_charges = (($newcon_type_period_in_days * $minRate->min_price)/30);                           
                                $water_chare_for_all_flats=$water_charges*$no_of_flats;
                                $amount2=$water_chare_for_all_flats;
                                $remainingConsumption=0;
                                
                                
                            } else {
                                $remainingConsumption = $newcon_type_period_consumption;   
                                
                                $rate = ConnectionRate::getRate($newConnTypeId);
                                $watercharge = $this->calculateWaterCharge($rate, $remainingConsumption); 
                                $water_charge_per_day_dom = $watercharge / 30;
                                $water_charge_total_days_dom = $water_charge_per_day_dom * $newcon_type_period_in_days;
                                $water_chare_for_all_flats_dom = $water_charge_total_days_dom * $no_of_flats;
                                $amount2 = $water_chare_for_all_flats_dom;
                               
                            }                            
                            break;
                    }                   
                }

                $final_amount = number_format($amount1 + $amount2, 2, '.', ''); //13000   
                $paid_amount1 = ConnectionRate::getWaterCharge($seq_no, $lastDateofChange); //1000
                $paid_amount = $paid_amount1->total_water_charge;

                $difference = $final_amount - $paid_amount;
                if ($difference < 0) {
                    if ($deposite_amount != 0 && $ischecked == 1) {
                        $difference = (-($deposite_amount) + $difference);
                    }
                    $excess_amt = $difference;
                } else {
                    if ($deposite_amount != 0 && $ischecked == 1) {
                        $difference = ($deposite_amount + $difference);
                    }
                    $arreas_amt = $difference;
                }
            } else {
                $excess_amt = 0;
                $arreas_amt = 0;
            }
        } else {
            $excess_amt = 0;
            $arreas_amt = 0;
        } */
        $minRate = DB::table('master_connection_minrate')
                ->select(['min_price'])
                ->where('master_connection_minrate.connection_type_id', $request->newConnTypeId)
                ->get();
        if ($minRate->isNotEmpty()) {
            return Response::json(['success' => '1', 'rate' => $minRate ]);
        } else {
            return Response::json(['success' => '0']);
        }
    }

    public function calculateWaterCharge($rates, $consumption)
    {
        $final_amt = 0;
        foreach ($rates as $rate) {
            $diff_count = 0;
            $fromUnit = $rate->from_unit;
            $toUnit = $rate->to_unit;
            $price = $rate->price;
            if ($consumption > $rate->from_unit) {
                if ($rate->from_unit == 0 && $rate->to_unit == -1) {
                    $diff_count = $consumption / 1000;
                    $final_amt += $diff_count * $price;
                } elseif ($rate->to_unit == -1) {
                    $diff_count = ceil(($consumption - $fromUnit) / 1000);
                    $final_amt += $diff_count * $price;
                } else {
                    if ($consumption > $rate->to_unit) {
                        $diff_count = ceil(($toUnit - $fromUnit) / 1000);
                        $final_amt += $diff_count * $price;
                    } else {
                        $diff_count = ceil(($consumption - $fromUnit) / 1000);
                        $final_amt += $diff_count * $price;
                    }
                }
            }
        }
        return $final_amt;
    }

    public function dateDiff($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400);
    }

}
