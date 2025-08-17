<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contact';

    protected $fillable = ['first_name', 'last_name', 'phone', 'email', 'user_type', 'message'];

    public static function latestContacts()
    {
        return Contact::orderBy('id', 'DESC')->limit(10)->get();
    }

}
