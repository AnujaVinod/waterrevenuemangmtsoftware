<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'designation'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_designation';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
    
        
    public static function getUserDesignation($catId){
        $data   = Designation::
               where('cat_id', $catId) 
                ->get();
                //->value([                    
                //    'users.id', 'users.name','users.status','users.email',
                 //   'master_users_sub_category.sub_category_name',
                //    'admin_details.contact_no'
                //]);
        return $data;
    }
  
}

