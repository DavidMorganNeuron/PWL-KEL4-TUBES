@extends('layouts.admin')
@section('page')
<h2 class="text-xl font-bold mb-4">{{ isset($product) ? 'Edit' : 'Add' }} Product</h2>

<form action="{{ isset($product) ? route('admin.products.update', $product->id_products) : route('admin.products.store') }}" 
      method="POST" 
      enctype="multipart/form-data" 
      class="max-w-lg bg-white p-4 border rounded space-y-4">
    
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div>
        <label class="block text-sm mb-1">Product Name</label>
        <input type="text" name="name" value="{{ $product->name ?? '' }}" class="w-full border p-2 rounded text-sm" required>
    </div>
    
    <div>
        <label class="block text-sm mb-1">Category</label>
        <select name="id_categories" class="w-full border p-2 rounded text-sm" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id_categories }}" {{ isset($product) && $product->id_categories == $cat->id_categories ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm mb-1">Base Price</label>
        <input type="number" step="2" name="base_price" value="{{ $product->base_price ?? '' }}" class="w-full border p-2 rounded text-sm" required>
    </div>

    <div>
        <label class="block text-sm mb-1">Image (.jpg/.png)</label>
        <input type="file" name="image" accept=".jpg,.png" class="w-full border p-2 rounded text-sm">
        @if(isset($product) && $product->image_url)
            <p class="text-xs mt-1">Current: {{ $product->image_url }}</p>
        @endif
    </div>

    <button type="submit" class="bg-black text-white px-4 py-2 rounded text-sm">Save</button>
</form>
@endsection