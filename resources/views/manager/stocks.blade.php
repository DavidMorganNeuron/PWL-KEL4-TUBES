@extends('layouts.manager')
@section('page')
<h2 class="text-xl font-bold mb-4">My Branch Stocks</h2>

<table class="w-full text-sm bg-white border rounded">
    <thead class="bg-gray-50 border-b">
        <tr>
            <th class="p-3 text-left">Product Name</th>
            <th class="p-3 text-left">Physical Qty</th>
            <th class="p-3 text-left">Reserved Qty</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stocks as $stock)
        <tr class="border-b">
            <td class="p-3">{{ $stock->product->name }}</td>
            <td class="p-3 font-bold {{ $stock->physical_qty <= 5 ? 'text-red-600' : '' }}">{{ $stock->physical_qty }}</td>
            <td class="p-3 text-gray-500">{{ $stock->reserved_qty }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection