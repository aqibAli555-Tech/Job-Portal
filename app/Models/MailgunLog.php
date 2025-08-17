<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class MailgunLog extends Model
{
    use HasFactory;

    protected $table = 'mailgun_log';
    protected $fillable = ['email_id', 'statuscode', 'message', 'request','mailgun_email_id'];

}
