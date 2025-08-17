<?php

namespace App\Models;

use App\Applicant;
use App\Sub_private_note;
use Illuminate\Database\Eloquent\Model;

class Private_note extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'private_notes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['note', 'title', 'applicant_id', 'user_id'];

    protected $dates = ['deleted_at', 'deleted_at', 'archived_at', 'deletion_mail_sent_at', 'deleted_at'];

    public function Applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function Sub_private_note()
    {
        return $this->hasMany(Sub_private_note::class);
    }

}