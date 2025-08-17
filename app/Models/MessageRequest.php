<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRequest extends Model
{
    protected $table = 'messages_requests';
    protected $fillable = ['user_id', 'status', 'number_of_employee', 'skill_set', 'message'];
    use HasFactory;

    public static function get_all_message_request($request)
    {
        $limit = !empty($request->get('limit')) ? $request->get('limit') : 15;
        $data = MessageRequest::with(['post', 'company']);
        return $data->orderBy('messages_requests.id', 'desc')->paginate($limit)->appends(request()->query());
    }

    public static function get_message_request_by_id($id)
    {
        $data = MessageRequest::select('messages_requests.*');
        return $data->where('messages_requests.id', $id)->first();
    }

    public static function get_all_message_request_by_employer_id()
    {
        $data = MessageRequest::select('messages_requests.*', 'posts.title as title');
        $data = $data->join('posts', 'messages_requests.post_id', '=', 'posts.id', 'left');
        $data = $data->where('messages_requests.user_id', auth()->user()->id);
        return $data->orderBy('messages_requests.created_at')->get();
    }

    public static function get_message_request_count()
    {
        return MessageRequest::where("user_id", auth()->user()->id)->count();
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'user_id', 'c_id');
    }
}