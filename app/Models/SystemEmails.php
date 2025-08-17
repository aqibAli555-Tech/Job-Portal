<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemEmails extends Model
{
    use HasFactory;

    protected $table = 'system_emails';
    protected $fillable = ['name'];


    public static function get_all_emails()
    {
        return SystemEmails::get();
    }
}
