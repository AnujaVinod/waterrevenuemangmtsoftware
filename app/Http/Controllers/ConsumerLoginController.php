<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ConsumerLoginController extends Controller
{
    public function authenticateConsumer(Request $request){        
        $username = ($request->sequence_number != NULL) ? $request->sequence_number : $request->Phone_Number;
        $attempt = "";
        if($username != NULL){
           $attempt = Auth::guard('consumers')->attempt(['sequence_number' => 4568]); 
           dd($attempt);
        }
        /**************/
        
//        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)? $this->username() : 'name';        
//        return [
//            $field => $request->get($this->username())
//        ];
    }
}
