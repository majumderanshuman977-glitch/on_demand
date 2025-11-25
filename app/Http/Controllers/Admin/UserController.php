<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{



    public function index()
    {
        $customers = User::with(['UserAddress'])->where('role', 'user')->paginate(20);
        $providers = User::with(['UserAddress', 'categories'])->where('role', 'provider')->paginate(20);

        return view('content.pages.users.user-list', compact('customers', 'providers'));
    }




    public function show($id)
    {
        $user = User::with(['UserAddress', 'categories', 'userBookings', 'providerBookings'])->findOrFail($id);

        return view('content.pages.users.user-show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);


        return view('content.pages.users.user-edit', compact('user'));
    }


    // public function update(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);

    //     $request->validate([
    //         'name'         => 'required|string|max:255',
    //         'email'        => 'nullable|email',
    //         'phone'        => 'required',
    //         'gender'       => 'required|in:male,female',
    //         'is_verified'  => 'required|boolean',
    //         'profile_image' => 'nullable|image|mimes:jpg,jpeg,png',
    //     ]);


    //     $user->name        = $request->name;
    //     $user->email       = $request->email;
    //     $user->phone       = $request->phone;
    //     $user->gender      = $request->gender;
    //     $user->is_verified = $request->is_verified;


    //     if ($request->hasFile('profile_image')) {
    //         if ($user->profile_image && file_exists(storage_path('app/public/' . $user->profile_image))) {
    //             unlink(storage_path('app/public/' . $user->profile_image));
    //         }

    //         $user->profile_image = $request->file('profile_image')->store('profile_images', 'public');
    //     }

    //     $user->save();

    //     return redirect()->route('admin.users.edit', $user->id)
    //         ->with('success', 'User updated successfully!');
    // }
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $category->name = $request->name;
        $category->description = $request->description;

        if ($request->hasFile('image')) {

            if ($category->image && Storage::exists('public/category_images/' . $category->image)) {
                Storage::delete('public/category_images/' . $category->image);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/category_images', $filename);

            $category->image = $filename;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }
}
