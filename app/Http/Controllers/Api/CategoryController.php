<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {

            $perPage = $request->input('per_page', 10);

            $categories = Category::paginate($perPage);
            $categories->getCollection()->transform(function ($category) {
            $category->image_url = $category->category_image
                ? asset('storage/' . $category->category_image)
                : null;
            return $category;
        });

            return response()->json([
                'success' => true,
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
