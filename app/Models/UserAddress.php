<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = "user_addresses";
    protected $fillable = [
        'user_id',
        'apartment_number',
        'street_address',
        'pin_code',
        'state',
        'city',
        'country',
        'contact_phone_number',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
