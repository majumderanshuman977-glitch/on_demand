<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';
    protected $fillable = [
        'user_id',
        'wallet_id',
        'previous_amount',
        'amount',
        'description',
        'status'
    ];
}
