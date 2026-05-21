<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Article;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.article_id' => 'required|exists:articles,id',
            'items.*.quantity' => 'required|integer|min:1',

            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_province' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_phone' => 'required|string|max:20',

            'billing_same_as_shipping' => 'nullable|boolean',

            'billing_name' => 'nullable|string|max:255',
            'billing_address' => 'nullable|string|max:255',
            'billing_city' => 'nullable|string|max:255',
            'billing_province' => 'nullable|string|max:255',
            'billing_postal_code' => 'nullable|string|max:20',
            'billing_phone' => 'nullable|string|max:20',

            'card_number' => 'required|string|size:16',
            'card_expiry' => 'required|string|max:7',
            'card_cvv' => 'required|string|size:3',
        ]);

        $total = 0;
        $orderItemsData = [];

        foreach ($validated['items'] as $item) {
            $article = Article::findOrFail($item['article_id']);

            if ($article->stock < $item['quantity']) {
                return response()->json([
                    'message' => "Stock insuficiente para: {$article->name}",
                    'available_stock' => $article->stock,
                ], 400);
            }

            $subtotal = $article->price * $item['quantity'];
            $total += $subtotal;

            $orderItemsData[] = [
                'article_id' => $article->id,
                'article_name' => $article->name,
                'quantity' => $item['quantity'],
                'unit_price' => $article->price,
                'subtotal' => $subtotal,
            ];
        }

        $billingSame = filter_var($validated['billing_same_as_shipping'] ?? true, FILTER_VALIDATE_BOOLEAN);

        $orderData = [
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => $total,
            'shipping_name' => $validated['shipping_name'],
            'shipping_address' => $validated['shipping_address'],
            'shipping_city' => $validated['shipping_city'],
            'shipping_province' => $validated['shipping_province'],
            'shipping_postal_code' => $validated['shipping_postal_code'],
            'shipping_phone' => $validated['shipping_phone'],
            'billing_same_as_shipping' => $billingSame,
            'card_last_four' => substr($validated['card_number'], -4),
            'card_brand' => 'Visa',
        ];

        if ($billingSame) {
            $orderData['billing_name'] = $validated['shipping_name'];
            $orderData['billing_address'] = $validated['shipping_address'];
            $orderData['billing_city'] = $validated['shipping_city'];
            $orderData['billing_province'] = $validated['shipping_province'];
            $orderData['billing_postal_code'] = $validated['shipping_postal_code'];
            $orderData['billing_phone'] = $validated['shipping_phone'];
        } else {
            $orderData['billing_name'] = $validated['billing_name'];
            $orderData['billing_address'] = $validated['billing_address'];
            $orderData['billing_city'] = $validated['billing_city'];
            $orderData['billing_province'] = $validated['billing_province'];
            $orderData['billing_postal_code'] = $validated['billing_postal_code'];
            $orderData['billing_phone'] = $validated['billing_phone'];
        }

        $order = Order::create($orderData);

        foreach ($orderItemsData as $itemData) {
            $order->items()->create($itemData);

            $article = Article::find($itemData['article_id']);
            $article->decrement('stock', $itemData['quantity']);
            $article->increment('sell_count', $itemData['quantity']);
        }

        return response()->json([
            'message' => 'Pedido realizado con éxito',
            'order' => $order->load('items'),
        ], 201);
    }

    public function index(Request $request)
    {
        $orders = Order::with('items')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with('items')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($order);
    }
}
