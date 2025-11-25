<?php

namespace App\Models;

use App\Models\User;
use App\Models\Services;
use App\Models\UserAddress;
use App\Models\BookingItems;
use App\Models\CostEstimation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $fillable = ['user_id', 'provider_id', 'service_id', 'address_id', 'status', 'scheduled_date', 'scheduled_time', 'subtotal', 'tax', 'total_amount', 'notes'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }


    public function estimations()
    {
        return $this->hasMany(CostEstimation::class, 'booking_id');
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItems::class, 'booking_id');
    }
}
