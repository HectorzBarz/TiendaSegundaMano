<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $orders = Order::with(['items', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $order = Order::with(['items.article', 'user'])->findOrFail($id);

        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,completed,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Estado actualizado correctamente',
            'order' => $order->load('items'),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Pedido eliminado correctamente']);
    }
}
