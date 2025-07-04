<?php

namespace App\Http\Controllers;

use App\Models\GlobalPool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LimboController extends Controller
{
    /**
     * Handle a Limbo game round.
     * Expects: betAmount (float), targetMultiplier (float)
     * Returns: { win: bool, multiplierAchieved: float }
     */
    public function play(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'betAmount' => 'required|numeric|min:1',
            'targetMultiplier' => 'required|numeric|min:1.05|max:999',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $betAmount = (float)$request->input('betAmount');
        $targetMultiplier = (float)$request->input('targetMultiplier');

        // Check user balance
        if ($betAmount > $user->no_edge_cash) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        $payout = $betAmount * ($targetMultiplier - 1);

        // Make sure win amount does not exceed 20% of global pool
        $globalPool = GlobalPool::where('currency_name', '=', 'NoEdgeCash')->first();
        if ($payout > ($globalPool->currency_value * 0.2)) {
            return response()->json(['error' => 'Payout exceeds 20% of global pool'], 400);
        }

        // Generate random multiplier (crash point)
        $random = mt_rand() / mt_getrandmax();
        $multiplierAchieved = 1 / $random;
        if ($multiplierAchieved > 999) {
            $multiplierAchieved = 999;
        }

        $win = $multiplierAchieved >= $targetMultiplier;

        // Update balance
        try {
            if ($win) {
                $user->increment('no_edge_cash', $payout);
                GlobalPool::where('currency_name', '=', 'NoEdgeCash')->decrement('currency_value', $payout);
            } else {
                $user->decrement('no_edge_cash', $betAmount);
                GlobalPool::where('currency_name', '=', 'NoEdgeCash')->increment('currency_value', $betAmount);
            }
        } catch (\Exception $e) {
            Log::error('Limbo game error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }

        return response()->json([
            'win' => $win,
            'multiplierAchieved' => round($multiplierAchieved, 2)
        ]);
    }
}
