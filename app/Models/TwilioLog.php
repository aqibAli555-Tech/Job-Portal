<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwilioLog extends Model
{
    use HasFactory;
    protected $table = 'twilio_logs';

    protected $fillable = ['user_id', 'post_ids', 'number', 'type', 'message','is_sent','response'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function get_twilio_log($request)
    {
        $twiliolog = new self();
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        if (!empty($request->get('search'))) {
            $twiliolog = $twiliolog->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('message', 'LIKE', "%{$search}%");
            });
        }
        $twiliolog = $twiliolog->orderBy('id', 'DESC');

        if (!empty($request->get('start'))) {
            return $twiliolog->skip($request->get('start'))->take($limit)->get();
        }
        $twiliolog = $twiliolog->paginate($limit)->appends(request()->query());
        return $twiliolog;
    }

    public static function get_twilio_log_count($request)
    {
        $twiliolog = new self();
        if (!empty($request->get('search'))) {
            $twiliolog = $twiliolog->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('message', 'LIKE', "%{$search}%");
            });
        }
        $twiliolog = $twiliolog->orderBy('id', 'DESC');
        $twiliolog = $twiliolog->count();
        return $twiliolog;
    }
}
