<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\SubCategoryItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('content.pages.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('content.pages.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'nullable',
            'category_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;

        // Upload Image if provided
        if ($request->hasFile('category_image')) {
            $imageName = time() . '_' . $request->file('category_image')->getClientOriginalName();
            $path = $request->file('category_image')->storeAs('categories', $imageName, 'public');
            $category->category_image = $path;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }


    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('content.pages.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Validation
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
            'description' => 'nullable',
            'category_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        // Update fields
        $category->name = $request->name;
        $category->description = $request->description;

        // Handle new image upload
        if ($request->hasFile('category_image')) {

            // Delete old image if exists
            if ($category->category_image && Storage::disk('public')->exists($category->category_image)) {
                Storage::disk('public')->delete($category->category_image);
            }

            // Save new image
            $path = $request->file('category_image')->store('categories', 'public');
            $category->category_image = $path;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }


    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }


    public function addTypes($id)
    {
        $category = Category::findOrFail($id);

        // Group sub category items by TYPE
        $subItems = SubCategoryItem::where('category_id', $id)
            ->orderBy('type')
            ->get()
            ->groupBy('type');

        return view('content.pages.categories.types', compact('category', 'subItems'));
    }



    // public function saveTypes(Request $request, $categoryId)
    // {

    //     $request->validate([
    //         'types' => 'required|array',
    //         'types.*.name' => 'required|string|max:255',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         foreach ($request->types as $typeGroup) {
    //             $oldTypeName = $typeGroup['existing_type'] ?? null;
    //             $newTypeName = $typeGroup['name'];
    //             $typeImagePath = null;

    //             // Get existing type image if not uploading new one
    //             if ($oldTypeName) {
    //                 $existingItem = SubCategoryItem::where('category_id', $categoryId)
    //                     ->where('type', $oldTypeName)
    //                     ->first();
    //                 $typeImagePath = $existingItem?->category_type_image;
    //             }

    //             // Upload new type image if provided


    //             // RENAME TYPE (update all items with this type)
    //             if (!empty($oldTypeName) && $oldTypeName !== $newTypeName) {
    //                 SubCategoryItem::where('category_id', $categoryId)
    //                     ->where('type', $oldTypeName)
    //                     ->update([
    //                         'type' => $newTypeName,
    //                         'category_type_image' => $typeImagePath
    //                     ]);
    //             }

    //             // UPDATE TYPE IMAGE for all items of this type (if image was uploaded)
    //             if ($typeImagePath && !empty($typeGroup['image'])) {
    //                 SubCategoryItem::where('category_id', $categoryId)
    //                     ->where('type', $newTypeName)
    //                     ->update(['category_type_image' => $typeImagePath]);
    //             }

    //             // PROCESS ITEMS
    //             if (isset($typeGroup['items'])) {
    //                 foreach ($typeGroup['items'] as $itemData) {

    //                     // UPDATE EXISTING ITEM
    //                     if (!empty($itemData['existing_item_id'])) {
    //                         $item = SubCategoryItem::find($itemData['existing_item_id']);

    //                         if ($item) {
    //                             // Update item name
    //                             $item->item = $itemData['name'];

    //                             // Update type (in case type was renamed)
    //                             $item->type = $newTypeName;

    //                             // Update type image
    //                             if ($typeImagePath) {
    //                                 $item->category_type_image = $typeImagePath;
    //                             }

    //                             // Upload new item image if provided
    //                             if (!empty($itemData['image'])) {
    //                                 // Delete old item image
    //                                 if ($item->item_image && Storage::disk('public')->exists($item->item_image)) {
    //                                     Storage::disk('public')->delete($item->item_image);
    //                                 }
    //                                 $item->item_image = $itemData['image']->store('category_items', 'public');
    //                             }

    //                             $item->save();
    //                         }
    //                     }
    //                     // CREATE NEW ITEM
    //                     else {
    //                         $itemImage = null;

    //                         if (!empty($itemData['image'])) {
    //                             $itemImage = $itemData['image']->store('category_items', 'public');
    //                         }

    //                         SubCategoryItem::create([
    //                             'category_id'         => $categoryId,
    //                             'type'                => $newTypeName,
    //                             'category_type_image' => $typeImagePath,
    //                             'item'                => $itemData['name'],
    //                             'item_image'          => $itemImage,
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         return redirect()->back()
    //             ->with('success', 'Types & Items saved successfully!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return redirect()->back()
    //             ->with('error', 'Failed to save: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

    public function saveTypes(Request $request, $categoryId)
    {
        $request->validate([
            'types' => 'required|array',
            'types.*.name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->types as $typeGroup) {
                $oldTypeName = $typeGroup['existing_type'] ?? null;
                $newTypeName = $typeGroup['name'];


                // Handle new type image


                // Rename type if needed
                if ($oldTypeName && $oldTypeName !== $newTypeName) {
                    SubCategoryItem::where('category_id', $categoryId)
                        ->where('type', $oldTypeName)
                        ->update([
                            'type' => $newTypeName,
                        ]);
                }

                // Process items
                $items = $typeGroup['items'] ?? [];
                foreach ($items as $itemData) {
                    // Existing item
                    if (!empty($itemData['existing_item_id'])) {
                        $item = SubCategoryItem::find($itemData['existing_item_id']);
                        if ($item) {
                            $item->item = $itemData['name'];
                            $item->type = $newTypeName;

                            // Upload new image
                            if (!empty($itemData['image']) && $itemData['image'] instanceof \Illuminate\Http\UploadedFile) {
                                if ($item->item_image && Storage::disk('public')->exists($item->item_image)) {
                                    Storage::disk('public')->delete($item->item_image);
                                }
                                $item->item_image = $itemData['image']->store('category_items', 'public');
                            }

                            $item->save();
                        }
                    }
                    // New item
                    else {
                        $itemImage = null;
                        if (!empty($itemData['image']) && $itemData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $itemImage = $itemData['image']->store('category_items', 'public');
                        }

                        SubCategoryItem::create([
                            'category_id' => $categoryId,
                            'type' => $newTypeName,
                            'item' => $itemData['name'],
                            'item_image' => $itemImage,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Types & Items saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save: ' . $e->getMessage())->withInput();
        }
    }



    public function showSubCategories($id)
    {
        $category = Category::findOrFail($id);

        // Fetch all rows of the subcategory items for this category
        $subItems = SubCategoryItem::where('category_id', $id)
            ->orderBy('type')
            ->get()
            ->groupBy('type');

        return view('content.pages.categories.subcategories', compact('category', 'subItems'));
    }




    public function deleteType(Request $request, $categoryId)
    {
        $request->validate([
            'type' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            $items = SubCategoryItem::where('category_id', $categoryId)
                ->where('type', $request->type)
                ->get();


            foreach ($items as $item) {
                if ($item->item_image && Storage::disk('public')->exists($item->item_image)) {
                    Storage::disk('public')->delete($item->item_image);
                }
                if ($item->category_type_image && Storage::disk('public')->exists($item->category_type_image)) {
                    Storage::disk('public')->delete($item->category_type_image);
                }
            }

            // Delete all items of this type
            SubCategoryItem::where('category_id', $categoryId)
                ->where('type', $request->type)
                ->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Update your existing deleteItem method to include this
    public function deleteItem($id)
    {
        DB::beginTransaction();

        try {
            $item = SubCategoryItem::findOrFail($id);

            // Delete item image if exists
            if ($item->item_image && Storage::disk('public')->exists($item->item_image)) {
                Storage::disk('public')->delete($item->item_image);
            }

            $item->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
