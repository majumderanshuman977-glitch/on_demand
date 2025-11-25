<?php

namespace App\Models;

use App\Models\User;
use App\Models\Booking;
use App\Models\CostEstimationItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CostEstimation extends Model
{
    use HasFactory;

    protected $table = "cost_estimations";
    protected $fillable = ['booking_id', 'provider_id', 'total_amount', 'status'];


    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function items()
    {
        return $this->hasMany(CostEstimationItems::class, 'cost_estimation_id');
    }
}
