<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class POSController extends Controller
{
    public function index(): View
    {
        return view('pos.index', [
            'products' => Product::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'cashier_name' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'in:cash,card,qris,transfer'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:0'],
        ]);

        $payload['items'] = array_values(array_filter(
            $payload['items'],
            fn (array $item): bool => (int) $item['quantity'] > 0
        ));

        abort_if(count($payload['items']) === 0, 422, 'Please select at least one product.');

        $sale = DB::transaction(function () use ($payload) {
            $subtotal = 0;
            $items = [];

            foreach ($payload['items'] as $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];

                abort_if($product->stock < $quantity, 422, "Insufficient stock for {$product->name}");

                $lineTotal = $quantity * (float) $product->price;
                $subtotal += $lineTotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => (float) $product->price,
                    'line_total' => $lineTotal,
                ];
            }

            $tax = round($subtotal * 0.11, 2);
            $discount = 0;
            $total = round($subtotal + $tax - $discount, 2);
            $paidAmount = (float) $payload['paid_amount'];

            abort_if($paidAmount < $total, 422, 'Paid amount is less than total.');

            $sale = Sale::query()->create([
                'receipt_number' => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'change_amount' => round($paidAmount - $total, 2),
                'payment_method' => $payload['payment_method'],
                'cashier_name' => $payload['cashier_name'],
            ]);

            foreach ($items as $item) {
                $sale->items()->create([
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            return $sale;
        });

        return redirect()->route('pos.index')->with('status', 'Transaction completed: ' . $sale->receipt_number);
    }
}
