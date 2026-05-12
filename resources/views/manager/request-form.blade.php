<!--req restock-->
@extends('layouts.manager')
@section('page')
<div class="max-w-md">
    <h2 class="text-xl font-bold mb-4">Request Restock</h2>
    <form action="{{ route('manager.request.store') }}" method="POST" class="bg-white p-4 border rounded space-y-4">
        @csrf
        <div>
            <label class="block text-sm mb-1">Product</label>
            <!-- Note: branch_id and manager_id will be injected in Controller from auth user -->
            <select name="id_products" class="w-full border p-2 rounded text-sm" required>
                @foreach($products as $product)
                    <option value="{{ $product->id_products }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm mb-1">Requested Qty</label>
            <input type="number" name="requested_qty" class="w-full border p-2 rounded text-sm" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Submit Request</button>
    </form>
</div>
@endsection