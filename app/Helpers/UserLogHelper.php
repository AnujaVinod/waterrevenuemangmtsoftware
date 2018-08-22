<?php
namespace App\Helpers;

use App\Models\UserLog;
//use App\Models\ApplicationLogs;

class UserLogHelper {
    /**
     * @param int $user_id User-id
     * 
     * @return string
     */
    public static function createUserLog($sequence_no, $category, $action) {
        $userdetail = auth()->user();
        $logDetail = array(
                'user_id' => $userdetail->id,                
                'ipaddress' => \Request::ip(),
                'sequence_no' => $sequence_no,
                'category' => $category,
                'action' => $action               
        );
        UserLog::create($logDetail);        
    }
    
  /*  public static function ApplicationLogs($category, $action) {
        $userdetail = auth()->user();
        $logDetail = array(
                'user_id' => $userdetail->id,                
                'ipaddress' => \Request::ip(),
                'category' => $category,
                'action' => $action               
        );
        ApplicationLogs::insert($logDetail);        
    }
   
   */
}