@extends('layouts.app')
@section('content')
<div class="flex h-screen">
    <aside class="w-56 bg-white border-r p-4">
        <h1 class="font-bold mb-6">Admin Pusat</h1>
        <ul class="space-y-2 text-sm">
            <li><a href="{{ route('admin.dashboard') }}" class="block p-2 bg-gray-100 rounded">Dashboard</a></li>
            <li><a href="{{ route('admin.products.index') }}">Manage Menu</a></li>
            <li><a href="{{ route('admin.promos.index') }}">Manage Promos</a></li>
            <li><a href="{{ route('admin.requests') }}">Validate Requests</a></li>
            <li><a href="{{ route('admin.managers') }}">Branch Managers</a></li>
        </ul>
    </aside>
    <main class="flex-1 p-6 overflow-y-auto bg-gray-50">
        @yield('page')
    </main>
</div>
@endsection