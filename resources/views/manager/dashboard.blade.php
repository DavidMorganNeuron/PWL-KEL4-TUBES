@extends('layouts.manager')
@section('page')
<h2 class="text-xl font-bold mb-6">Dashboard - {{ auth()->user()->branch->name }}</h2>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white border p-4 rounded">
        <p class="text-sm text-gray-500">Today's Orders</p>
        <p class="text-2xl font-bold mt-1">{{ $todayOrdersCount }}</p>
    </div>
    <div class="bg-white border p-4 rounded">
        <p class="text-sm text-gray-500">Today's Revenue</p>
        <p class="text-2xl font-bold mt-1">Rp {{ number_format($todayRevenue) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Low Stock Alerts -->
    <div class="bg-white border p-4 rounded">
        <h3 class="font-bold mb-3 text-sm">Low Stock Alerts (≤ 5 pcs)</h3>
        <ul class="space-y-2 text-sm">
            @forelse($lowStocks as $stock)
                <li class="flex justify-between p-2 bg-red-50 rounded">
                    <span>{{ $stock->product->name }}</span>
                    <span class="font-bold text-red-600">{{ $stock->physical_qty }} left</span>
                </li>
            @empty
                <li class="text-gray-400 text-xs">All stocks are sufficient.</li>
            @endforelse
        </ul>
    </div>

    <!-- Active Kitchen Queue -->
    <div class="bg-white border p-4 rounded">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-bold text-sm">Kitchen Queue</h3>
            <a href="{{ route('manager.kds') }}" class="text-xs text-blue-600">Open KDS →</a>
        </div>
        <ul class="space-y-2 text-sm">
            @forelse($activeOrders as $order)
                <li class="flex justify-between p-2 bg-yellow-50 rounded">
                    <span class="font-mono text-xs">#{{ $order->order_number }}</span>
                    <span class="uppercase text-xs font-bold {{ $order->status == 'paid' ? 'text-yellow-600' : 'text-orange-600' }}">
                        {{ $order->status }}
                    </span>
                </li>
            @empty
                <li class="text-gray-400 text-xs">No active orders right now.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection