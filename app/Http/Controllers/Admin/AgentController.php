<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Agent;
use App\Models\Ward;
use App\Models\CorpWard;
use App\Models\UsersCategory;
use App\Models\Inspector;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use Userlogs;
use App\Helpers\UserLogHelper;

class AgentController extends Controller
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
        $categoryId = UsersCategory::
                where('category_name', '=', 'agent')
                ->value('id');
        $wards = Ward::orderBy('ward_name')->get();
        $corpwards = CorpWard::all()->sortBy("corp_name");
        return view('admin.agent', ['categoryId' => $categoryId, 'wards' => $wards, 'corpwards' => $corpwards]);
    }

    public function getAgents()
    {
        $categoryId = UsersCategory::
                where('category_name', '=', 'agent')
                ->value('id');
        $agents = Agent::retrieveAgents($categoryId);
        return Datatables::of($agents)
                        ->addColumn('action', function ($agents) {
                            return ($agents->status == 1) ? '<button class="btn btn-danger btn-flat pull-right changestatus" data-action="deactivate" data-value="' . $agents->id . '" data-name="' . $agents->name . '" >Deactivate</button>' : '<button data-value="' . $agents->id . '" data-name="' . $agents->name . '" data-action="activate" class="btn btn-danger btn-flat pull-right changestatus">Acivate</button>';
                        })
                        ->editColumn('status', function ($agents) {
                            return ($agents->status == 1) ? 'Active' : 'Inactive';
                        })
                        ->rawColumns(['action' => 'action'])
                        ->make(true);
    }

    public function saveAgent(Request $request)
    {
        $messages = array(
            'regex' => 'Invalid :attribute',
            'unique' => ':attribute has already been taken'
        );
        $this->validate($request, [
            'agent_code' => 'required|unique:agents,agent_code|regex:/^[A-Za-z0-9\.\s-\/]+$/|max:25',
            'agent_name' => 'required|unique:agents,agent_name|regex:/^[A-Za-z\.\s\']+$/|max:150',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'ward_name' => 'required',
            'inspector_name' => 'required'
                ], $messages);
        $user = User::create([
                    'name' => trim($request->agent_name),
                    'password' => bcrypt(trim($request->password)),
                    'cat_id' => $request->cat_id,
                    'status' => 1,
        ]);
        $inspector = Agent::create([
                    'agent_user_id' => $user->id,
                    'agent_name' => trim($request->agent_name),
                    'agent_code' => trim($request->agent_code),
                    'inspector_id' => $request->inspector_name,
                    'ward_id' => $request->ward_name,
                    'corpward_id' => $request->corp_ward
        ]);
        $sequenceNo = '';
        $action = 'New agent '.$request->agent_name.' to the ward '.$request->ward_name.' is added';
        UserLogHelper::createUserLog($sequenceNo, 'Agent', $action);
        return redirect()->back()->with("success", "Agent added successfully !");
    }

    public function changeAgentStatus(Request $request)
    {
        if ($request->action == 'deactivate') {
            $agentaction = "Deactivated agent " . $request->username;
            $data = User::where('id', '=', $request->userId)
                    ->update(['status' => '0']);
        } else {
            $agentaction = "Activated agent " . $request->username;
            $data = User::where('id', '=', $request->userId)
                    ->update(['status' => '1']);
        }
        $sequenceNo = '';
        UserLogHelper::createUserLog($sequenceNo, 'Agent', $agentaction);
        if ($data) {
            return Response::json(['success' => '1']);
        } else {
            return Response::json(['errors' => 'Error Occured']);
        }
    }

    public function getInspectorWard(Request $request)
    {
        $wardId = $request->wardId;
        $inspectorList = Agent::getInspectorWard($wardId);
        if ($inspectorList->isNotEmpty()) {
            return Response::json(['success' => '1', 'inspectors' => $inspectorList]);
        } else {
            return Response::json(['success' => '0']);
        }
    }

}
