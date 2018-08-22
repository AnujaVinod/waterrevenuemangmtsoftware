<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class UsersCategory extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_users_category';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id'; 
    
    public static function getAdminSubcategory($categoryId) {
        $data = [];
        $data = UsersCategory::
                join('master_users_sub_category','master_users_category.id','=','master_users_sub_category.category_id')           
                ->where('master_users_category.id', $categoryId)
                    ->orderBy('master_users_sub_category.sub_category_name', 'ASC')
                ->get(['master_users_sub_category.sub_category_name','master_users_sub_category.id']);
        return $data;
    } 
  
}

