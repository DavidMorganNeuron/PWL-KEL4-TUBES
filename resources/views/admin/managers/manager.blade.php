{{-- daftar manager cabang --}}
@extends('admin.layouts.app')

@section('title', "Data Manager Cabang — Pod's Admin")
@section('page-title', 'Data Manager Cabang')

@section('content')



<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem;">

        @foreach($managers as $i => $mgr)
        <div class="adm-card adm-animate" style="overflow:hidden; animation-delay:{{ $i * 0.09 }}s;">

            {{-- header kartu: info cabang + status buka/tutup --}}
            <div style="background:var(--pods-espresso); padding:1.25rem 1.375rem 1rem; position:relative; overflow:hidden;">

                <div style="position:absolute; right:-20px; top:-20px; width:90px; height:90px; border-radius:50%; background:rgba(200,129,59,0.07); pointer-events:none;" aria-hidden="true"></div>

                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:0.75rem;">
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:rgba(245,233,211,0.45); margin-bottom:0.25rem;">Cabang</p>
                        <p class="font-serif" style="font-size:1.0625rem; font-weight:700; color:#F5E9D3;">{{ $mgr['branch'] }}</p>
                    </div>

                    {{-- badge status cabang --}}
                    @if($mgr['always_open'])
                    <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 9px 3px 7px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:rgba(5,150,105,0.25); color:#6EE7B7; white-space:nowrap; flex-shrink:0;">
                        <span style="width:5px; height:5px; border-radius:9999px; background:#34D399; animation:mgr-pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;" aria-hidden="true"></span>
                        Always Open
                    </span>
                    @elseif($mgr['is_open'])
                    <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 9px 3px 7px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:rgba(5,150,105,0.25); color:#6EE7B7; white-space:nowrap; flex-shrink:0;">
                        <span style="width:5px; height:5px; border-radius:9999px; background:#34D399;" aria-hidden="true"></span>
                        Buka
                    </span>
                    @else
                    <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 9px 3px 7px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:rgba(239,68,68,0.2); color:#FCA5A5; white-space:nowrap; flex-shrink:0;">
                        <span style="width:5px; height:5px; border-radius:9999px; background:#F87171;" aria-hidden="true"></span>
                        Tutup
                    </span>
                    @endif
                </div>

                {{-- jam operasional --}}
                <p style="font-size:0.75rem; font-weight:300; color:rgba(245,233,211,0.45);">
                    @if($mgr['always_open'])
                        Beroperasi 24 jam, 7 hari seminggu
                    @else
                        Jam operasional: {{ $mgr['open_time'] }} – {{ $mgr['close_time'] }} WIB
                    @endif
                </p>
            </div>

            {{-- identitas manager --}}
            <div style="padding:1.25rem 1.375rem; border-bottom:1px solid #F0E8DC; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px; height:48px; border-radius:50%; background:rgba(200,129,59,0.15); border:2px solid rgba(200,129,59,0.25); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <span class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-caramel);">
                        {{ strtoupper(substr($mgr['name'], 0, 1)) }}
                    </span>
                </div>
                <div style="min-width:0;">
                    <p style="font-size:0.9375rem; font-weight:600; color:var(--pods-espresso); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $mgr['name'] }}</p>
                    <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $mgr['email'] }}</p>
                </div>
            </div>

            {{-- statistik performa cabang bulan ini --}}
            <div style="padding:1rem 1.375rem; border-bottom:1px solid #F0E8DC; display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div style="background:#FBF6EE; border-radius:8px; padding:0.75rem; text-align:center;">
                    <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.25rem;">Order Hari Ini</p>
                    <p style="font-size:1.25rem; font-weight:700; color:var(--pods-espresso); font-variant-numeric:tabular-nums;">{{ $mgr['orders_today'] }}</p>
                </div>
                <div style="background:#FBF6EE; border-radius:8px; padding:0.75rem; text-align:center;">
                    <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.25rem;">Revenue MTD</p>
                    <p style="font-size:0.875rem; font-weight:700; color:var(--pods-caramel); font-variant-numeric:tabular-nums;">
                        Rp {{ number_format($mgr['revenue_mtd'] / 1000000, 1) }}jt
                    </p>
                </div>
            </div>

            {{-- detail alamat & bergabung --}}
            <div style="padding:1rem 1.375rem; display:flex; flex-direction:column; gap:0.625rem;">
                <div style="display:flex; align-items:flex-start; gap:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="flex-shrink:0; color:var(--pods-muted); margin-top:2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p style="font-size:0.8125rem; color:var(--pods-muted); font-weight:300; line-height:1.5;">{{ $mgr['address'] }}</p>
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="flex-shrink:0; color:var(--pods-muted);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p style="font-size:0.8125rem; color:var(--pods-muted); font-weight:300;">
                        Bergabung: {{ \Carbon\Carbon::parse($mgr['joined_at'])->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>

        </div>
        @endforeach

    </div>

</div>

@push('head-scripts')
<style>
    @keyframes mgr-pulse { 0%, 100% { opacity:1; } 50% { opacity:0.4; } }
</style>
@endpush

@endsection