@extends('layouts.cust')
@section('title', 'My Account')
@section('page')
<div class="max-w-md mt-6">
    <div class="bg-white border rounded p-6">
        <h2 class="text-xl font-bold mb-4">My Account</h2>
        
        <div class="space-y-4 text-sm">
            <div>
                <label class="block text-gray-500 mb-1">Name</label>
                <p class="font-medium">{{ auth()->user()->name }}</p>
            </div>
            <div>
                <label class="block text-gray-500 mb-1">Email</label>
                <p class="font-medium">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t">
            <p class="text-xs text-gray-500 mb-2">As a registered customer, your order history is saved automatically.</p>
            <a href="{{ route('history') }}" class="text-blue-600 text-sm hover:underline">View My Order History →</a>
        </div>
    </div>
</div>
@endsection