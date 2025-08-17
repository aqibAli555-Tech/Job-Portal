<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $table = 'payment_setting';
    protected $fillable = ['Tap_enabled', 'Tap_mode', 'secret_key'];
    use HasFactory;
}
