<?php

namespace App\Models;

use App\Helpers\Files\Storage\StorageDisk;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\LocalizedScope;
use App\Observers\ResumeObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Larapen\Admin\app\Models\Traits\Crud;
use Request;

class Resume extends BaseModel
{
    use Crud, HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resumes';
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
    protected $fillable = ['country_code', 'user_id', 'city_id', 'name', 'file', 'picture', 'pdf', 'videoLink', 'active', 'skill', 'experience', 'fullName', 'about', 'fatherName', 'birthDate', 'cnic', 'email', 'phone', 'interest', 'gender', 'address', 'seDescription', 'seCountry', 'seCity', 'seCompany', 'university', 'degree', 'ed_startDate', 'ed_endDate', 'company', 'views', 'cExperience', 'hobby', 'hDescription', 'facebook', 'twitter', 'instagram', 'tiktok', 'linkedin', 'employee_skill', 'sestartdate', 'seenddate', 'nationality'];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'deleted_at', 'archived_at', 'deletion_mail_sent_at', 'deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function resume_by_user_id($user_id)
    {
        return self::where('user_id', $user_id)->with('User')->first();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        Resume::observe(ResumeObserver::class);

        static::addGlobalScope(new ActiveScope());
        static::addGlobalScope(new LocalizedScope());
    }

    public function post()
    {
        return $this->hasMany(Post::class);
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

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_id', 'id');
    }

    public function getNameAttribute()
    {
        $value = null;

        if (isset($this->attributes) && isset($this->attributes['name'])) {
            $value = $this->attributes['name'];
        }

        if (empty($value)) {
            $value = last(explode('/', $this->attributes['filename']));
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function getFilenameAttribute()
    {
        if (!isset($this->attributes) || !isset($this->attributes['filename'])) {
            return null;
        }

        $value = $this->attributes['filename'];

        // Fix path
        $value = str_replace('uploads/resumes/', '', $value);
        $value = str_replace('resumes/', '', $value);
        $value = 'resumes/' . $value;

        $disk = StorageDisk::getDisk();

        if (!$disk->exists($value)) {
            return null;
        }

        return $value;
    }

    public function setFilenameAttribute($value)
    {
        $diskName = StorageDisk::getDiskName();
        $field_name = 'resume.filename';
        $attribute_name = 'filename';

        // Set the right field name
        $request = Request::instance();
        if (!$request->hasFile($field_name)) {
            $field_name = $attribute_name;
        }

        if (!isset($this->country_code) || !isset($this->user_id)) {
            $this->attributes[$attribute_name] = null;
            return false;
        }

        // Path
        $destination_path = 'resumes/' . strtolower($this->country_code) . '/' . $this->user_id;

        // Upload
        $this->uploadFileToDiskCustom($value, $field_name, $attribute_name, $diskName, $destination_path);
    }

    public function City()
    {
        return $this->belongsTo(City::class);
    }
}
