<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Booking;
use App\Models\ServiceParts;
use Illuminate\Http\Request;
use App\Models\CostEstimation;
use Illuminate\Support\Facades\DB;
use App\Models\CostEstimationItems;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CostEstimationController extends Controller
{


    public function getServiceParts()
    {
        try {

            $parts = ServiceParts::select('id', 'part_name', 'base_cost', 'category_id', 'description')
                ->get()
                ->groupBy('category_id')
                ->map(function ($group) {
                    return [
                        'category_id' => $group->first()->category_id,
                        'category_name' => $group->first()->category->name ?? null,
                        'items' => $group->values()
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'parts' => $parts
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'errors' => $e->getMessage()], 422);
        }
    }

    // public function costEstimations(Request $request)
    // {
    //     $provider = Auth::user();
    //     $request->validate([
    //         'booking_id' => 'required|exists:bookings,id',
    //         'items' => 'required|array|min:1',
    //         'items.*.service_part_id' => 'nullable|exists:service_parts,id',
    //         'items.*.part_name' => 'required|string|max:255',
    //         'items.*.base_price' => 'nullable|numeric|min:0',
    //         'items.*.provider_price' => 'nullable|numeric|min:0',
    //         'items.*.qty' => 'required|integer|min:1',
    //     ]);

    //     // Check if booking belongs to provider
    //     $booking = Booking::where('id', $request->booking_id)
    //         ->where('provider_id', $provider->id)
    //         ->firstOrFail();

    //     DB::beginTransaction();
    //     try {
    //         $totalAmount = 0;

    //         // Calculate total
    //         foreach ($request->items as $item) {
    //             $subtotal = $item['provider_price'] * $item['qty'];
    //             $totalAmount += $subtotal;
    //         }

    //         // Create cost estimation
    //         $estimation = CostEstimation::create([
    //             'booking_id' => $request->booking_id,
    //             'provider_id' => auth()->id(),
    //             'total_amount' => $totalAmount,
    //             'status' => 'sent',
    //         ]);


    //         foreach ($request->items as $item) {

    //             // If provider_price is empty, null, or zero → use base_price
    //             $providerPrice = !empty($item['provider_price']) && $item['provider_price'] != 0
    //                 ? $item['provider_price']
    //                 : $item['base_price'];

    //             $subtotal = $providerPrice * $item['qty'];

    //             CostEstimationItems::create([
    //                 'cost_estimation_id' => $estimation->id,
    //                 'service_part_id'    => $item['service_part_id'] ?? null,
    //                 'part_name'          => $item['part_name'],
    //                 'base_price'         => $item['base_price'],
    //                 'provider_price'     => $providerPrice,
    //                 'qty'                => $item['qty'],
    //                 'subtotal'           => $subtotal,
    //             ]);
    //         }

    //         DB::commit();

    //         $estimation->load('items');

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Cost estimation created successfully',
    //             'data' => $estimation
    //         ], 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to create cost estimation',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function costEstimations(Request $request)
    {
        $provider = Auth::user();

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'items' => 'required|array|min:1',
            'items.*.service_part_id' => 'nullable|exists:service_parts,id',
            'items.*.part_name' => 'required|string|max:255',
            'items.*.base_price' => 'nullable|numeric|min:0',
            'items.*.provider_price' => 'nullable|numeric|min:0',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        // Check if booking belongs to provider
        $booking = Booking::where('id', $request->booking_id)
            ->where('provider_id', $provider->id)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $totalAmount = 0;

            // Correct total calculation
            foreach ($request->items as $item) {

                // FIX: use same logic for price selection
                $providerPrice = !empty($item['provider_price']) && $item['provider_price'] != 0
                    ? $item['provider_price']
                    : $item['base_price'];

                // FIX: correct subtotal calculation
                $subtotal = $providerPrice * $item['qty'];

                $totalAmount += $subtotal;
            }

            // Create cost estimation
            $estimation = CostEstimation::create([
                'booking_id' => $request->booking_id,
                'provider_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'status' => 'sent',
            ]);

            // Create items
            foreach ($request->items as $item) {

                $providerPrice = !empty($item['provider_price']) && $item['provider_price'] != 0
                    ? $item['provider_price']
                    : $item['base_price'];

                $subtotal = $providerPrice * $item['qty'];

                CostEstimationItems::create([
                    'cost_estimation_id' => $estimation->id,
                    'service_part_id'    => $item['service_part_id'] ?? null,
                    'part_name'          => $item['part_name'],
                    'base_price'         => $item['base_price'],
                    'provider_price'     => $providerPrice,
                    'qty'                => $item['qty'],
                    'subtotal'           => $subtotal,
                ]);
            }

            DB::commit();

            $estimation->load('items');

            return response()->json([
                'success' => true,
                'message' => 'Cost estimation created successfully',
                'data' => $estimation
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create cost estimation',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function updateStatus(Request $request)
    {

        try {
            $request->validate([
                'estimation_id' => 'required|exists:cost_estimations,id',
                'status'        => 'required|in:accepted,rejected',
            ]);

            $estimation = CostEstimation::findOrFail($request->estimation_id);

            $estimation->status = $request->status;
            $estimation->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $estimation
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update the status from user side',
                'error'  => $e->getMessage()
            ]);
        }
    }
}
