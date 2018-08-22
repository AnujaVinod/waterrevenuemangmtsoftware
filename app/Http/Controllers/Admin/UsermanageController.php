<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\UsersCategory;
use App\Models\UsersSubCategory;
use App\Models\AdminDetail;
use App\Models\Designation;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use Hash;

class UsermanageController extends Controller
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
        $categoryId = 1;
        $subCategory = UsersCategory::getAdminSubcategory($categoryId);
        $designations = Designation::all();           
        return view('admin.usermanage', ['subCategory' => $subCategory, 'designations' => $designations]);
    }

    public function register(Request $request)
    {
        $messages = array(           
            'name.regex' => 'Invalid User Name'
        );
        
        if($request->category == '5'){
            //$validator->errors()->add('bank_name', 'The Bank name field is required');
            $validator_set = Array('name' => 'required|max:255|unique:users|regex:/^([A-Za-z])+([A-Za-z0-9_\.\s-\/])*$/',
                    'email' => 'email|nullable|unique:users',
                    'password' => 'required|min:6|confirmed',
                    'contact_no' => 'nullable|numeric|digits_between:10,15',
                    'category' => 'required',
                    'role' => 'required',
                    'status' => 'required',
                    'bank_name' => 'required',
                    'bank_branch' => 'required');
        }else{
            $validator_set = Array('name' => 'required|max:255|unique:users|regex:/^([A-Za-z])+([A-Za-z0-9_\.\s-\/])*$/',
                    'email' => 'email|nullable|unique:users',
                    'password' => 'required|min:6|confirmed',
                    'contact_no' => 'nullable|numeric|digits_between:10,15',
                    'category' => 'required',
                    'role' => 'required',
                    'status' => 'required');
        }
        $validator = Validator::make($request->all(),  $validator_set ,$messages);
        //if($request->category == '5' && $request->bank_branch == ''){
       //     $validator->errors()->add('bank_branch', 'The Bank branch field is required');
       // }
        if ($validator->passes()) {
            $user = User::create([
                        'name' => trim($request->name),
                        'email' => trim($request->email),
                        'password' => bcrypt(trim($request->password)),
                        'cat_id' => $request->category,
                        'status' => $request->status,
            ]);
            $adminDetail = AdminDetail::create([
                        'user_id' => $user->id,
                        'sub_category_id' => $request->role,
                        'designation_id' => $request->designation,
                        'contact_no' => trim($request->contact_no),
                        'bank_name' => trim($request->bank_name),
                        'bank_branch' => trim($request->bank_branch)
            ]);
            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function getUsers()
    {
        $users = User::retriveUsersInfo();
        return Datatables::of($users)
                        ->addColumn('action', function ($users) {
                            return '<p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" id="editBtn" data-value="' . $users->id . '" data-catid="'.$users->cat_id.'" data-title="Edit" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></button></p>';
                        })
                        ->editColumn('status', function ($users) {
                            return ($users->status == 1) ? 'Active' : 'Inactive';
                        })                        
                        ->editColumn('cat_id', function ($users) {
                            if($users->cat_id == 7){
                                return 'EXECUTIVE';
                            }elseif($users->cat_id == 1){
                                return 'MCC';
                            }else{
                                return 'BANK';
                            }                           
                        })
                        ->rawColumns(['action' => 'action'])
                        ->make(true);
    }

    public function getUserInfo(Request $request)
    {
        $user = User::getUserInfo($request->userId);
        $designation = Designation::getUserDesignation($request->catId); 
        if ($user) {
            return Response::json(['success' => '$user', 'user' => $user, 'designation' => $designation]);
        } else {
            return Response::json(['errors' => 'Error Occured']);
        }
    }

    public function updateUserInfo(Request $request)
    {
        if($request->catid == '5'){
            //$validator->errors()->add('bank_name', 'The Bank name field is required');
            $validator_set = Array('email' => 'email|nullable',
                    'contact_no' => 'nullable|digits_between:10,15',
                    'role' => 'required',
                    'bank_name' => 'required',
                    'bank_branch' => 'required');
        }else{
            $validator_set = Array('email' => 'email|nullable',
                    'contact_no' => 'nullable|digits_between:10,15',
                    'role' => 'required');
        }
        $validator = Validator::make($request->all(),  $validator_set);
        
        
        
     //   $validator = Validator::make($request->all(), [
      //              'email' => 'email|nullable',
      //              'contact_no' => 'nullable|digits_between:10,15',
      //              'role' => 'required'
      //  ]);

        if ($validator->passes()) {
            User::where('id', '=', $request->userId)
                    ->update(['email' => trim($request->email), 'status' => $request->status]);
            AdminDetail::where('user_id', '=', $request->userId)
                    ->update(['sub_category_id' => $request->role, 'designation_id' => $request->designation, 'contact_no' => $request->contact_no,'bank_name' => $request->bank_name, 'bank_branch' => $request->bank_branch ]);
            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    /*
      public function deleteUser(Request $request)
      {
      User::where('id', '=', $request->userid)
      ->update(['status' => '0']);
      return Response::json(['success' => '1']);
      } */

    public function changePassword()
    {
        return view('admin.changepassword');
    }

    public function resetPassword(Request $request)
    {
        if (!(Hash::check(trim($request->current_password), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }

        if (strcmp(trim($request->current_password), trim($request->new_password)) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }
        
        $this->validate($request, [
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);
        $user = Auth::user();
        $user->password = bcrypt(trim($request->new_password));
        $user->save();

        return redirect()->back()->with("success", "Password changed successfully !");       
    }

    public function getUserDesignation(Request $request)
    {
        $designation = Designation::getUserDesignation($request->cat_id);        
        if ($designation) {
            return Response::json(['success' => 'success', 'designation' => $designation]);
        } else {
            return Response::json(['errors' => 'Error Occured']);
        }
        
    }
}
