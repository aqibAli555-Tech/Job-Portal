<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvUploadLogs extends Model
{
    use HasFactory;

    protected $table = 'cv_upload_logs';
    protected $fillable = ['total_cv', 'approved_no_contact_cv', 'rejected_no_contact_Cv', 'pending_no_contact_cv', 'pending_approved', 'inprocess'];


}
