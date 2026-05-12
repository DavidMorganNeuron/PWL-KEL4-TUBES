@extends('layouts.cust')
@section('title', 'Menu')
@section('page')
<h2 class="text-xl font-bold mb-4">Menu - {{ session('branch_name') }}</h2>

<!-- Categories -->
<div class="flex gap-2 mb-6">
    @foreach($categories as $cat)
        <button class="px-3 py-1 bg-white border text-sm rounded">{{ $cat->name }}</button>
    @endforeach
</div>

<!-- Product List -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($products as $product)
        @if($product->is_available)
        <div class="bg-white border p-4 flex justify-between">
            <div>
                <h3 class="font-bold">{{ $product->name }}</h3>
                <!-- Show crossed price if promo exists (handled via backend variable) -->
                @if(isset($product->promo_price))
                    <p class="text-sm text-gray-400 line-through">Rp {{ number_format($product->base_price) }}</p>
                    <p class="text-red-600 font-bold">Rp {{ number_format($product->promo_price) }}</p>
                @else
                    <p class="font-bold">Rp {{ number_format($product->base_price) }}</p>
                @endif
            </div>
            <form action="{{ route('cust.cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="id_products" value="{{ $product->id_products }}">
                <button type="submit" class="bg-black text-white px-4 py-2 rounded text-sm">Add</button>
            </form>
        </div>
        @endif
    @endforeach
</div>

<!-- Cart Summary -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t p-4 flex justify-between items-center">
    <span class="font-bold">{{ count(session('cart', [])) }} Items</span>
    <a href="{{ route('cust.checkout') }}" class="bg-black text-white px-6 py-2 rounded">Checkout</a>
</div>
@endsection