<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProviderReview;
use App\Models\UserReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function rateUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'rating' => 'required|numeric|min:1|max:5',
                'booking_id' => 'required|exists:bookings,id',
                'comment' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $provider = Auth::user();

            if ($provider->role !== 'provider') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            UserReview::create([
                'user_id' => $request->user_id,
                'provider_id' => $provider->id,
                'booking_id' => $request->booking_id,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            return response()->json(['success' => true, 'message' => 'Review submitted successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function rateProvider(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'provider_id' => 'required|exists:users,id',
                'rating' => 'required|numeric|min:1|max:5',
                'booking_id' => 'required|exists:bookings,id',
                'comment' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $user = Auth::user();

            if ($user->role !== 'user') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            ProviderReview::create([
                'user_id' => $user->id,
                'provider_id' => $request->provider_id,
                'booking_id' => $request->booking_id,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            return response()->json(['success' => true, 'message' => 'Review submitted successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
