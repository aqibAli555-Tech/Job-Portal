<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class PackageCancelReason extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'package_cancel_reason';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'status',

    ];
    public static function get_all_cancel_reasons(){
        return self::where('status',1)->get();
    }
      public static function get_cancel_reasons_with_id($id){
        return self::where('id',$id)->first();
    }


    public static function get_cancel_reasons_with_count(){

        $reasonsWithPercentage = DB::table('package_cancel_reason')
        ->select('package_cancel_reason.id','package_cancel_reason.title as cancel_reason',
            DB::raw('COUNT(user_cancelled_packages.id) as total_rejections'),
            DB::raw('(COUNT(user_cancelled_packages.id) / total.total_rejections) * 100 as percentage')
        )
        ->leftJoin('user_cancelled_packages', 'package_cancel_reason.id', '=', 'user_cancelled_packages.cancel_reason_id')
        ->crossJoin(DB::raw('(SELECT COUNT(user_cancelled_packages.id) as total_rejections FROM user_cancelled_packages WHERE cancel_reason_id IS NOT NULL) as total'))
        ->groupBy('package_cancel_reason.id')
        ->orderByDesc('percentage')
        ->get();
       return $reasonsWithPercentage;

    }

}