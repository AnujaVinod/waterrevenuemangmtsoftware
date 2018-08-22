<?php
//
namespace App\Http\Controllers\Api;
    
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Models\Billing;
use Carbon\Carbon;

class PullDataController extends ApiController
{   
	 /**
     * Function name : getBillInfo
     * Purpose       : Api function to get all updated details regarding consumer bill of that particular agent (Pull data)
     * Added Date    : February 23rd, 2018
     * Updated Date  : 
     */
    public function getBillInfo(Request $request){ 
 
        
	$agent_id = $request->agent_id;
        $last_pull_date= $request->last_updated_date;
        $corp_ward_id= $request->corp_ward_id;
        if( $last_pull_date ==0)
        {
          $date='0'; 
        }
        else
        {

          $date= Carbon::createFromTimestamp($last_pull_date);
		  	
        }      

         $bill_data['data']= Billing::retriveBilldata($agent_id,$date,$corp_ward_id);
  
        if ($bill_data) {
            return $this->respondWithSuccess('Successful', $bill_data);
        }
        else
        {
        return $this->respondWithError('FAILURE MESSAGE',[]);
        }
       
    } 
 } 
