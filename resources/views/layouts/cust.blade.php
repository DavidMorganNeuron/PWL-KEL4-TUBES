@extends('layouts.app')
@section('content')
<div class="flex h-screen">
    <!-- Your Sidebar -->
    <aside class="w-56 bg-white border-r p-4">
        <h1 class="font-bold text-lg mb-6">Pod's Coffee</h1>
        <ul>
            <li class="{{ request()->routeIs('main') ? 'active bg-gray-100 rounded' : '' }} mb-1">
                <a href="{{ route('main') }}" class="block p-2">Main</a>
            </li>
            <li class="{{ request()->routeIs('orders*') ? 'active bg-gray-100 rounded' : '' }} mb-1">
                <a href="{{ route('orders.branch') }}" class="block p-2">Order</a>
            </li>
            <li class="{{ request()->routeIs('history*') ? 'active bg-gray-100 rounded' : '' }} mb-1">
                <a href="{{ route('history') }}" class="block p-2">History</a>
            </li>
            <li class="{{ request()->routeIs('account*') ? 'active bg-gray-100 rounded' : '' }} mb-1">
                <a href="{{ route('account') }}" class="block p-2">Account</a>
            </li>
        </ul>
    </aside>
    
    <main class="flex-1 p-6 overflow-y-auto bg-gray-50">
        @yield('page')
    </main>
</div>
@endsection