<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactCardsRemaining extends Model
{
    use HasFactory;
    protected $table = 'contact_cards_remaining';
    protected $fillable = ['employee_id', 'employer_id', 'package_id', 'is_package_expire', 'package_expire_date'];
    public static function check_total_used_credits($employer_id){
        return ContactCardsRemaining::where('employer_id',$employer_id)->where('is_package_expire',0)->count();
    }
    public static function check_total_credit_available($employer_id){
        return ContactCardsRemaining::where('employer_id',$employer_id)->where('is_package_expire',0)->count();
    }
    
    public static function check_credit_available_by_package_id($employer_id,$package_id){
        return ContactCardsRemaining::where('employer_id',$employer_id)->where('package_id',$package_id)->whereNull('employee_id')->where('is_package_expire',0)->count();
    }
    
}
