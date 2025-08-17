<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyPostArchivedReason extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'company_post_archived_reasons';
    protected $primaryKey = 'id';

    protected $fillable = [
        'post_id',
        'reason_id',
    ];

}