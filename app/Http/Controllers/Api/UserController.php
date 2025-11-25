<?php

namespace App\Http\Controllers\Api;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // Check role
            if ($user->role !== 'user') {
                return response()->json([
                    'status' => 0,
                    'message' => 'Only users can add addresses.',
                ], 403);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'apartment_number' => 'nullable|string|max:255',
                'street_address'   => 'required|string|max:255',
                'pin_code'         => 'required|string|max:20',
                'state'            => 'required|string|max:100',
                'city'             => 'required|string|max:100',
                'country'          => 'required|string|max:100',
                'contact_phone_number' => 'nullable|string|max:20',
                'is_active'        => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Insert address (exclude latitude & longitude)
            $address = UserAddress::create([
                'user_id' => $user->id,
                'apartment_number' => $request->apartment_number,
                'street_address' => $request->street_address,
                'pin_code' => $request->pin_code,
                'state' => $request->state,
                'city' => $request->city,
                'country' => $request->country,
                'contact_phone_number' => $request->contact_phone_number,
                'is_active' => $request->is_active ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully.',
                'data' => $address,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
