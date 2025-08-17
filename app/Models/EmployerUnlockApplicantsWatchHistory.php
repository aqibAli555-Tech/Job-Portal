<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerUnlockApplicantsWatchHistory extends Model
{
    use HasFactory;
    public $table = 'employer_unlock_applicants_watch_history';

    public static function add_employer_check_applied_applicants_history(){
            $user_type = auth()->user()->type_id; // if 1 then employer
            $company_id = auth()->user()->id;
            if(!self::check_is_employer_check_applied_unlock_applicants($company_id)){
                $model = new EmployerUnlockApplicantsWatchHistory();
                $model->company_id = $company_id;    
                $model->save();    
            }
    }


    public static function check_is_employer_check_applied_unlock_applicants($company_id = ''){
        if(empty($company_id)){
            $company_id = auth()->user()->id;
        }
        $is_check = EmployerUnlockApplicantsWatchHistory::where('company_id', $company_id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->count();
        if($is_check > 0){
            return true;
        }else{
            return false;
        }
    }

}
