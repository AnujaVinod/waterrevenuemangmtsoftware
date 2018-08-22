<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\ApiController;
use JWTAuth;
use App\User;
use App\Models\Agent;
use DB;

use JWTAuthException;

class LoginController extends ApiController
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * User registration.
     *
     * @param  \Illuminate\Http\Request  $request 
     */
    public function register(Request $request)
    {
        $user = $this->user->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);
        if ($user) {
            $response = User::find($user->id);
            return $this->respondWithSuccess('USER_CREATED', $response);
        }
    }

    /**
     * User login.
     *
     * @param  \Illuminate\Http\Request  $request 
     */
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->respondWithSuccessError('Invalid Username and Password!', []);
            }
        } catch (JWTAuthException $e) {
            return $this->respondWithSuccessError('could_not_create_token', []);
        } catch(\Exception $e){
            return $this->respondWithError('failure', []);
        }
        $customArray = [];
        $customArray['token'] = $token;
        //$customArray['user'] = array(auth()->user()->toArray());
        $userWardCheck = User::
                where('users.id',auth()->user()->id)
                ->first();
        if($userWardCheck->cat_id == '3' || $userWardCheck->cat_id == '4'){
            $customArray['user'] = User::
                    leftjoin('agents','agents.agent_user_id','=','users.id')
                ->where('users.id',auth()->user()->id)
                ->get([
                    'users.id','users.name','users.cat_id','users.status','users.email',
                    DB::raw('agents.corpward_id as corp_ward_id')                    
                ]);
            //$data = ['token' => $token,'user' => auth()->user()->toArray()];        
            return $this->respondWithSuccess('Login Successful', $customArray); 
        } else {
            return $this->respondWithSuccessError('Invalid User!', []);
        }
        
    }

    /**
     * To get authorized users
     *
     * @param  \Illuminate\Http\Request  $request 
     */
    public function getAuthUser(Request $request)
    {
        // if (!$request->token) {            
        //     return $this->respondWithError('AUTH_ERROR', 'Token is required!');
        // }
        $user = JWTAuth::toUser($request->token);
        $customArray = [];
        $customArray['user'] = array($user);
        return $this->respondWithSuccess('SUCCESS', $customArray);
    }

    /**
     * To get refresh token
     *
     * @param  \Illuminate\Http\Request  $request 
     */
    public function refreshToken(Request $request)
    {
        try {
            $token = \JWTAuth::getToken();
            $token = \JWTAuth::refresh($token);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $exception) {
            return $this->respondWithSuccessError($exception->getMessage(), []);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $exception) {
            return $this->respondWithSuccessError($exception->getMessage(), []);
        } catch (JWTException $exception) {
            return $this->respondWithSuccessError($exception->getMessage(), []);
        } catch(\Exception $e){
            return $this->respondWithError('failure', []);
        }
        return $this->respondWithSuccess('REFRESH_TOKEN', compact('token'));
        /*
          try {
          $token = \JWTAuth::getToken();
          $token = \JWTAuth::refresh($token);
          } catch (JWTException $exception) {
          return $this->respondWithError('could_not_refresh_token' + $exception->getMessage(), []);
          }
          return $this->respondWithSuccess('REFRESH_TOKEN', compact('token')); */
    }

    /**
     * User Logout
     *
     * @param  \Illuminate\Http\Request  $request 
     */
    public function logout()
    {
        try {
            // \JWTAuth::invalidate();
            \JWTAuth::parseToken()->invalidate();
        } catch (JWTException $ex) {
            return $this->respondWithError('could_not_invalidate_token', []);
        } catch(\Exception $e){
            return $this->respondWithError('failure', []);
        }
        return $this->respondWithSuccess('Logged out successfully', []);
    }

    /**
     * To get agents Login details
     *
     * @param  \Illuminate\Http\Request  $request 
     */
    public function getAgentLogins(Request $request)
    {
        $categoryId = 3;
        $user = User::retriveAgents($categoryId);
        $customArray = [];
        $customArray['agets'] = $user['agents'];
        //print_r($user); exit;
        if ($customArray) {
            return $this->respondWithSuccess('AGENT LOGINS', $customArray);
        }
        return $this->respondWithError('FAILURE MESSAGE', $customArray);
    }

}
