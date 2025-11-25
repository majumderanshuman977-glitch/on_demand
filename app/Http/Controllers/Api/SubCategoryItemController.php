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
