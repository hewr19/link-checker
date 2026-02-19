@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Professional Cashier System</h1>
        <p>Point of Sale workflow with stock updates, tax calculation, and receipt generation.</p>

        @if(session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('pos.checkout') }}">
            @csrf
            <label>Cashier Name</label>
            <input type="text" name="cashier_name" required style="width:100%;padding:.6rem;margin:.4rem 0 .8rem;">

            <label>Payment Method</label>
            <select name="payment_method" style="width:100%;padding:.6rem;margin:.4rem 0 .8rem;">
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="qris">QRIS</option>
                <option value="transfer">Transfer</option>
            </select>

            <label>Paid Amount</label>
            <input type="number" min="0" step="0.01" name="paid_amount" required style="width:100%;padding:.6rem;margin:.4rem 0 .8rem;">

            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            <td>
                                {{ $product->name }}
                                <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}">
                            </td>
                            <td>{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td><input type="number" min="0" name="items[{{ $index }}][quantity]" value="0"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:1rem;">
                <button class="btn" type="submit">Process Transaction</button>
            </div>
        </form>
    </div>
@endsection
