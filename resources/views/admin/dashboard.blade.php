{{-- ADMIN DASHBOARD: laporan eksekutif global seluruh cabang --}}
@extends('admin.layouts.app')

@section('title', "Dashboard Admin — Pod's")
@section('page-title', 'Laporan Eksekutif Global')

@section('content')

@php
    $globalStats = [
        ['label' => 'Total Pendapatan Global',  'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'sub' => 'Semua cabang bulan ini',    'icon' => '💰'],
        ['label' => 'Total Pesanan Selesai',    'value' => number_format($totalOrders, 0, ',', '.'),           'sub' => 'Transaksi berhasil',        'icon' => '🧾'],
        ['label' => 'Produk Aktif',             'value' => $activeProducts . ' Produk',                        'sub' => 'Tersedia di menu',        'icon' => '☕'],
        ['label' => 'Request Pending',          'value' => $pendingRequests . ' Pengajuan',                    'sub' => 'Menunggu validasi admin',   'icon' => '⏳'],
    ];
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">
<div style="max-width:1100px;">

    {{-- ================================================================
         SECTION 1: STAT CARDS GLOBAL
    ================================================================ --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.75rem;">
        @foreach($globalStats as $i => $stat)
        <div class="adm-card adm-animate" style="padding:1.25rem 1.375rem; animation-delay:{{ $i * 0.06 }}s;">
            <div style="font-size:1.5rem; margin-bottom:0.625rem;">{{ $stat['icon'] }}</div>
            <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.375rem;">{{ $stat['label'] }}</p>
            <p class="font-serif" style="font-size:1.25rem; font-weight:700; color:var(--pods-espresso); line-height:1.1; margin-bottom:0.25rem; font-variant-numeric:tabular-nums;">{{ $stat['value'] }}</p>
            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">{{ $stat['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ================================================================
         SECTION 2: PERBANDINGAN PENDAPATAN ANTAR CABANG
    ================================================================ --}}
    <div style="display:grid; grid-template-columns:1fr 320px; gap:1rem; margin-bottom:1.75rem; align-items:start;">

        {{-- kartu perbandingan cabang --}}
        <div class="adm-card adm-animate" style="animation-delay:0.24s; overflow:hidden;">
            <div style="padding:1.125rem 1.375rem 0.875rem; border-bottom:1px solid #F0E8DC; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.125rem;">Bulan Ini</p>
                    <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:var(--pods-espresso);">Perbandingan Pendapatan Cabang</h2>
                </div>
                <a href="{{ route('admin.reports.sales') }}" style="font-size:0.75rem; color:var(--pods-caramel); font-weight:500; text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Laporan Lengkap →</a>
            </div>
            <div style="padding:1.25rem 1.375rem; display:flex; flex-direction:column; gap:1.125rem;">
                @foreach($branchRevenue as $branch)
                <div>
                    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.4rem;">
                        <span style="font-size:0.9375rem; font-weight:600; color:var(--pods-espresso);">{{ $branch['name'] }}</span>
                        <div style="text-align:right;">
                            <span style="font-size:0.9375rem; font-weight:700; color:var(--pods-espresso); font-variant-numeric:tabular-nums;">Rp {{ number_format($branch['revenue'], 0, ',', '.') }}</span>
                            <span style="font-size:0.75rem; color:var(--pods-muted); margin-left:0.5rem;">{{ $branch['orders'] }} order</span>
                        </div>
                    </div>
                    {{-- progress bar proporsi terhadap total --}}
                    <div style="height:8px; background:#EDE0CC; border-radius:9999px; overflow:hidden;">
                        <div style="height:100%; width:{{ $branch['pct'] }}%; background:var(--pods-caramel); border-radius:9999px; transition:width 0.5s ease;"></div>
                    </div>
                    <p style="font-size:0.6875rem; color:var(--pods-muted); font-weight:300; margin-top:0.25rem;">{{ $branch['pct'] }}% dari total pendapatan</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- top seller overall --}}
        <div class="adm-card adm-animate" style="animation-delay:0.3s; overflow:hidden;">
            <div style="padding:1.125rem 1.375rem 0.75rem; border-bottom:1px solid #F0E8DC;">
                <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.125rem;">Semua Cabang</p>
                <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:var(--pods-espresso);">Top Seller Overall</h2>
            </div>
            <ul style="list-style:none; margin:0; padding:0.5rem 0;" role="list">
                @foreach($topSellersOverall as $ts)
                <li style="display:flex; align-items:center; gap:0.75rem; padding:0.625rem 1.375rem; {{ !$loop->last ? 'border-bottom:1px solid #F8F0E6;' : '' }}">
                    <span style="width:22px; height:22px; border-radius:50%; background:{{ $loop->first ? 'var(--pods-caramel)' : '#EDE0CC' }}; color:{{ $loop->first ? '#1C0F0A' : 'var(--pods-muted)' }}; font-size:0.6875rem; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;">{{ $ts['rank'] }}</span>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:0.875rem; font-weight:500; color:var(--pods-espresso); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $ts['name'] }}</p>
                        <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300; font-variant-numeric:tabular-nums;">Rp {{ number_format($ts['revenue'], 0, ',', '.') }}</p>
                    </div>
                    <span style="font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); white-space:nowrap; font-variant-numeric:tabular-nums;">{{ $ts['qty'] }} pcs</span>
                </li>
                @endforeach
            </ul>
        </div>

    </div>

    {{-- ================================================================
         SECTION 3: TOP SELLER PER CABANG
    ================================================================ --}}
    <div class="adm-card adm-animate" style="animation-delay:0.36s; overflow:hidden; margin-bottom:1.75rem;">
        <div style="padding:1.125rem 1.375rem 0.875rem; border-bottom:1px solid #F0E8DC;">
            <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.125rem;">Breakdown</p>
            <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:var(--pods-espresso);">Top Seller per Cabang (Top 3)</h2>
        </div>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0; overflow:hidden;">
            @foreach($topSellerPerBranch as $branchName => $sellers)
            <div style="{{ !$loop->last ? 'border-right:1px solid #F0E8DC;' : '' }} padding:1rem 1.375rem;">
                <p style="font-size:0.6875rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:var(--pods-caramel); margin-bottom:0.75rem;">{{ $branchName }}</p>
                @foreach($sellers as $idx => $seller)
                <div style="display:flex; align-items:center; gap:0.625rem; padding:0.4rem 0; {{ $idx < count($sellers)-1 ? 'border-bottom:1px dashed #F0E8DC;' : '' }}">
                    <span style="width:18px; height:18px; border-radius:50%; background:{{ $idx === 0 ? 'var(--pods-caramel)' : '#EDE0CC' }}; color:{{ $idx === 0 ? '#1C0F0A' : 'var(--pods-muted)' }}; font-size:0.625rem; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;">{{ $idx + 1 }}</span>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:0.8125rem; font-weight:500; color:var(--pods-espresso); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $seller['name'] }}</p>
                    </div>
                    <span style="font-size:0.75rem; font-weight:600; color:var(--pods-muted); white-space:nowrap; font-variant-numeric:tabular-nums;">{{ $seller['qty'] }} pcs</span>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    {{-- ================================================================
         SECTION 4: SHORTCUT AKSI CEPAT
    ================================================================ --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem;">
        @foreach([
            ['label' => 'Tambah Produk',     'route' => 'admin.catalogs.create',  'icon' => '+ Produk'],
            ['label' => 'Buat Promo Baru',   'route' => 'admin.promos.create',    'icon' => '+ Promo'],
            ['label' => 'Validasi Request',  'route' => 'admin.requests.index',   'icon' => '2 Pending'],
            ['label' => 'Data Manager',      'route' => 'admin.managers.index',   'icon' => '3 Manager'],
        ] as $i => $shortcut)
        <a
            href="{{ route($shortcut['route']) }}"
            class="adm-card adm-animate"
            style="padding:1.125rem 1.25rem; display:flex; align-items:center; justify-content:space-between; text-decoration:none; transition:box-shadow 0.2s, transform 0.2s; animation-delay:{{ 0.42 + $i * 0.06 }}s;"
            onmouseover="this.style.boxShadow='0 6px 20px rgba(28,15,10,0.1)';this.style.transform='translateY(-2px)';"
            onmouseout="this.style.boxShadow='';this.style.transform='';"
        >
            <span style="font-size:0.875rem; font-weight:600; color:var(--pods-espresso);">{{ $shortcut['label'] }}</span>
            <span style="font-size:0.75rem; font-weight:600; color:var(--pods-caramel);">{{ $shortcut['icon'] }} →</span>
        </a>
        @endforeach
    </div>

</div>
</div>
@endsection