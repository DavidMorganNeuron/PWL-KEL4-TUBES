<!--kds -> kitchen display system-->
@extends('layouts.manager')
@section('page')
<h2 class="text-xl font-bold mb-4">Active Orders (Status: Paid)</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach($orders as $order)
        <div class="bg-white border p-4 rounded">
            <p class="font-mono text-sm text-gray-500">#{{ $order->order_number }}</p>
            
            <!-- List Items -->
            <ul class="mt-2 text-sm divide-y">
                @foreach($order->orderItems as $item)
                    <li class="py-1">{{ $item->qty }}x {{ $item->product->name }}</li>
                @endforeach
            </ul>

            <!-- Action Buttons matching exact enums -->
            <div class="mt-4 space-y-2">
                @if($order->status == 'paid')
                    <form action="{{ route('manager.kds.update', $order->id_orders) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="cooking">
                        <button class="w-full bg-yellow-500 text-white p-2 rounded text-sm">Start Cooking</button>
                    </form>
                @endif

                @if($order->status == 'cooking')
                    <form action="{{ route('manager.kds.update', $order->id_orders) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button class="w-full bg-green-600 text-white p-2 rounded text-sm">Completed</button>
                    </form>
                    
                    <!-- Cancel Button triggers modal/JS to input cancel_reason -->
                    <button onclick="document.getElementById('cancel-{{ $order->id_orders }}').classList.remove('hidden')" 
                            class="w-full bg-red-600 text-white p-2 rounded text-sm">Emergency Cancel</button>
                            
                    <div id="cancel-{{ $order->id_orders }}" class="hidden border p-2 rounded text-xs">
                        <form action="{{ route('manager.kds.update', $order->id_orders) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="canceled">
                            <input type="text" name="cancel_reason" class="w-full border p-1 mb-1" placeholder="Reason (mandatory)" required>
                            <button class="w-full bg-red-800 text-white p-1 rounded">Confirm Cancel</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection