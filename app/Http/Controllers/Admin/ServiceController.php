<?php

namespace App\Http\Controllers\Admin;

use App\Models\Services;
use Illuminate\Http\Request;
use App\Models\SubCategoryItem;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Services::with('subCategoryItem')->get();
        return view('content.pages.services.index', compact('services'));
    }

    public function create()
    {
        $items = SubCategoryItem::with('category')->get();

        return view('content.pages.services.create', compact('items'));
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_category_item_id' => 'required|exists:sub_category_items,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'offer_price' => 'required|numeric',
            'duration' => 'required|numeric',
            'includes' => 'nullable|array',
            'includes.*' => 'string',
            'services_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        // Initialize data array
        $data = [
            'title' => $request->title,
            'sub_category_item_id' => $request->sub_category_item_id,
            'description' => $request->description,
            'price' => $request->price,
            'offer_price' => $request->offer_price,
            'duration' => $request->duration,
            'includes' => $request->includes,
        ];

        // Handle image upload
        if ($request->hasFile('services_image')) {
            $image = $request->file('services_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('services'), $imageName);
            $data['services_image'] = 'services/' . $imageName;
        }

        // Create the service
        Services::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Service added successfully!');
    }


    public function edit($id)
    {
        $service = Services::findOrFail($id);
        $items = SubCategoryItem::with('category')->get();

        return view('content.pages.services.edit', compact('service', 'items'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'sub_category_item_id' => 'required|exists:sub_category_items,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'offer_price' => 'required|numeric',
            'duration' => 'required|numeric',
            'includes' => 'nullable|array',
            'includes.*' => 'string',
        ]);

        $service = Services::findOrFail($id);

        $service->update([
            'title' => $request->title,
            'sub_category_item_id' => $request->sub_category_item_id,
            'description' => $request->description,
            'price' => $request->price,
            'offer_price' => $request->offer_price,
            'duration' => $request->duration,
            'includes' => $request->includes,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service updated!');
    }

    public function destroy($id)
    {
        Services::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Service deleted!');
    }
}
