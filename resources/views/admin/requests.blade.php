@extends('layouts.admin')
@section('page')
<h2 class="text-xl font-bold mb-4">Restock Validations</h2>

<table class="w-full text-sm bg-white border rounded">
    <thead class="bg-gray-50 border-b">
        <tr>
            <th class="p-3 text-left">ID Request</th>
            <th class="p-3 text-left">Branch</th>
            <th class="p-3 text-left">Manager</th>
            <th class="p-3 text-left">Product</th>
            <th class="p-3 text-left">Qty</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $req)
        <tr class="border-b">
            <td class="p-3">{{ $req->id_request_log }}</td>
            <td class="p-3">{{ $req->branch->name }}</td>
            <td class="p-3">{{ $req->manager->name }}</td>
            <td class="p-3">{{ $req->product->name }}</td>
            <td class="p-3">{{ $req->requested_qty }}</td>
            <td class="p-3">
                <span class="px-2 py-1 rounded text-xs 
                    @if($req->status == 'pending') bg-yellow-100 text-yellow-700 
                    @elseif($req->status == 'approved') bg-green-100 text-green-700 
                    @else bg-red-100 text-red-700 @endif">
                    {{ strtoupper($req->status) }}
                </span>
            </td>
            <td class="p-3">
                @if($req->status == 'pending')
                    <!-- Approve Trigger: Controller adds stock to specific branch table & creates stock_log -->
                    <form action="{{ route('admin.requests.action', $req->id_request_log) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button class="bg-green-600 text-white px-2 py-1 rounded text-xs">Approve</button>
                    </form>
                    <form action="{{ route('admin.requests.action', $req->id_request_log) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button class="bg-red-600 text-white px-2 py-1 rounded text-xs">Reject</button>
                    </form>
                @else
                    <span class="text-gray-400 text-xs">Processed</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection