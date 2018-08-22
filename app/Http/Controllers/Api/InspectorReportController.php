<?php

namespace App\Http\Controllers\Api;
    
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Models\InspectorReport;
use Carbon\Carbon;
use Exception;

class InspectorReportController extends ApiController
{   
	 /**
     * Function name : getBillInfo
     * Purpose       : Api function to get all updated details regarding consumer bill of that particular agent (Pull data)
     * Added Date    : February 23rd, 2018
     * Updated Date  : 
     */
    public function getReport(Request $request){ 
        
	$inspector_id = $request->inspector_id;
        $from_date_unix = $request->from_date;
        $to_date_unix = $request->to_date;
        
        $from_date= Carbon::createFromTimestamp($from_date_unix);
        $to_date= Carbon::createFromTimestamp($to_date_unix);
       
        try{
            $report_data['data']= InspectorReport::retriveAgentReport($inspector_id,$from_date,$to_date);  
            return $this->respondWithSuccess('Successful', $report_data);
        }
        catch(\Exception $e) {
            return $this->respondWithError('Error occured', []);
        }
    } 
 } 
