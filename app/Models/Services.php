<?php

namespace App\Models;

use App\Models\SubCategoryItem;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{

    protected $fillable = [
        'title',
        'sub_category_item_id',
        'description',
        'price',
        'offer_price',
        'duration',
        'includes',
        'services',
        'services_image'
    ];
    protected $casts = [
        'includes' => 'array',
    ];

    public function subCategoryItem()
    {
        return $this->belongsTo(SubCategoryItem::class);
    }
}
