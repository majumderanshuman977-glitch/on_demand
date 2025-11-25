<?php

namespace App\Models;

use App\Models\Services;
use App\Models\CostEstimationItems;
use App\Models\ServicePartsCategories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceParts extends Model
{
    use HasFactory;

    protected $table = 'service_parts';
    protected $fillable = ['category_id', 'part_name', 'base_cost', 'description'];


    public function category()
    {
        return $this->belongsTo(ServicePartsCategories::class, 'category_id');
    }

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function estimationItems()
    {
        return $this->hasMany(CostEstimationItems::class, 'service_parts_id');
    }
}
