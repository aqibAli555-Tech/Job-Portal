<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Activities extends Model
{
    use HasFactory;

    protected $table = 'activities';
    protected $fillable = ['route', 'method', 'referrer', 'ip_address', 'request', 'description','type'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public static function get_log($request)
    {
        $pagelog = new self();
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        if (!empty($request->get('search'))) {
            $pagelog = $pagelog->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('description', 'LIKE', "%{$search}%");
            });
        }
        if (!empty($request->get('company'))) {
            $pagelog = $pagelog->where(function ($query) use ($request) {
                $company = $request->get('company');
                $query->where('user_id', $company);
            });
        }
        if (!empty($request->get('type'))) {
            $pagelog = $pagelog->where(function ($query) use ($request) {
                $query->where('type', $request->get('type'));
            });
        }
        $pagelog = $pagelog->with('user')->orderBy('id', 'DESC');

        if (!empty($request->get('start'))) {
            return $pagelog->skip($request->get('start'))->take($limit)->get();
        }
        $pagelog = $pagelog->paginate($limit)->appends(request()->query());
        return $pagelog;
    }

    public static function get_log_count($request)
    {
        $pagelog = new self();
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 50);
        if (!empty($request->get('search'))) {
            $pagelog = $pagelog->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('description', 'LIKE', "%{$search}%");
            });
        }
        if (!empty($request->get('type'))) {
            $pagelog = $pagelog->where(function ($query) use ($request) {
                $query->where('type', $request->get('type'));
            });
        }

        $pagelog = $pagelog->count();
        return $pagelog;
    }

    public static function latestactivitilog()
    {
        $pagelog = Activities::orderBy('id', 'DESC')->limit(10)->get();
        if ($pagelog->count() > 0) {
            foreach ($pagelog as $log) {
                $url = '';
                $replace_text = '';
                if(!empty($log->user->name)){
                    if($log->user->user_type_id == 1){
                        $url = admin_url() . '/employer?search=' . $log->user->email;
                    }elseif($log->user->user_type_id == 5){
                        $url = admin_url() . '/affiliates?search=' . $log->user->email;
                    }else{
                        $url = admin_url() . '/job-seekers?search=' . $log->user->email;
                    }
                    
                    $replace_text = '<a href="'.$url.'" target="_blank">'.$log->user->name.'</a>';
                    $description = str_replace("{{company_name}}", $replace_text, $log->description);
                }else{
                    $description = $log->description;
                }
                $log->description = $description;
                $log->created_at = Carbon::parse($log->created_at)->format('Y-m-d H:i:s');
            }
        }
        return $pagelog;
    }

    public static function get_company_log($request)
    {
        $pagelog = new self();
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        if (!empty($request->get('search_new'))) {
            $pagelog = $pagelog->where(function ($query) use ($request) {
                $search = $request->get('search_new');
                $query->where('description', 'LIKE', "%{$search}%");
            });
        }
        $pagelog = $pagelog->where('user_id', auth()->user()->id)->orderBy('id', 'DESC');

        if (!empty($request->get('start'))) {
            return $pagelog->skip($request->get('start'))->take($limit)->get();
        }
        $pagelog = $pagelog->paginate($limit)->appends(request()->query());
        return $pagelog;
    }

    public static function get_company_log_count($request)
    {
        $pagelog = new self();
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 50);
        if (!empty($request->get('search_new'))) {
            $pagelog = $pagelog->where(function ($query) use ($request) {
                $search = $request->get('search_new');
                $query->where('description', 'LIKE', "%{$search}%");
            });
        }

        $pagelog = $pagelog->where('user_id', auth()->user()->id)->count();
        return $pagelog;
    }
}
