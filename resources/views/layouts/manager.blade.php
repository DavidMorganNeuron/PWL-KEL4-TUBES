@extends('layouts.app')
@section('content')
<div class="flex h-screen">
    <aside class="w-56 bg-white border-r p-4">
        <h1 class="font-bold mb-6">Manager Panel</h1>
        <ul class="space-y-2 text-sm">
            <li><a href="{{ route('manager.dashboard') }}" class="block p-2 bg-gray-100 rounded">Dashboard</a></li>
            <li><a href="{{ route('manager.kds') }}">Kitchen Display</a></li>
            <li><a href="{{ route('manager.stocks') }}">My Stocks</a></li>
            <li><a href="{{ route('manager.request.form') }}">Request Restock</a></li>
        </ul>
    </aside>
    <main class="flex-1 p-6 overflow-y-auto bg-gray-50">
        @yield('page')
    </main>
</div>
@endsection