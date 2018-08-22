<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\ConsumerConnection;
use View;

class DashboardController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {
        //$this->middleware('auth'); 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response include.user_header
     */
    public function index() {
        $consumerDetail['sequence_number'] = Auth::guard('consumer')->user()->sequence_number;
        $this->connection_id = Auth::guard('consumer')->user()->id;
        //FETCHING CONSUMER DETAIL
        $consumerDetailObj = ConsumerConnection::getConnectionDetail($this->connection_id)->first();
        $data['consumer_name'] = Auth::guard('consumer')->user()->name;
        if (!empty($consumerDetailObj)) {
            $consumerDetail = $consumerDetailObj->toArray();            
            $consumerDetail['activeBill'] = 1;
        } else {
            $consumerDetail['activeBill'] = 0;
        }
        View::composer('include.user_sidebar', function($view) use ($data) {
            return $view->with('user_data', $data);
        });
        View::composer('include.user_header', function($view) use ($data) {
            return $view->with('user_log_data', $data);
        });
        return view('user.dashboard', $consumerDetail);
    }

}
