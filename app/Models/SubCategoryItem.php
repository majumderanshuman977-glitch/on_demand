<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategoryItem extends Model
{

    protected $table = "sub_category_items";

    protected $fillable = ['id', 'category_id', 'type', 'item', 'item_image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
