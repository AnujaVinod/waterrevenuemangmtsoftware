<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\ConsumerConnection;
use App\Models\Ward;
use App\Models\LogConnectReconnect;
use App\Models\ConnectionType;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use Carbon\Carbon;
use App\Models\MeterStaus;
use App\Models\Plumber;
use App\Models\ConsumerApplication;
use App\Models\ConsumerApplicationSeven;
use App\Models\ConsumerAddress;
use Illuminate\Support\Facades\Storage;
use App\Helpers\UserLogHelper;
class NewApplicationController extends Controller
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

        $wards = Ward::all();
        $connType = ConnectionType::all();
        $plumberNames = Plumber::all();

        return view('admin.add_new_application',['wards' => $wards,'connTypes' => $connType,'plumberNames'=>$plumberNames]);
    }
    public function saveData(Request $request)
    {
       $application_type_id=$request->application_type_id;
       $application_date=Carbon::now();
       $category = "New Application"; 
       $sequence_no = '';
      
            if($application_type_id==1)
            {
                     $validator = Validator::make($request->all(), [
                       'application_number' => 'required|unique:consumer_application,application_number',
                       'phone_number' => 'required|numeric|digits_between:10,15|unique:consumer_application,phone_number',
                       'customer_name' => 'required',
                       'door_no'=>'required',
                       'ward_id' => 'required',
                       'corp_ward_id'=>'required',
                       'connection_type_id' =>'required',
                       'premises_address'=>'required',
                       'premises_owner_name'=>'required',
					   'documents'=>'required'
					   
                     ]);
					 
                        if ($validator->passes()) {
                            $files = $request->file('document_name');
                            $filename = [];
                            $i=0;
                            if($request->documents)
                            {			
                            foreach ($request->documents as $photo) { 
                                $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                $fileName = $file.'.'.$extension;
                                $filename[$i] = $fileName; 
                                $i++;
                                Storage::putFileAs('public/images', $photo, $fileName);
                                            
                                    
                               }
                            $filenameString = implode (", ", $filename);     
                            }
                            else {
                                  $filenameString='';
                            }
                        
                                   $tap_application_entry = ConsumerApplication::create([
                                             'application_number'=>$request->application_number,
                                             'application_type_id' =>  $application_type_id,
                                             'customer_name' => $request->customer_name , 
                                            'ward_id' =>$request->ward_id , 
                                            'corp_ward_id' =>$request->corp_ward_id , 
                                            'door_no' =>$request->door_no , 
                                            'khata_no' =>$request->khata_no , 
                                            'phone_number' =>$request->phone_number , 
                                            'service_id' =>$request->service_id , 
                                            'connection_type_id' =>$request->connection_type_id , 
                                            'plumber_id' =>$request->plumber_id , 
                                            'document_name' =>$filenameString , 
                                            'recommended_by' =>$request->recommended_by , 
                                            'remarks' =>$request->remarks , 
                                            'application_date' =>$application_date,
                                            'application_status_id'=>2,
                                            'inserted_by' =>  auth()->user()->id
                                 ]);
                                
                                $consumer_address = ConsumerAddress::create([
                                             'application_id' => $tap_application_entry->id,
                                             'premises_owner_name' => $request->premises_owner_name,
                                             'premises_address' => $request->premises_address,
                                             'premises_state' => $request->premises_state,
                                             'premises_street' => $request->premises_street,
                                             'premises_city'=>$request->premises_city,
                                             'premises_zip'=>$request->premises_zip,
                                             'inserted_by' =>  auth()->user()->id
                                            
                                 ]);
                             $action="Created new tap application ".$request->application_number;       
                             UserLogHelper::createUserLog($sequence_no, $category, $action); 
                            return Response::json(['success' => '1']);
                        }
                      return Response::json(['errors' => $validator->errors()]);
                        
            }
            else if($application_type_id==2)
            {
             
                   $validator = Validator::make($request->all(), [
                       'application_number' => 'required|unique:consumer_application,application_number',
                       'phone_number' => 'required|nullable|numeric|digits_between:10,15',
                       'customer_name' => 'required',
                       'door_no'=>'required',
                       'ward_id' => 'required',
                       'corp_ward_id'=>'required',
                       'connection_type_id' =>'required',
                       'premises_address'=>'required',
                       'premises_owner_name'=>'required',
					   'documents'=>'required'
                     ]);
                        if ($validator->passes()) {
                            
                            $files = $request->file('document_name');
                            $filename = [];
                            $i=0;
                            if($request->documents)
                            {
                            foreach ($request->documents as $photo) { 
                                $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                $fileName = $file.'.'.$extension;
                                $filename[$i] = $fileName; 
                                $i++;
                                Storage::putFileAs('public/images', $photo, $fileName);
                            }
                            $filenameString = implode (", ", $filename);     
                            }
                            else {
                                  $filenameString='';
                            }
                            
                                   $ugd_application_entry = ConsumerApplication::create([
                                             'application_number'=>$request->application_number,
                                             'application_type_id' =>  $application_type_id,
                                             'customer_name' =>$request->customer_name, 
                                            'ward_id' =>$request->ward_id , 
                                            'corp_ward_id' =>$request->corp_ward_id , 
                                            'door_no' =>$request->door_no , 
                                            'khata_no' =>$request->khata_no, 
                                            'phone_number' =>$request->phone_number, 
                                            'service_id' =>$request->service_id, 
                                            'connection_type_id' =>$request->connection_type_id, 
                                            'plumber_id' =>$request->plumber_id, 
                                            'document_name' =>$filenameString , 
                                            'no_of_house' =>$request->no_of_house, 
                                            'remarks' =>$request->remarks, 
                                            'application_date' =>$application_date,
                                            'application_status_id'=>2,
                                            'inserted_by' =>  auth()->user()->id
                                 ]);

                                $consumer_address = ConsumerAddress::create([
                                             'application_id' => $ugd_application_entry->id,
                                             'premises_owner_name' => $request->premises_owner_name,
                                             'premises_address' => $request->premises_address,
                                             'premises_state' => $request->premises_state,
                                             'premises_street' => $request->premises_street,
                                             'premises_city'=>$request->premises_city,
                                             'premises_zip'=>$request->premises_zip,
                                             'inserted_by' =>  auth()->user()->id
                                            
                                 ]);
                            $action="Created new UGD application ".$request->application_number;       
                             UserLogHelper::createUserLog($sequence_no, $category, $action);
                            return Response::json(['success' => '1']);
                        }
                      return Response::json(['errors' => $validator->errors()]);

            }
            else if($application_type_id==3)
            {
                 $validator = Validator::make($request->all(), [
                       'application_number' => 'required|unique:consumer_application,application_number',
                       'phone_number' => 'required|nullable|numeric|digits_between:10,15',
                       'customer_name' => 'required',
                       'door_no'=>'required',
                       'ward_id' => 'required',
                       'corp_ward_id'=>'required',
                       'premises_address'=>'required'
                     ]);
                        if ($validator->passes()) {
                            
                            $files = $request->file('document_name');
                            $filename = [];
                            $i=0;
                            if($request->bpl_doc)
                            {
                                foreach ($request->bpl_doc as $photo) { 
                                    $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                    $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                    $fileName = $file.'.'.$extension;
                                    $filename[$i] = $fileName; 
                                    $i++;
                                     Storage::putFileAs('public/images', $photo, $fileName);
                                }
                               
                            }
                            if($request->tax_doc)
                            {
                                foreach ($request->tax_doc as $photo) { 
                                    $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                    $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                    $fileName = $file.'.'.$extension;
                                    $filename[$i] = $fileName; 
                                    $i++;
                                    Storage::putFileAs('public/images', $photo, $fileName);
                                }
                                
                            }
                             if($request->affidavit_doc)
                            {
                                foreach ($request->affidavit_doc as $photo) { 
                                    $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                    $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                    $fileName = $file.'.'.$extension;
                                    $filename[$i] = $fileName; 
                                    $i++;
                                     Storage::putFileAs('public/images', $photo, $fileName);
                                }
                                  
                            }
                        
                           $filenameString = implode (", ", $filename);  

                              $jala_application_entry = ConsumerApplication::create([
                                             'application_number'=>$request->application_number,
                                             'application_type_id' =>  $application_type_id,
                                             'customer_name' =>$request->customer_name , 
                                             'phone_number' =>$request->phone_number, 
                                            'ward_id' =>$request->ward_id , 
                                            'corp_ward_id' =>$request->ward_id , 
                                            'door_no' =>$request->door_no , 
                                            'connection_type_id' =>2 , 
                                            'document_name' =>$filenameString , 
                                            'application_date' =>$application_date,
                                            'application_status_id'=>2,
                                            'inserted_by' =>  auth()->user()->id
                                 ]);

                                $consumer_address = ConsumerAddress::create([
                                             'application_id' => $jala_application_entry->id,
                                             'premises_address' => $request->premises_address,
                                             'premises_state' => $request->premises_state,
                                             'premises_street' => $request->premises_street,
                                             'premises_city'=>$request->premises_city,
                                             'premises_zip'=>$request->premises_zip,
                                             'inserted_by' =>  auth()->user()->id
                                            
                                 ]);
                            $action="Created new Jalabhagya application ".$request->application_number;       
                             UserLogHelper::createUserLog($sequence_no, $category, $action);
                            return Response::json(['success' => '1']);
                        }
                      return Response::json(['errors' => $validator->errors()]);
            }
            else if($application_type_id==4)
            {
                  $validator = Validator::make($request->all(), [
                       'application_number' => 'required|unique:consumer_application,application_number',
                       'phone_number' => 'required|nullable|numeric|digits_between:10,15',
                       'customer_name' => 'required',
                       'door_no'=>'required',
                       'ward_id' => 'required',
                       'corp_ward_id'=>'required',
                       'premises_address'=>'required'
                     ]);
                        if ($validator->passes()) {
                            
                            $files = $request->file('document_name');
                            $filename = [];
                            $certificate=[];
                            $i=0;
                            if($request->caste_doc)
                            {
                                foreach ($request->caste_doc as $photo) { 
                                    $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                    $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                    $fileName = $file.'.'.$extension;
                                    $certificate[$i] = $fileName; 
                                    $i++;
                                     Storage::putFileAs('public/images', $photo, $fileName);
                                }
                               
                            }
                            if($request->bpl_card_doc)
                            {
                                foreach ($request->bpl_card_doc as $photo) { 
                                    $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                    $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                    $fileName = $file.'.'.$extension;
                                    $filename[$i] = $fileName; 
                                    $i++;
                                   Storage::putFileAs('public/images', $photo, $fileName);
                                }
                                
                            }
                             if($request->income_doc)
                            {
                                foreach ($request->income_doc as $photo) { 
                                    $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                    $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                    $fileName = $file.'.'.$extension;
                                    $certificate[$i] = $fileName; 
                                    $i++;
                                     Storage::putFileAs('public/images', $photo, $fileName);
                                }
                                  
                            }
                               if($request->tax_paid_doc)
                            {
                                foreach ($request->tax_paid_doc as $photo) { 
                                    $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                    $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                    $fileName = $file.'.'.$extension;
                                    $filename[$i] = $fileName; 
                                    $i++;
                                     Storage::putFileAs('public/images', $photo, $fileName);
                                }
                                  
                            }
                               if($request->blueprint_doc)
                            {
                                foreach ($request->blueprint_doc as $photo) { 
                                    $file = $request->application_number.'_'.round(microtime(true) * 1000); //Get Image Name
                                    $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                                    $fileName = $file.'.'.$extension;
                                    $filename[$i] = $fileName; 
                                    $i++;
                                     Storage::putFileAs('public/images', $photo, $fileName);
                                }
                                  
                            }
                            
                        
                           $filenameString = implode (", ", $filename);  
                           $certificateString = implode (", ", $certificate);  

                              $tap_application_entry = ConsumerApplication::create([
                                             'application_number'=>$request->application_number,
                                             'application_type_id' =>  $application_type_id,
                                             'customer_name' =>$request->customer_name , 
                                             'ward_id' =>$request->ward_id , 
                                             'corp_ward_id' =>$request->corp_ward_id , 
                                             'door_no' =>$request->door_no , 
                                             'phone_number' =>$request->phone_number, 
                                             'connection_type_id' =>3 , 
                                             'document_name' =>$filenameString , 
                                             'certificate_name'=>$certificateString,
                                             'application_date' =>$application_date,
                                             'application_status_id'=>2,
                                             'inserted_by' =>  auth()->user()->id
                                 ]);

                                $consumer_address = ConsumerAddress::create([
                                             'application_id' => $tap_application_entry->id,
                                             'premises_address' => $request->premises_address,
                                             'premises_state' => $request->premises_state,
                                             'premises_street' => $request->premises_street,
                                             'premises_city'=>$request->premises_city,
                                             'premises_zip'=>$request->premises_zip,
                                             'inserted_by' =>  auth()->user()->id
                                            
                                 ]);
                                 $annual_income_verified_date=date("Y-m-d", strtotime($request->annual_income_verified_date));
                                 $income_tax_paid_date=date("Y-m-d", strtotime($request->income_tax_paid_date));
                                 $consumer_seven_extra = ConsumerApplicationSeven::create([
                                             'application_id' => $tap_application_entry->id,
                                             'father_name' => $request->father_name,
                                             'caste' => $request->caste,
                                             'sub_caste'=>$request->sub_caste,
                                             'bpl_card_no'=>$request->bpl_card_no,
                                             'annual_income' => $request->annual_income,
                                             'annual_income_verified_date' =>$annual_income_verified_date,
                                             'income_tax_paid_date'=>$income_tax_paid_date,
                                             'water_system'=>$request->water_system,
                                             'inserted_by' =>  auth()->user()->id
                                            
                                 ]);
                             
                                 $action="Created new Domestic 7.25 application ".$request->application_number;       
                             UserLogHelper::createUserLog($sequence_no, $category, $action);
                            return Response::json(['success' => '1']);
                        }
                      return Response::json(['errors' => $validator->errors()]);
            }

    }
}