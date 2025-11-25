<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountSettingsAccount extends Controller
{
  public function index()
  {

    if (Auth::check()) {
      $user = Auth::user();
    }
    return view('content.pages.pages-account-settings-account', compact('user'));
  }



  public function update(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $user->id,
      'phone' => 'nullable|string|max:20',
      'password' => 'nullable|string|min:8',
      'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
    ]);



    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;


    if ($request->filled('password')) {
      $user->password = Hash::make($request->password);
    }

    // Handle profile image upload
    if ($request->hasFile('profile_image')) {
      $path = $request->file('profile_image')->store('profile_images', 'public');
      $user->profile_image = $path;
    }

    $user->save();

    return back()->with('success', 'Profile updated successfully!');
  }
}
