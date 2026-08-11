<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = $request->user()->cartItems()->with('product.category')->get();
        $subtotal = $items->sum(fn (CartItem $item) => (float) $item->product->price * $item->quantity);

        return response()->json([
            'items' => $items,
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'count' => $items->sum('quantity'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::whereKey($data['product_id'])->where('is_active', true)->firstOrFail();
        $item = $request->user()->cartItems()->firstOrNew(['product_id' => $product->id]);
        $newQuantity = ($item->exists ? $item->quantity : 0) + $data['quantity'];
        abort_if($newQuantity > $product->stock, 422, 'Requested quantity exceeds available stock.');
        $item->quantity = $newQuantity;
        $item->save();

        return response()->json($item->load('product'), 201);
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);
        abort_if($data['quantity'] > $cartItem->product->stock, 422, 'Requested quantity exceeds available stock.');
        $cartItem->update($data);

        return response()->json($cartItem->load('product'));
    }

    public function destroy(Request $request, CartItem $cartItem): JsonResponse
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);
        $cartItem->delete();
        return response()->json(['message' => 'Cart item removed.']);
    }
}
