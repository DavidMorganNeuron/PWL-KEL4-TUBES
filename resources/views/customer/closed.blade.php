{{-- Pod's Closed: halaman saat semua cabang tutup --}}
@extends('customer.layouts.app')

@section('title', "Pod's Tutup Untuk Sementara")

@section('content')

<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:#F0E8DC;">

    <div style="text-align:center; max-width:480px; padding:2rem;">

        <div style="width:80px; height:80px; border-radius:50%; background:rgba(200,129,59,0.12); display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem;" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>

        <h1 class="font-serif" style="font-size:1.75rem; font-weight:900; color:var(--pods-espresso); margin-bottom:0.75rem;">
            Pod's Tutup Untuk Sementara
        </h1>

        <p style="font-size:0.9375rem; color:var(--pods-muted); font-weight:300; line-height:1.7; margin-bottom:2rem;">
            Saat ini seluruh cabang Pod's sedang tutup. Silakan kembali lagi nanti untuk menikmati sajian terbaik kami.
        </p>

        <a href="{{ route('main') }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 2rem; border-radius:9999px; background:var(--pods-caramel); color:#1C0F0A; font-weight:600; font-size:0.875rem; text-decoration:none; transition:background 0.2s;"
            onmouseover="this.style.background='#D99045'" onmouseout="this.style.background='#C8813B'">
            Kembali ke Beranda
        </a>

    </div>

</div>

@endsection
