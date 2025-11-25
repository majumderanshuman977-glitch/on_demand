<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderReview extends Model
{
    use HasFactory;

    protected $table = 'provider_reviews';
    protected $fillable = ['user_id', 'provider_id', 'booking_id', 'rating', 'comment'];
}
