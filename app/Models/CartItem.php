<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\ServiceParts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    protected $fillable = ['subtotal', 'price', 'name', 'services_id', 'cart_id', 'qty'];

    public function servicePart()
    {
        return $this->belongsTo(ServiceParts::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
