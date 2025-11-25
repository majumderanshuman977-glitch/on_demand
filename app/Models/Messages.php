<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'booking_id'
    ];
}
