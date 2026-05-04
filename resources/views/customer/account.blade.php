@extends('layout.app')

@section('content')
    <div style="padding: 20px; font-family: sans-serif;">
        <h1>Profil Akun</h1>
        <hr>
        
        {{-- Menggunakan display_name yang memotong maksimal 2 kata --}}
        <h3>Halo, {{ Auth::user()->display_name }}!</h3>
        
        <p><strong>Nama Sesuai KTP:</strong> {{ Auth::user()->name }}</p>
        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        <p><strong>Bergabung Sejak:</strong> {{ Auth::user()->created_at->format('d F Y') }}</p>
    </div>
@endsection