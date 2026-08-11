<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $request->user()->orders()->with('items')->latest()->paginate(15)
        );
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        return response()->json($order->load('items.product'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'shipping_address' => ['required', 'array'],
            'shipping_address.name' => ['required', 'string', 'max:120'],
            'shipping_address.phone' => ['required', 'string', 'max:30'],
            'shipping_address.line1' => ['required', 'string', 'max:255'],
            'shipping_address.city' => ['required', 'string', 'max:100'],
            'shipping_address.country' => ['required', 'string', 'max:100'],
        ]);

        $order = DB::transaction(function () use ($request, $data) {
            $cart = $request->user()->cartItems()->with('product')->lockForUpdate()->get();
            abort_if($cart->isEmpty(), 422, 'Your cart is empty.');

            $subtotal = 0;
            foreach ($cart as $item) {
                abort_if($item->product->stock < $item->quantity, 422, "Insufficient stock for {$item->product->name}.");
                $subtotal += (float) $item->product->price * $item->quantity;
            }

            $shipping = $subtotal >= 100 ? 0 : 9.99;
            $order = $request->user()->orders()->create([
                'number' => 'ORD-' . Str::upper(Str::random(10)),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $subtotal + $shipping,
                'shipping_address' => $data['shipping_address'],
            ]);

            foreach ($cart as $item) {
                $product = $item->product;
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $item->quantity,
                    'line_total' => (float) $product->price * $item->quantity,
                ]);
                $product->decrement('stock', $item->quantity);
            }

            $request->user()->cartItems()->delete();
            return $order->load('items');
        });

        return response()->json($order, 201);
    }
}
