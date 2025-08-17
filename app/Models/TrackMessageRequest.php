<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackMessageRequest extends Model
{
    protected $table = 'track_message_request';
    protected $fillable = ['user_id', 'to_user_id', 'post_id'];
    use HasFactory;

    public static function get_track_request_by_request_id($id)
    {
        $track_request = TrackMessageRequest::select("track_message_request.*", "posts.title as title", "users.name as name", "messages_requests.message as message")
            ->join("posts", "track_message_request.post_id", "=", "posts.id")
            ->join("users", "track_message_request.user_id", "=", "users.id")
            ->join("messages_requests", "track_message_request.request_id", "=", "messages_requests.id")
            ->with('user') // Include the 'user' relationship
            ->where("track_message_request.request_id", $id)
            ->orderBy('track_message_request.id', 'DESC')
            ->paginate(15);

        return $track_request;

    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
