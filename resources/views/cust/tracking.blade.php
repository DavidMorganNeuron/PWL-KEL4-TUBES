@extends('layouts.cust')
@section('title', 'Tracking')
@section('page')
<div class="mt-10 text-center">
    <h2 class="text-xl font-bold mb-2">Order {{ $order->order_number }}</h2>
    <p class="text-gray-500 mb-8">Status: <span class="font-bold uppercase">{{ $order->status }}</span></p>

    <div class="flex justify-center items-center gap-2 text-sm">
        <!-- Step 1 -->
        <div class="text-center">
            <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto border-2 
                @if(in_array($order->status, ['pending_payment', 'paid', 'cooking', 'completed'])) border-black bg-black text-white @else border-gray-300 @endif">
                1
            </div>
            <p class="mt-2">Pending</p>
        </div>
        <div class="w-16 h-1 @if(in_array($order->status, ['paid', 'cooking', 'completed'])) bg-black @else bg-gray-300 @endif"></div>
        
        <!-- Step 2 -->
        <div class="text-center">
            <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto border-2 
                @if(in_array($order->status, ['paid', 'cooking', 'completed'])) border-black bg-black text-white @else border-gray-300 @endif">
                2
            </div>
            <p class="mt-2">Paid</p>
        </div>
        <div class="w-16 h-1 @if(in_array($order->status, ['cooking', 'completed'])) bg-black @else bg-gray-300 @endif"></div>

        <!-- Step 3 -->
        <div class="text-center">
            <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto border-2 
                @if(in_array($order->status, ['cooking', 'completed'])) border-black bg-black text-white @else border-gray-300 @endif">
                3
            </div>
            <p class="mt-2">Cooking</p>
        </div>
        <div class="w-16 h-1 @if($order->status === 'completed') bg-black @else bg-gray-300 @endif"></div>

        <!-- Step 4 -->
        <div class="text-center">
            <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto border-2 
                @if($order->status === 'completed') border-black bg-black text-white @else border-gray-300 @endif">
                4
            </div>
            <p class="mt-2">Completed</p>
        </div>
    </div>
    
    @if($order->status === 'canceled')
        <p class="text-red-500 mt-6">Order Canceled: {{ $order->cancel_reason }}</p>
    @endif
</div>
@endsection