<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubCategoryItem;
use Illuminate\Http\Request;

class SubCategoryItemController extends Controller
{


   public function index(Request $request)
{
    try {
        $perPage = $request->input('per_page', 10);

        $sub_categories_items = SubCategoryItem::with(['category'])->paginate($perPage);

        // Transform collection to add full URL for item_image
        $sub_categories_items->getCollection()->transform(function ($sub_categories_item) {
            $sub_categories_item->item_image_url = $sub_categories_item->item_image
                ? asset('storage/' . $sub_categories_item->item_image)
                : null;

            return $sub_categories_item;
        });

        return response()->json([
            'success' => true,
            'data' => $sub_categories_items,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

}
