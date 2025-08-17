<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use phpDocumentor\Reflection\Types\Self_;

class UserCancelledPackages extends Model
{
    use HasFactory;

    protected $table = 'user_cancelled_packages';
    protected $fillable = ['user_id', 'package_id', 'cancel_reason_id'];

}
