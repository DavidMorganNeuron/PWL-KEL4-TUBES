@extends('layouts.cust')
@section('title', 'Payment')
@section('page')
<div class="mt-10 max-w-md mx-auto bg-white p-6 border rounded">
    <h2 class="text-xl font-bold mb-4">Payment</h2>
    <p class="text-sm text-gray-500 mb-2">Order: {{ $order->order_number }}</p>
    
    <div class="border-t border-b py-4 my-4 space-y-2 text-sm">
        <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal) }}</span></div>
        <div class="flex justify-between text-red-500"><span>Discount</span><span>- Rp {{ number_format($order->total_discount) }}</span></div>
        <div class="flex justify-between font-bold text-base"><span>Grand Total</span><span>Rp {{ number_format($order->grand_total) }}</span></div>
    </div>

    <form action="{{ route('cust.payment.process', $order->id_orders) }}" method="POST">
        @csrf
        <div class="space-y-2 mb-6">
            <label class="block text-sm"><input type="radio" name="method" value="QRIS" required> QRIS</label>
            <label class="block text-sm"><input type="radio" name="method" value="E_Wallet" required> E-Wallet</label>
        </div>
        <!-- NOTE: No actual deduction, just mark as paid -->
        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded font-bold">BAYAR SEKARANG</button>
    </form>
</div>
@endsection