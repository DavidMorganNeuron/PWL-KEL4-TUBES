@extends('layouts.cust')

@section('title', 'Select Branch')

@section('page')
<div class="mt-10 text-center">
    <h2 class="text-2xl font-bold mb-6">Select Branch</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($branches as $branch)
        <form action="{{ route('orders.setBranch') }}" method="POST">
            @csrf
            <input type="hidden" name="id_branches" value="{{ $branch->id_branches }}">
            <button type="submit" class="block w-full p-6 bg-white border rounded text-left hover:border-black">
                <h3 class="font-bold">{{ $branch->name }}</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $branch->address }}</p>
                <p class="text-xs mt-2 font-medium 
                    @if($branch->is_always_open || (now()->between($branch->open_time, $branch->close_time))) 
                        text-green-600 
                    @else 
                        text-red-600 
                    @endif">
                    {{ $branch->is_always_open ? 'Open 24 Hours' : $branch->open_time . ' - ' . $branch->close_time }}
                </p>
            </button>
        </form>
        @endforeach
    </div>
</div>
@endsection