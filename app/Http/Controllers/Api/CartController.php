<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\ServiceParts;
use App\Models\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    // public function addToCart(Request $request)
    // {
    //     try {

    //         $user = Auth::user();
    //         $validator = Validator::make($request->all(), [
    //             'service_part_id' => 'required|exists:service_parts,id',
    //             'qty' => 'required|integer|min:1'
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }

    public function addItem(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'services_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            // Load the service part
            $service = Services::findOrFail($request->services_id);

            $total_amount = (!empty($service->price) && $service->price > 0)
                ? $service->price
                : $service->offer_price;

            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['total_amount' => $total_amount]
            );

            // Check if item already in cart
            $item = CartItem::where('cart_id', $cart->id)
                ->where('services_id', $service->id)
                ->first();

            if ($item) {
                $item->qty += 1;
                $item->subtotal = $item->qty * $item->price;
                $item->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'services_id' => $service->id,
                    'name' => $service->title,
                    'price' => $total_amount,
                    'qty' => 1,
                    'subtotal' => $total_amount,
                ]);
            }


            $this->updateCartTotals($cart);

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function increaseItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // $user = Auth::user();


        $item = CartItem::find($request->cart_item_id);
        $item->qty++;
        $item->subtotal = $item->qty * $item->price;
        $item->save();

        $this->updateCartTotals($item->cart);

        return response()->json(['success' => true, 'item' => $item]);
    }


    public function decreaseItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = CartItem::find($request->cart_item_id);

        if ($item->qty > 1) {
            $item->qty--;
            $item->subtotal = $item->qty * $item->price;
            $item->save();
        } else {
            $item->delete();
        }

        $this->updateCartTotals($item->cart);

        return response()->json(['success' => true, 'item' => $item]);
    }


    public function viewCart()
    {
        $user = Auth::user();

        $cart = Cart::with('items.servicePart')
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }



    private function updateCartTotals($cart)
    {
        $total = $cart->items()->sum('subtotal');


        $final = $total;

        $cart->update([

            'total_amount' => $final
        ]);
    }
}
