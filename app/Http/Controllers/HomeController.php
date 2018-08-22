<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
class HomeController extends Controller
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
        $userId = Auth::user()->id;
        $categoryId = User::where('id',$userId)->first()->cat_id;        
        if($categoryId == 2){
            return redirect('user/dashboard');
           // return view('admin.dashboard',['userName'=> $userName]);
        }else {
            return redirect('admin/dashboard');        
        }
    }
}
