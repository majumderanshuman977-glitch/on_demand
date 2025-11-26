<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'message' => 'Profile fetched successfully.',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'profile_image' => $user->profile_image
                        ? asset('storage/' . $user->profile_image)
                        : null,
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
            ], 500);
        }
    }


    // public function updateImage(Request $request)
    // {
    //     try {
    //         $user = $request->user();


    //         $validator = Validator::make($request->all(), [
    //             'profile_image' => 'required|image|mimes:jpeg,png,jpg,webp',
    //             'type' => 'required'
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => 0,
    //                 'errors' => $validator->errors(),
    //             ], 422);
    //         }


    //         if ($request->hasFile('profile_image')) {

    //             $image = $request->file('profile_image');


    //             $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();


    //             Storage::disk('public')->putFileAs('profile', $image, $filename);


    //             if ($user->profile_image && Storage::disk('public')->exists('profile/' . $user->profile_image)) {
    //                 Storage::disk('public')->delete('profile/' . $user->profile_image);
    //             }

    //             // Save new filename
    //             $user->profile_image = $filename;
    //             $user->save();

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Profile image updated successfully.',
    //                 'profile_image' => asset('storage/profile/' . $filename),
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No image file provided.',
    //             ], 400);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to update profile image.',
    //             'error' => env('APP_DEBUG') ? $e->getMessage() : null,
    //         ], 500);
    //     }
    // }


    public function updateImage(Request $request)
    {
        try {
            // $user = $request->user();
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,webp',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'errors' => $validator->errors(),
                ], 422);
            }

            if ($request->hasFile('profile_image')) {

                $image = $request->file('profile_image');
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('profile', $filename, 'public');

                // Delete old image if exists
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                // Save full path
                $user->profile_image = $path;
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Profile image updated successfully.',
                    'profile_image' => asset('storage/' . $path),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image file provided.',
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile image.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
