<?php

namespace App\Models;

use App\Models\ServiceParts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicePartsCategories extends Model
{
    use HasFactory;

    protected $table = "service_parts_categories";
    protected $fillable = ['name', 'description'];


    public function serviceParts()
    {
        return $this->hasMany(ServiceParts::class, 'category_id');
    }
}
