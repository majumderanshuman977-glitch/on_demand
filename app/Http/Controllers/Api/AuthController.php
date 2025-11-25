<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string',
            'email' => 'nullable|email',
            'gender'        => 'required|in:male,female',
            'role'          => 'required|in:user,provider',
            'kyc_document'  => 'nullable|file|mimes:pdf,jpg,png,jpeg',
            'category_id'      => 'nullable|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $kycPath = null;
        if ($request->hasFile('kyc_document')) {
            $file = $request->file('kyc_document');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $kycPath = $file->storeAs('kyc_documents', $filename, 'public');
        }

        // Set is_verified based on role
        $isVerified = $request->role === 'user' ? 1 : 0;
        if ($request->role === 'provider') {
            $category_id = $request->category_id;
        }


        $user = User::updateOrCreate(
            [
                'phone' => $request->phone,
                'role'  => $request->role
            ],
            [
                'name'          => $request->name,
                'phone'         => $request->phone,
                'email' => $request->email,
                'gender'        => $request->gender,
                'role'          => $request->role,
                'is_verified'   => $isVerified,
                'kyc_document'  => $kycPath,
                'category_id' => $category_id ?? null
            ]
        );


        // $token = $user->createToken('OnDemand')->accessToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => [
                'user'  => $user,
                // 'token' => $token,
            ]
        ]);
    }


    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'type'  => 'required|in:user,provider',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Check if user with this phone and type already exists
            $user = User::where('phone', $request->phone)
                ->where('role', $request->type)
                ->first();

            if (!$user) {
                // Create new user only if phone + role not exists
                $user = User::create([
                    'phone' => $request->phone,
                    'role'  => $request->type,
                ]);
            }

            // Generate OTP
            $otp_code = rand(100000, 999999);

            Otp::create([
                'user_id'    => $user->id,
                'otp'        => $otp_code,
                'expires_at' => now()->addMinutes(2),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully.',
                'otp'     => env('APP_DEBUG') ? $otp_code : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while sending OTP.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
            ], 500);
        }
    }




    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'otp'   => 'required|digits:6',
                'type'  => 'required|in:user,provider',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }


            $user = User::where('phone', $request->phone)
                ->where('role', $request->type)
                ->first();



            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }


            $otp = Otp::where('user_id', $user->id)
                ->where('otp', $request->otp)
                ->where('expires_at', '>=', now())
                ->latest()
                ->first();

            if (!$otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired OTP.',
                ], 400);
            }


            $otp->delete();

            if ($user->role === 'user') {
                $user->is_verified = true;
            }

            $user->save();


            $token = $user->createToken('OnDemand')->accessToken;


            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully.',
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during OTP verification.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function serviceLocation(Request $request)
    {
        try {
            $user = Auth::user();

            // Check if user is a provider
            if ($user->role !== 'provider') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only providers can add a service location.'
                ], 403);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'service_area'     => 'nullable|string|max:255',
                'service_location' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Save service location directly in users table
            $user->update([
                'service_location' => $request->service_location,
                'service_area'     => $request->service_area ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Service location saved successfully',
                'data'    => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user) {
                // Revoke the token that was used to authenticate the current request
                $request->user()->token()->revoke();

                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during logout',
                'error'   => env('APP_DEBUG') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
