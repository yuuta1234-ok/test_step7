<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        $productId = $request->input('product_id');

        $product = Product::find($productId);
        if (!$product || $product->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => '在庫がないため購入できません。',
            ], 400);
        }

        DB::beginTransaction();
        try {
            Sale::create(['product_id' => $productId]);

            $product->decrement('stock');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '購入が完了しました。',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => '購入処理中にエラーが発生しました。',
            ], 500);
        }
    }
}
