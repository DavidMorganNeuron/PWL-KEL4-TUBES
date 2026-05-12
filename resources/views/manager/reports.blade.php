@extends('layouts.manager')
@section('page')
<h2 class="text-xl font-bold mb-6">Branch Sales Report</h2>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left: Transactions -->
    <div class="lg:col-span-2 bg-white border rounded p-4">
        <h3 class="font-bold mb-4">Recent Transactions</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-2 text-left">Order ID</th>
                    <th class="p-2 text-left">Customer</th>
                    <th class="p-2 text-left">Total</th>
                    <th class="p-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b">
                    <td class="p-2 font-mono text-xs">{{ $order->order_number }}</td>
                    <td class="p-2">{{ $order->user->name }}</td>
                    <td class="p-2">Rp {{ number_format($order->grand_total) }}</td>
                    <td class="p-2 text-xs uppercase">{{ $order->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Right: Best Sellers -->
    <div class="bg-white border rounded p-4">
        <h3 class="font-bold mb-4">Best Sellers</h3>
        <ul class="space-y-3">
            <!-- Controller should pass $bestSellers grouped by qty -->
            @foreach($bestSellers as $index => $seller)
            <li class="flex items-center justify-between border-b pb-2">
                <div>
                    <span class="font-bold text-sm">{{ $index + 1 }}. {{ $seller->product->name }}</span>
                </div>
                <span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold">{{ $seller->total_qty }} sold</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection