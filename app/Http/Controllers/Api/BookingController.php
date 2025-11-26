<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Cart;
use App\Models\Booking;
use App\Models\BookingItems;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{




    public function serviceSlot(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'cart_id' => 'required|exists:carts,id',
                'scheduled_date' => 'required|date',
                'scheduled_time' => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'], // HH:MM format
                'address_id' => 'required|exists:user_addresses,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $cart = Cart::with('items')->where('id', $request->cart_id)
                ->where('user_id', $user->id)->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty or not found'
                ], 404);
            }

            $bookings = [];

            DB::transaction(function () use ($cart, $user, $request, &$bookings) {
                $scheduled_at = $request->scheduled_date . ' ' . $request->scheduled_time . ':00';

                foreach ($cart->items as $item) {
                    $subtotal = $item->subtotal;
                    $tax = round($subtotal * 0.18, 2); // example 18% tax
                    $total = $subtotal + $tax;

                    $booking = Booking::create([
                        'user_id' => $user->id,
                        'provider_id' => null, // assign later
                        'service_id' => $item->services_id,
                        'address_id' => $request->address_id,
                        'scheduled_date' => Carbon::today()->format('Y-m-d'),
                        'scheduled_time' => $scheduled_at,
                        'subtotal' => $subtotal,
                        'tax' => $tax,
                        'total_amount' => $total,
                        'status' => 'pending',
                    ]);

                    BookingItems::create([
                        'booking_id' => $booking->id,
                        'services_id' => $item->services_id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'qty' => $item->qty,
                        'subtotal' => $item->subtotal,
                    ]);

                    $bookings[] = $booking;
                }

                // Optional: clear cart after booking
                $cart->items()->delete();
                $cart->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Service scheduled successfully',
                'data' => $bookings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function providerBookings(Request $request)
    {
        try {

            $bookings = Booking::where('status', 'pending')->get();

            if ($bookings->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending bookings found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Bookings fetched successfully',
                'data' => $bookings
            ], 200);


            return response()->json([
                'success' => true,
                'message' => 'Bookings fetched successfully',
                'data' => $bookings
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function changeBookingStatus(Request $request, $bookingId)
    {

        $provider = Auth::user();


        if ($provider->role !== 'provider') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking = Booking::findOrFail($bookingId);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:accepted,rejected,completed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $booking->status = $request->status;
        $booking->provider_id = $provider->id;
        $booking->save();

        return response()->json(['success' => true, 'message' => "Booking {$request->status}"]);
    }

    public function providerJobs(Request $request)
    {
        $provider = auth()->user();

        if ($provider->role != 'provider') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Current Jobs
        $currentJobs = Booking::where('provider_id', $provider->id)
            ->whereIn('status', [
                'accepted',
                'in_progress'
            ])
            ->with(['bookingItems', 'user', 'address'])
            ->orderBy('scheduled_date', 'asc')
            ->get();


        $completedJobs = Booking::where('provider_id', $provider->id)
            ->where('status', 'completed')
            ->with(['bookingItems', 'user', 'address'])
            ->orderBy('scheduled_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'current_jobs' => $currentJobs,
                'completed_jobs' => $completedJobs
            ]
        ]);
    }
}
