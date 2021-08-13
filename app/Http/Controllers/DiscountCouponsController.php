<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use App\Models\GiftCardType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountCouponsController extends Controller
{
    /**
     * Calculate the Total Cart Payment after the Discount
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculatePayment(Request $request)
    {
        $cart_total = $request->input('cart_total');

        $gift_card_ids = $request->input('gift_card_ids', []);

        if (!empty($cart_total)) {
            $discount = $this->calculateDiscount($cart_total, $gift_card_ids);

            return $discount;
        } else {
            return response()->json([
                'status' => 'error',
                'msg' => 'Total Cart cannot be empty.'
            ], 422);
        }
    }

    /**
     * Handle the Calculation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateDiscount($cart_total, $gift_card_ids)
    {
        $giftCardsTable = (new GiftCard())->getTable();

        $giftCardTypesTable = (new GiftCardType())->getTable();

        $gifts = DB::table($giftCardsTable)
                ->whereIn($giftCardsTable . '.type_id', $gift_card_ids)
                ->join($giftCardTypesTable, $giftCardsTable . '.type_id', '=', $giftCardTypesTable . '.id')
                ->get();

        if ($gifts->count() > 1) {
            $discount = $gifts->map(function ($gift_card) {
                return $gift_card->max_avail_value/100 * $gift_card->value;
            });
        } else {
            $discount = $gifts->map(function ($gift_card) {
                return $gift_card->value;
            });
        }

        $total_discount = $discount->sum();

        $payable_amount = $cart_total - $total_discount;

        return response()->json([
                'status' => 'success',
                'payable_amount' => $payable_amount,
                'gift_discount' => $total_discount
        ], 200);
    }

    public function getCalculation(Request $request)
    {
        $calculation = $this->calculatePayment($request);

        $calculation = json_encode($calculation);

        $calculation = json_decode($calculation);

        $calculation = collect($calculation)->flatten();

        return view('calculation')->with('calculation', $calculation);
    }
}
