<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactCardProblems extends Model
{
    use HasFactory;

    protected $table = 'contact_card_problems';
    protected $fillable = ['id', 'name', 'company', 'created_at', 'updated_at'];

    public static function latestcontactcardproblems()
    {
        return ContactCardProblems::orderBy('id', 'DESC')->limit(10)->get();
    }
}