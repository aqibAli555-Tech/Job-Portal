<?php

namespace App\Models;

use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Larapen\Admin\app\Models\Traits\Crud;

class ThreadParticipant extends BaseModel
{
    use SoftDeletes, Crud, Notifiable, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'threads_participants';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    protected $appends = ['created_at_formatted'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    // public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'thread_id',
        'user_id',
        'last_read',
        'is_important',
    ];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function get_all_threads_login_user()
    {
        return self::where('user_id', auth()->user()->id)->pluck('thread_id')->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function get_all_threads_without_login_user($id)
    {
        return self::where('user_id', $id)->pluck('thread_id')->toArray();
    }

    protected static function boot()
    {
        parent::boot();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Thread relationship.
     *
     * @return BelongsTo
     *
     * @codeCoverageIgnore
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * User relationship.
     *
     * @return BelongsTo
     *
     * @codeCoverageIgnore
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCreatedAtFormattedAttribute($value)
    {
        $value = new Carbon($this->attributes['created_at']);
        $value->timezone(Date::getAppTimeZone());

        $value = Date::formatFormNow($value);

        return $value;
    }

}
