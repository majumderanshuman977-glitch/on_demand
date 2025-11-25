<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceParts;
use App\Models\ServicePartsCategories;
use Illuminate\Http\Request;

class ServicePartsController extends Controller
{
    public function index()
    {
        $categories = ServicePartsCategories::orderBy('id', 'DESC')->get();
        return view('content.pages.service_parts_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('content.pages.service_parts_categories.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ServicePartsCategories::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.servicePartsCategories.index')
            ->with('success', 'Service parts category created successfully!');
    }

    public function edit($id)
    {
        $category = ServicePartsCategories::findOrFail($id);
        return view('content.pages.service_parts_categories.edit', compact('category'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = ServicePartsCategories::findOrFail($id);

        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.servicePartsCategories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = ServicePartsCategories::findOrFail($id);
        $category->delete();

        return redirect()
            ->route('admin.servicePartsCategories.index')
            ->with('success', 'Category deleted successfully!');
    }




    public function service_index()
    {
        $parts = ServiceParts::with('category')->latest()->get();
        return view('content.pages.service_parts.index', compact('parts'));
    }

    // Show Add Form
    public function service_create()
    {
        $categories = ServicePartsCategories::all();
        return view('content.pages.service_parts.create', compact('categories'));
    }

    // Store New
    public function service_store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:service_parts_categories,id',
            'part_name' => 'required|string|max:255',
            'base_cost' => 'nullable|numeric',
        ]);

        ServiceParts::create($request->all());

        return redirect()->route('admin.serviceParts.index')
            ->with('success', 'Service Part created successfully.');
    }

    // Edit Form
    public function service_edit($id)
    {
        $part = ServiceParts::findOrFail($id);
        $categories = ServicePartsCategories::all();

        return view('content.pages.service_parts.edit', compact('part', 'categories'));
    }

    // Update
    public function service_update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:service_parts_categories,id',
            'part_name' => 'required',
            'base_cost' => 'nullable|numeric',
        ]);

        $part = ServiceParts::findOrFail($id);
        $part->update($request->all());

        return redirect()->route('admin.serviceParts.index')
            ->with('success', 'Service Part updated successfully.');
    }

    // Delete
    public function service_destroy($id)
    {
        $part = ServiceParts::findOrFail($id);
        $part->delete();

        return redirect()->route('admin.serviceParts.index')
            ->with('success', 'Service Part deleted successfully.');
    }
}
