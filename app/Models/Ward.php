<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ward_name', 'zone_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_ward';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public static function retriveWardInfo() {
        //$data = [];
        $data   = Ward::
                join('master_zone', 'master_ward.zone_id', '=', 'master_zone.id')
                ->orderBy('master_ward.ward_name', 'ASC')
                ->get([                    
                    'master_ward.ward_name',
                    'master_zone.zone_name'
                ]);
        return $data;
    }
}
