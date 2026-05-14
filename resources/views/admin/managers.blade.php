@extends('layouts.admin')
@section('page')
<h2 class="text-xl font-bold mb-4">Branch Managers</h2>
<p class="text-xs text-red-500 mb-4">*READ ONLY. One branch = One manager.</p>

<table class="w-full text-sm bg-white border rounded">
    <thead class="bg-gray-50 border-b">
        <tr>
            <th class="p-3 text-left">Manager Name</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 text-left">Assigned Branch</th>
        </tr>
    </thead>
    <tbody>
        @foreach($managers as $manager)
        <tr class="border-b">
            <td class="p-3">{{ $manager->name }}</td>
            <td class="p-3 text-gray-500">{{ $manager->email }}</td>
            <td class="p-3 font-bold">{{ $manager->branch->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection