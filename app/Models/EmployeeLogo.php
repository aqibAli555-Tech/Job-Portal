<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLogo extends Model
{
    use HasFactory;

    protected $table = 'employee_logo';

    protected $fillable = ['id', 'logo', 'user_id'];

    public static function get_comapny_logo($id)
    {
        return EmployeeLogo::where('user_id', $id)->get()->all();
    }
}
