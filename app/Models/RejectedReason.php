<?php


namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RejectedReason extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'rejected_reason';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'status',

    ];

    public static function get_all_rejected_reasons()
    {
        return self::where('status', 1)->get();
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'rejected_reason_id');
    }

    public static function get_rejected_reasons_with_count()
    {
        $reasonsWithPercentage = DB::table('rejected_reason')
            ->select(
                'rejected_reason.title',
                DB::raw('COUNT(applicants.id) as total_rejections'),
                DB::raw('(COUNT(applicants.id) / total.total_rejections) * 100 as percentage')
            )
            ->leftJoin('applicants', 'rejected_reason.id', '=', 'applicants.rejected_reason_id')
            ->crossJoin(DB::raw('(SELECT COUNT(applicants.id) as total_rejections FROM applicants WHERE rejected_reason_id IS NOT NULL) as total'))
            ->groupBy('rejected_reason.title')
            ->orderByDesc('percentage')
            ->get();
        return $reasonsWithPercentage;
    }

    public static function get_reason_with_id($id)
    {
        return self::where('id', $id)->first();
    }


    public static function get_reasons($request)
    {
        $model = self::query();
        $search = $request->get('search')['value'];
        if (!empty($search)) {
            $model->where('title', 'LIKE', "%{$search}%");
        }
        return $model->orderBy('title')->get();
    }

    public static function get_reasons_count($request)
    {
        $model = self::query();
        $search = $request->get('search')['value'];
        if (!empty($search)) {
            $model->where('title', 'LIKE', "%{$search}%");
        }
        return $model->count();
    }
}