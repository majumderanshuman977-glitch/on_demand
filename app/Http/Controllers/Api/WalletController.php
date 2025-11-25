<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    //


    // public function walletBalance()
    // {
    //     try {
    //         $user = Auth::user();
    //         $wallet = Wallet::findOrFail(['user_id' => $user->id]);
    //         return response()->json([
    //             'success' => true,
    //             'balance' => $wallet->balance
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }

    // public function addMoney(Request $request)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric|min:1',
    //         'description' => 'nullable|string'
    //     ]);

    //     $user = Auth::user();
    //     $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

    //     DB::beginTransaction();
    //     try {
    //         $wallet->balance += $request->amount;
    //         $wallet->save();

    //         WalletTransaction::create([
    //             'user_id'     => $user->id,
    //             'type'            => 'cash_in',
    //             'amount'          => $request->amount,
    //             'closing_balance' => $wallet->balance,
    //             'description'     => $request->description ?? 'Wallet Top-up'
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Wallet topped-up successfully',
    //             'balance' => $wallet->balance
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['success' => false, 'error' => $e->getMessage()]);
    //     }
    // }



    // public function withdraw(Request $request)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric|min:1'
    //     ]);

    //     $user = Auth::user();
    //     $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

    //     if ($wallet->balance < $request->amount) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Insufficient wallet balance'
    //         ], 400);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $wallet->balance -= $request->amount;
    //         $wallet->save();

    //         WalletTransaction::create([
    //             'user_id'     => $user->id,
    //             'type'            => 'cash_out',
    //             'amount'          => $request->amount,
    //             'closing_balance' => $wallet->balance,
    //             'description'     => 'Provider Withdrawal Request'
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Withdrawal requested successfully',
    //             'balance' => $wallet->balance
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['success' => false, 'error' => $e->getMessage()]);
    //     }
    // }


    // public function walletTransactions(Request $request)
    // {
    //     $user = Auth::user();

    //     $query = WalletTransaction::where('user_id', $user->id)
    //         ->orderBy('id', 'DESC');

    //     if ($request->type) {
    //         $query->where('type', $request->type); // cash_in / cash_out
    //     }

    //     if ($request->range == '30days') {
    //         $query->whereDate('created_at', '>=', now()->subDays(30));
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'transactions' => $query->get()
    //     ]);
    // }


    public function walletBalance()
    {
        try {
            $user = Auth::user();

            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            return response()->json([
                'success' => true,
                'balance' => $wallet->balance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * Add money to wallet
     */
    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string'
        ]);

        $user = Auth::user();
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

        DB::beginTransaction();
        try {
            $previousAmount = $wallet->balance;

            $wallet->balance += $request->amount;
            $wallet->save();

            WalletTransaction::create([
                'user_id'        => $user->id,
                'wallet_id'      => $wallet->id,
                'status'           => 'cash_in',
                'previous_amount' => $previousAmount,
                'amount'         => $request->amount,
                'closing_balance' => $wallet->balance,
                'description'    => $request->description ?? 'Wallet Top-up',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wallet topped-up successfully',
                'balance' => $wallet->balance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Withdraw money from wallet
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $user = Auth::user();
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

        if ($wallet->balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $previousAmount = $wallet->balance;

            $wallet->balance -= $request->amount;
            $wallet->save();

            WalletTransaction::create([
                'user_id'        => $user->id,
                'wallet_id'      => $wallet->id,
                'status'           => 'cash_out',
                'previous_amount' => $previousAmount,
                'amount'         => $request->amount,
                'closing_balance' => $wallet->balance,
                'description'    => 'Provider Withdrawal Request',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal requested successfully',
                'balance' => $wallet->balance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get wallet transactions
     */
    public function walletTransactions(Request $request)
    {
        $user = Auth::user();

        $query = WalletTransaction::where('user_id', $user->id)
            ->orderBy('id', 'DESC');

        // Filter by type: cash_in / cash_out
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('range') && $request->range === '30days') {
            $query->whereDate('created_at', '>=', now()->subDays(30));
        }

        return response()->json([
            'success' => true,
            'transactions' => $query->get()
        ]);
    }
}
