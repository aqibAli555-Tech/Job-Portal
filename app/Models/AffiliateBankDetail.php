<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateBankDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'affiliate_bank_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'bank_name', 'swift_code', 'address', 'beneficiary_name', 'IBAN', 'country_code', 'city', 'state', 'bank_address', 'email', 'service_type'];
}
