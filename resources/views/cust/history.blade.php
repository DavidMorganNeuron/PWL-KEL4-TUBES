@extends('layouts.cust')
@section('title', 'History')
@section('page')
<h2 class="text-xl font-bold mb-4">Order History</h2>

<table class="w-full text-sm bg-white border rounded">
    <thead class="bg-gray-50 border-b">
        <tr>
            <th class="p-3 text-left">Order Number</th>
            <th class="p-3 text-left">Branch</th>
            <th class="p-3 text-left">Items</th>
            <th class="p-3 text-left">Grand Total</th>
            <th class="p-3 text-left">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr class="border-b">
            <td class="p-3 font-mono">{{ $order->order_number }}</td>
            <td class="p-3">{{ $order->branch->name }}</td>
            <td class="p-3">
                <!-- Show list of items bought -->
                @foreach($order->orderItems as $item)
                    <span class="block text-xs">{{ $item->qty }}x {{ $item->product->name }}</span>
                @endforeach
            </td>
            <td class="p-3 font-bold">Rp {{ number_format($order->grand_total) }}</td>
            <td class="p-3">
                <span class="px-2 py-1 rounded text-xs 
                    @if($order->status == 'completed') bg-green-100 text-green-700 
                    @elseif($order->status == 'canceled') bg-red-100 text-red-700 
                    @else bg-yellow-100 text-yellow-700 @endif">
                    {{ strtoupper($order->status) }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection