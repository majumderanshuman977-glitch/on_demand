<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItems extends Model
{
    use HasFactory;

    protected $fillable = ['subtotal', 'qty', 'price', 'name', 'services_id', 'booking_id'];
}
