<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\DeleteBill;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Response;
use Carbon\Carbon;
use App\Helpers\UserLogHelper;



class DeleteBillController extends Controller
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

         return view('admin.delete_record');

    }
    public function getBasicInfo(Request $request)

    {

        $seq_no=$request->sequence_number;
        $check_seq_no=DeleteBill::sequenceNoCheck($seq_no);

        if(count($check_seq_no))
        {
            $get_datas=DeleteBill::deleteRecordCountCheck($seq_no);
            if($get_datas->count())
                 {
                    $get_data=DeleteBill::getBasicDetails($seq_no);
                       if($get_data->count())
                         {
                                foreach ($get_data as $billinfo) {
                                 return $billinfo;
                             }
                         }
                 }
                 else
                 {
                    return '1';
                 }
            }
        else
        {
           return '0';
        }
    }

    public function deleteBillInfo(Request $request)
    {

        $seq_no=$request->sequence_number;
        $get_datas=DeleteBill::deleteRecordCountCheck($seq_no);
        if($get_datas->count())
             {
                foreach($get_datas as $get_data)
                {
                   $last_id=$get_data->id;

                    $get_datas=DeleteBill::deleteRecordData($last_id,$seq_no);
                    $action = 'Bill deleted';
                    UserLogHelper::createUserLog($seq_no,'billupdated' , $action);
                     return '1';
                    }


             }
    }
}
