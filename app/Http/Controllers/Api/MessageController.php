<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function getConversations()
    {
        $user = Auth::user();

        // Get accepted bookings where user is involved
        $acceptedBookings = Booking::where('status', 'accepted')
            ->where(function ($q) use ($user) {
                $q->where('provider_id', $user->id)
                    ->orWhere('user_id', $user->id);
            })
            ->pluck('id');

        // Fetch messages for those bookings
        $messages = Messages::whereIn('booking_id', $acceptedBookings)
            ->orderBy('created_at', 'asc')
            ->get(['booking_id as job_id', 'sender_id', 'receiver_id', 'message as text', 'created_at']);

        // Group messages by booking/job
        $conversations = $messages->groupBy('job_id')->map(function ($msgs, $jobId) {
            return [
                'job_id' => $jobId,
                'messages' => $msgs->values(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);
    }

    /**
     * Get messages for a specific booking/job
     */
    // public function getMessages($bookingId)
    // {
    //     $user = Auth::user();

    //     // Ensure the booking is accepted and the user is part of it
    //     $booking = Booking::where('id', $bookingId)
    //         ->where('status', 'accepted')
    //         ->where(function ($q) use ($user) {
    //             $q->where('provider_id', $user->id)
    //                 ->orWhere('user_id', $user->id);
    //         })
    //         ->firstOrFail();

    //     $messages = Messages::where('booking_id', $bookingId)
    //         ->orderBy('created_at', 'asc')
    //         ->get(['sender_id', 'receiver_id', 'message as text', 'created_at']);

    //     return response()->json([
    //         'success' => true,
    //         'job_id' => $bookingId,
    //         'messages' => $messages
    //     ]);
    // }


    public function getMessages($bookingId)
    {
        $messages = Messages::where('booking_id', $bookingId)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->map(function ($msg) {

                // Load sender to detect role
                $sender = User::find($msg->sender_id);

                return [
                    'id' => $msg->id,
                    'text' => $msg->message,
                    'side' => $sender && $sender->role === 'provider' ? 'provider' : 'user',
                    'sender_id' => $msg->sender_id,
                    'receiver_id' => $msg->receiver_id,
                    'time' => $msg->created_at->format('h:i A'),
                    'date' => $msg->created_at->format('d-m-Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'booking_id' => $bookingId,
            'messages' => $messages
        ]);
    }



    /**
     * Send a message in a booking/job
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        // Ensure the booking is accepted and user is part of it
        $booking = Booking::where('id', $request->booking_id)
            ->where('status', 'accepted')
            ->where(function ($q) use ($user) {
                $q->where('provider_id', $user->id)
                    ->orWhere('user_id', $user->id);
            })
            ->firstOrFail();

        $message = Messages::create([
            'booking_id' => $request->booking_id,
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ]);
    }
}
