<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Inspector;
use App\User;
use App\Models\UsersCategory;
use App\Models\Ward;
use App\Models\InspectorWard;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use Userlogs;
use App\Helpers\UserLogHelper;

class InspectorController extends Controller
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
        $data = UsersCategory::
                where('category_name', '=', 'inspector')
                ->value('id');
        $wards = Ward::orderBy('ward_name')->get();
        return view('admin.inspector', ['data' => $data, 'wards' => $wards]);
    }

    public function getInspector()
    {
        $categoryId = UsersCategory::
                where('category_name', '=', 'inspector')
                ->value('id');
        $inspectors = Inspector::retrieveInspector($categoryId);
        $wardsList = InspectorWard::all();
        return Datatables::of($inspectors)
                        ->addColumn('action', function ($inspectors) {
                            return ($inspectors->status == 1) ? '<button class="btn btn-danger btn-flat pull-right changestatus" data-action="deactivate" data-value="' . $inspectors->id . '" data-name="' . $inspectors->name . '">Deactivate</button>' : '<button data-value="' . $inspectors->id . '" data-name="' . $inspectors->name . '" data-action="activate" class="btn btn-danger btn-flat pull-right changestatus">Acivate</button>';
                        })
                        ->editColumn('status', function ($inspectors) {
                            return ($inspectors->status == 1) ? 'Active' : 'Inactive';
                        })
                        ->rawColumns(['action' => 'action'])
                        ->make(true);
    }

    public function saveInspector(Request $request)
    {
        $messages = array(
            'inspector_code.required' => 'The Inspector Code field is required',
            'inspector_name.required' => 'The Inspector Name field is required',
            'inspector_code.regex' => 'Invalid Inspector Code',
            'inspector_name.regex' => 'Invalid Inspector Name',
            'inspector_code.unique' => 'Inspector Name already exists.',
            'inspector_code.unique' => 'Inspector Code already exists.',
            'password.min' => 'The Password must be at least 6 characters.',
            'confirm_password.same' => 'Confirm Password and Password must match'
        );
        $this->validate($request, [
            'inspector_code' => 'required|unique:inspector,inspector_code|regex:/^[A-Za-z0-9\.\s-\/]+$/|max:25',
            'inspector_name' => 'required|unique:inspector,inspector_name|regex:/^[A-Za-z\.\s\']+$/|max:150',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'ward_name.*' => 'required'
                ], $messages);
        $user = User::create([
                    'name' => $request->inspector_name,
                    'password' => bcrypt($request->password),
                    'cat_id' => $request->cat_id,
                    'status' => 1,
        ]);
        $inspector = Inspector::create([
                    'inspector_name' => $request->inspector_name,
                    'inspector_code' => $request->inspector_code,
                    'inspector_user_id' => $user->id
        ]);
        foreach ($request->ward_name as $ward) {
            $inspectorward = InspectorWard::create([
                        'inspector_id' => $inspector->id,
                        'ward_id' => $ward
            ]);
        }
        $sequenceNo = '';
        $action ="New inspector ".$request->inspector_name." is added";
        UserLogHelper::createUserLog($sequenceNo, 'inspector', $action);
        return redirect()->back()->with("success", "Inspector added Successfully !");
    }

    public function changeInspectorStatus(Request $request)
    {
        if ($request->action == 'deactivate') {
            $action = "Deactivated Inspector " . $request->username;
            $data = User::where('id', '=', $request->userId)
                    ->update(['status' => '0']);
        } else {
            $action = "Activated Inspector " . $request->username;
            $data = User::where('id', '=', $request->userId)
                    ->update(['status' => '1']);
        }
        $sequenceNo = '';
        UserLogHelper::createUserLog($sequenceNo, 'inspector', $action);
        if ($data) {
            return Response::json(['success' => '1']);
        } else {
            return Response::json(['errors' => 'Error Occured']);
        }
    }

}
