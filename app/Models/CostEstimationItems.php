<?php

namespace App\Models;

use App\Models\ServiceParts;
use App\Models\CostEstimation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CostEstimationItems extends Model
{
    use HasFactory;

    protected $table = 'cost_estimation_items';
    protected $fillable = ['cost_estimation_id', 'service_part_id', 'part_name', 'base_price', 'provider_price', 'qty', 'subtotal'];




    public function servicePart()
    {
        return $this->belongsTo(ServiceParts::class, 'service_part_id');
    }

    public function costEstimation()
    {
        return $this->hasOne(CostEstimation::class);
    }



    public function costEstimations()
    {
        return $this->hasMany(CostEstimation::class, 'provider_id');
    }
}
