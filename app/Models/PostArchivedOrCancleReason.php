<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostArchivedOrCancleReason extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'post_archived_or_delete_reasons';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'status',
    ];

    public static function get_all_reasons()
    {
        return self::where('status', 1)->get();
    }

    public static function get_reasons_with_id($id)
    {
        return self::where('id', $id)->first();
    }

    public static function get_reasons_with_count()
    {
        $allReasons = self::get_all_reasons();

        $totalArchivedCount = CompanyPostArchivedReason::count();
        $reasonCounts = CompanyPostArchivedReason::select('reason_id', DB::raw('COUNT(reason_id) as reason_count'))
            ->groupBy('reason_id')
            ->get()
            ->keyBy('reason_id');

        $reasonStatistics = $allReasons->map(function ($reason) use ($reasonCounts, $totalArchivedCount) {
            $reason_id = $reason->id;
            $reason_count = $reasonCounts->has($reason_id) ? $reasonCounts->get($reason_id)->reason_count : 0;
            $percentage = $totalArchivedCount > 0 ? ($reason_count / $totalArchivedCount) * 100 : 0.000;

            return (object)[
                'reason_id' => $reason_id,
                'title' => $reason->title,
                'reason_count' => $reason_count,
                'percentage' => $percentage,
            ];
        });
        $reasonStatistics = $reasonStatistics->sortByDesc('percentage');

        return $reasonStatistics;
    }

}