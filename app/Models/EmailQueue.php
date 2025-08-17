<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmailQueue extends Model
{
    use HasFactory;

    protected $table = 'email_queue';

    protected $fillable = ['from', 'to', 'status', 'body', 'subject', 'attachment', 'reply_to', 'priority', 'created_at', 'updated_at'];

    public static function get_latest_email_queue_count()
    {

        return DB::table('email_queue')->whereBetween('created_at', [now()->subDays(10), now()])->selectRaw('DATE(created_at) as date, COUNT(id) as count')
            ->orderBy('date', 'desc')->groupBy('date')->get();

    }

    public static function get_email_log($request)
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $emaillog = new self();
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        if (!empty($request->get('search'))) {
            $emaillog = $emaillog->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('to', 'LIKE', "%{$search}%")
                ->orWhereRaw("CAST(subject AS CHAR) LIKE ?", ["%{$search}%"]);
            });
        }
        $emaillog = $emaillog->where('created_at', '>=', $thirtyDaysAgo)->orderBy('id', 'DESC');

        if (!empty($request->get('start'))) {
            return $emaillog->skip($request->get('start'))->take($limit)->get();
        }
        $emaillog = $emaillog->paginate($limit)->appends(request()->query());
        return $emaillog;
    }

    public static function get_email_log_count($request)
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $emaillog = new self();
        if (!empty($request->get('search'))) {
            $emaillog = $emaillog->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('to', 'LIKE', "%{$search}%")
                ->orWhereRaw("CAST(subject AS CHAR) LIKE ?", ["%{$search}%"]);
            });
        }
        $emaillog = $emaillog->where('created_at', '>=', $thirtyDaysAgo)->orderBy('id', 'DESC');
        $emaillog = $emaillog->count();
        return $emaillog;
    }
}
