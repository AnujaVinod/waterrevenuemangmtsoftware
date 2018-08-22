<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'cat_id', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public static function retriveAgents($categoryId) {
        $data = [];
        $data['agents'] = User::
                join('agents', 'users.id', '=', 'agents.agent_user_id')                
                ->where('users.cat_id', $categoryId)
                    ->orderBy('users.id', 'ASC')
                ->get([                    
                    'users.id', 'users.name',
                    'agents.agent_code','users.updated_at'
                ]);
        return $data;
    }
    
    public static function retriveUsersInfo() {
        //$data = [];
        $data   = User::
                join('admin_details', 'users.id', '=', 'admin_details.user_id')
                ->join('master_users_sub_category', 'admin_details.sub_category_id','=','master_users_sub_category.id')
                ->where('users.cat_id', 1)
                ->orWhere('users.cat_id', 5)
                ->orWhere('users.cat_id', 7)
                    ->orderBy('users.id', 'ASC')
                ->get([                    
                    'users.id', 'users.name','users.status','users.cat_id',
                    'master_users_sub_category.sub_category_name',
                    'admin_details.contact_no'
                ]);
        return $data;
    }
    
    public static function getUserInfo($userId) {
        $data   = User::
                join('admin_details', 'users.id', '=', 'admin_details.user_id')
                ->join('master_users_sub_category', 'admin_details.sub_category_id','=','master_users_sub_category.id')
                ->where('users.id', $userId) 
                ->first();
                //->value([                    
                //    'users.id', 'users.name','users.status','users.email',
                 //   'master_users_sub_category.sub_category_name',
                //    'admin_details.contact_no'
                //]);
        return $data;
    }
}
