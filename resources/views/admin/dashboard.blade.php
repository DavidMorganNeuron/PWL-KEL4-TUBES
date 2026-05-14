@extends('layouts.admin')
@section('page')
<h2 class="text-xl font-bold mb-6">Executive Dashboard</h2>

<!-- Top Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white border p-4 rounded">
        <p class="text-sm text-gray-500">Total Revenue (All Branches)</p>
        <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalRevenue) }}</p>
    </div>
    <div class="bg-white border p-4 rounded">
        <p class="text-sm text-gray-500">Total Transactions</p>
        <p class="text-2xl font-bold mt-1">{{ $totalOrders }}</p>
    </div>
    <div class="bg-white border p-4 rounded">
        <p class="text-sm text-gray-500">Overall Top Seller</p>
        <p class="text-lg font-bold mt-1">{{ $overallTopSeller->product->name ?? '-' }}</p>
    </div>
</div>

<!-- Branch Comparison & Global Stock View -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Revenue per Branch -->
    <div class="bg-white border p-4 rounded">
        <h3 class="font-bold mb-4">Revenue per Branch</h3>
        @foreach($branchRevenues as $branch)
        <div class="flex justify-between items-center mb-2 text-sm">
            <span>{{ $branch->name }}</span>
            <span class="font-bold">Rp {{ number_format($branch->revenue) }}</span>
        </div>
        @endforeach
    </div>

    <!-- SPECIFIC PDF REQUIREMENT: global_stocks_view -->
    <div class="bg-white border p-4 rounded">
        <h3 class="font-bold mb-4">Global Physical Stock Assets (global_stocks_view)</h3>
        <table class="w-full text-xs">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-2 text-left">Product</th>
                    <th class="p-2 text-left">Branch</th>
                    <th class="p-2 text-left">Physical Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($globalStocks as $stock)
                <tr class="border-b">
                    <td class="p-2">{{ $stock->product->name }}</td>
                    <td class="p-2">{{ $stock->branch_name }}</td>
                    <td class="p-2 font-bold">{{ $stock->physical_qty }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection