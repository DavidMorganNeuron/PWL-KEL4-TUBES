@extends('layouts.admin')
@section('page')
<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Products</h2>
    <a href="{{ route('admin.products.create') }}" class="bg-black text-white px-4 py-2 rounded text-sm">Add Product</a>
</div>

<table class="w-full text-sm bg-white border rounded">
    <thead class="bg-gray-50 border-b">
        <tr>
            <th class="p-3 text-left">Image</th>
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Category</th>
            <th class="p-3 text-left">Base Price</th>
            <th class="p-3 text-left">Kill Switch</th>
            <th class="p-3 text-left">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr class="border-b">
            <td class="p-3">
                @if($product->image_url)
                    <img src="{{ asset('storage/'.$product->image_url) }}" class="w-10 h-10 object-cover">
                @else
                    <div class="w-10 h-10 bg-gray-200"></div>
                @endif
            </td>
            <td class="p-3">{{ $product->name }}</td>
            <td class="p-3 text-gray-500">{{ $product->category->name }}</td>
            <td class="p-3">Rp {{ number_format($product->base_price) }}</td>
            <td class="p-3">
                <!-- Form for is_available toggle -->
                <form action="{{ route('admin.products.toggle', $product->id_products) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="is_available" value="{{ $product->is_available ? 0 : 1 }}">
                    <button type="submit" class="px-2 py-1 text-xs rounded {{ $product->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $product->is_available ? 'Active' : 'Disabled' }}
                    </button>
                </form>
            </td>
            <td class="p-3">
                <a href="{{ route('admin.products.edit', $product->id_products) }}" class="text-blue-600 text-xs">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection