@extends('layouts.admin')
@section('page')
<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Manage Promos</h2>
    <a href="{{ route('admin.promos.create') }}" class="bg-black text-white px-4 py-2 rounded text-sm">Add Promo</a>
</div>

<table class="w-full text-sm bg-white border rounded">
    <thead class="bg-gray-50 border-b">
        <tr>
            <th class="p-3 text-left">Promo Name</th>
            <th class="p-3 text-left">Scope</th>
            <th class="p-3 text-left">Discount</th>
            <th class="p-3 text-left">Valid Period</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($promos as $promo)
        <tr class="border-b">
            <td class="p-3 font-medium">{{ $promo->name }}</td>
            
            <!-- Scope Logic: null = National, else Local -->
            <td class="p-3">
                @if(is_null($promo->branch_id))
                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">National</span>
                @else
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">Local: {{ $promo->branch->name }}</span>
                @endif
            </td>
            
            <!-- Discount Logic: percentage or nominal -->
            <td class="p-3">
                @if($promo->discount_type == 'percentage')
                    <span class="font-bold">{{ $promo->discount_value }}%</span>
                @else
                    <span class="font-bold">Rp {{ number_format($promo->discount_value) }}</span>
                @endif
            </td>
            
            <!-- Date Period -->
            <td class="p-3 text-xs text-gray-500">
                {{ $promo->start_date->format('d M Y, H:i') }} <br>
                <span class="text-red-500">to</span> {{ $promo->end_date->format('d M Y, H:i') }}
            </td>
            
            <!-- is_active Toggle -->
            <td class="p-3">
                <form action="{{ route('admin.promos.toggle', $promo->id_promos) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="is_active" value="{{ $promo->is_active ? 0 : 1 }}">
                    <button type="submit" class="px-2 py-1 text-xs rounded {{ $promo->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $promo->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </form>
            </td>
            
            <!-- Actions -->
            <td class="p-3">
                <a href="{{ route('admin.promos.edit', $promo->id_promos) }}" class="text-blue-600 text-xs hover:underline">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection