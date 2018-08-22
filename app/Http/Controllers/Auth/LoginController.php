<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\ConsumerConnection;
use Illuminate\Support\MessageBag;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'name';        
        return [
            $field => $request->get($this->username()),
            'password' => $request->password,
        ];
    }
    
    public function authenticateConsumer(Request $request){   
        $data['sequence_number'] = $request->sequence_number;
        if(empty($request->sequence_number) || $request->sequence_number == ""){
            $errors = new MessageBag(['loginerror' => ["Sequence Number cannot be empty ! Please enter a valid one!"]]);
            return redirect('/login')->withErrors($errors)->withInput($data);
        }
        $userDetail = ConsumerConnection::getUserDetailBySequenceNumber($data)->get()->toArray();
        $attempt = "";
        if(!empty($userDetail)){
            $username = $userDetail[0]['id'];                    
            $attempt = Auth::guard('consumer')->loginUsingId($username);              
        }else{
            $attempt = 0;
        }        
        if($attempt){            
            session::put('sequence_number',$request->sequence_number);
            //REDIRECTING TO DASHBOARD
            return redirect('consumer/dashboard');
        }else{
            $errors = new MessageBag(['loginerror' => ["Failed to login! Sequence number is no longer available!"]]);
            $data['from_page'] = 'consumer';
            //REDIRECTING BACK TO LOGIN PAGE
            return redirect('/login')->withErrors($errors)->withInput($data);
        }
        
    }
}
