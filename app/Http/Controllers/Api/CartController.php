<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $cart->load('items.product');

        return response()->json([
            'data' => new CartResource($cart),
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        $item = $cart->items()->where('product_id', $validated['product_id'])->first();

        if ($item) {
            $item->increment('quantity', $validated['quantity'] ?? 1);
        } else {
            $cart->items()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'] ?? 1,
            ]);
        }

        $cart->load('items.product');

        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Товар добавлен в корзину',
        ]);
    }
}
