{{-- ADMIN REPORTS SALES: laporan penjualan global seluruh cabang --}}
@extends('admin.layouts.app')

@section('title', "Laporan Penjualan — Pod's Admin")
@section('page-title', 'Laporan Penjualan Global')

@section('content')

@php
    /* data dummy: ringkasan global */
    $globalSummary = [
        ['label' => 'Total Pendapatan Global', 'value' => 'Rp 148.250.000', 'sub' => 'Semua cabang, bulan ini',      'icon' => '💰'],
        ['label' => 'Total Transaksi Selesai', 'value' => '1.842',          'sub' => 'Dari 1.897 total order',       'icon' => '🧾'],
        ['label' => 'Total Diskon Diberikan',  'value' => 'Rp 9.340.000',   'sub' => 'Dari program promo aktif',     'icon' => '🏷'],
        ['label' => 'Rata-rata per Transaksi', 'value' => 'Rp 80.483',      'sub' => 'Dari transaksi selesai',       'icon' => '📊'],
    ];

    /* data dummy: pendapatan per cabang */
    $perBranch = [
        ['branch' => 'Dr. Mansyur',   'completed' => 842,  'canceled' => 12, 'revenue' => 68500000, 'discount' => 4200000, 'net' => 64300000],
        ['branch' => 'Jamin Ginting', 'completed' => 621,  'canceled' => 8,  'revenue' => 51200000, 'discount' => 3100000, 'net' => 48100000],
        ['branch' => 'Gatot Subroto', 'completed' => 379,  'canceled' => 5,  'revenue' => 28550000, 'discount' => 2040000, 'net' => 26510000],
    ];

    $totalRevenue = array_sum(array_column($perBranch, 'revenue'));

    /* data dummy: top seller per cabang (top 3) */
    $topSellersPerBranch = [
        'Dr. Mansyur' => [
            ['name' => 'Caramel Macchiato', 'qty' => 218, 'revenue' => 6104000],
            ['name' => 'Iced Americano',    'qty' => 187, 'revenue' => 4114000],
            ['name' => 'Brown Sugar Latte', 'qty' => 156, 'revenue' => 4524000],
        ],
        'Jamin Ginting' => [
            ['name' => 'Matcha Latte',      'qty' => 143, 'revenue' => 4147000],
            ['name' => 'Caramel Macchiato', 'qty' => 122, 'revenue' => 3416000],
            ['name' => 'Chocolate Frappe',  'qty' => 98,  'revenue' => 2940000],
        ],
        'Gatot Subroto' => [
            ['name' => 'Iced Americano',    'qty' => 113, 'revenue' => 2486000],
            ['name' => 'Cold Brew',         'qty' => 87,  'revenue' => 2262000],
            ['name' => 'Brown Sugar Latte', 'qty' => 76,  'revenue' => 2204000],
        ],
    ];

    /* data dummy: tabel transaksi lintas cabang terbaru */
    $recentTransactions = [
        ['order_number' => 'PODS-20260517-BB0004', 'branch' => 'Dr. Mansyur',   'customer' => 'Andi Wijaya',   'status' => 'cooking',   'grand_total' => 95000,  'date' => '17 Mei 2026, 14:22'],
        ['order_number' => 'PODS-20260517-BB0003', 'branch' => 'Dr. Mansyur',   'customer' => 'Sari Dewi',    'status' => 'cooking',   'grand_total' => 52000,  'date' => '17 Mei 2026, 14:18'],
        ['order_number' => 'PODS-20260517-JG0012', 'branch' => 'Jamin Ginting', 'customer' => 'Rina Susanti', 'status' => 'completed', 'grand_total' => 78000,  'date' => '17 Mei 2026, 14:05'],
        ['order_number' => 'PODS-20260517-GS0008', 'branch' => 'Gatot Subroto', 'customer' => 'Dodi Prasetya','status' => 'completed', 'grand_total' => 130000, 'date' => '17 Mei 2026, 13:58'],
        ['order_number' => 'PODS-20260517-BB0002', 'branch' => 'Dr. Mansyur',   'customer' => 'Benny Kusuma', 'status' => 'completed', 'grand_total' => 130000, 'date' => '17 Mei 2026, 14:10'],
        ['order_number' => 'PODS-20260517-JG0011', 'branch' => 'Jamin Ginting', 'customer' => 'Lina Hartati', 'status' => 'canceled',  'grand_total' => 45000,  'date' => '17 Mei 2026, 13:45'],
        ['order_number' => 'PODS-20260517-BB0001', 'branch' => 'Dr. Mansyur',   'customer' => 'Diana Putri',  'status' => 'paid',      'grand_total' => 78000,  'date' => '17 Mei 2026, 14:05'],
    ];

    $statusCfg = [
        'paid'      => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'dot' => '#2563EB', 'label' => 'Lunas'],
        'cooking'   => ['bg' => '#FEF3C7', 'text' => '#92400E', 'dot' => '#D97706', 'label' => 'Dimasak'],
        'completed' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#059669', 'label' => 'Selesai'],
        'canceled'  => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#DC2626', 'label' => 'Dibatalkan'],
    ];
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

    {{-- ================================================================
         FILTER PERIODE
    ================================================================ --}}
    <form method="GET" action="{{ route('admin.reports.sales') }}" id="sales-filter-form">
        <div class="adm-card adm-animate" style="padding:1rem 1.375rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:1.25rem;">
            <div style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="color:var(--pods-muted);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span style="font-size:0.8125rem; font-weight:600; color:var(--pods-espresso);">Periode:</span>
            </div>
            <div style="display:flex; gap:0.5rem;">
                @foreach(['Hari Ini' => [now()->toDateString(), now()->toDateString()], '7 Hari' => [now()->subDays(6)->toDateString(), now()->toDateString()], 'Bulan Ini' => [now()->startOfMonth()->toDateString(), now()->toDateString()]] as $label => [$from, $to])
                <button type="button" class="sales-preset-btn" data-from="{{ $from }}" data-to="{{ $to }}"
                    style="padding:0.4rem 0.875rem; border-radius:9999px; font-size:0.8125rem; font-weight:500; border:1.5px solid #D4C4AE; background:#FFFDF9; color:var(--pods-espresso); cursor:pointer; transition:all 0.15s;"
                    onmouseover="this.style.borderColor='#C8813B'" onmouseout="this.style.borderColor='#D4C4AE'">
                    {{ $label }}
                </button>
                @endforeach
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem; margin-left:auto;">
                <label for="from" class="sr-only">Dari</label>
                <input type="date" id="from" name="from" value="{{ request('from', now()->startOfMonth()->toDateString()) }}"
                    style="padding:0.4rem 0.75rem; border-radius:8px; border:1.5px solid #D4C4AE; background:#FFFDF9; font-size:0.8125rem; color:var(--pods-espresso); font-family:var(--font-sans);">
                <span style="font-size:0.8125rem; color:var(--pods-muted);">s/d</span>
                <label for="to" class="sr-only">Sampai</label>
                <input type="date" id="to" name="to" value="{{ request('to', now()->toDateString()) }}"
                    style="padding:0.4rem 0.75rem; border-radius:8px; border:1.5px solid #D4C4AE; background:#FFFDF9; font-size:0.8125rem; color:var(--pods-espresso); font-family:var(--font-sans);">
                <button type="submit" class="pods-btn-primary" style="font-size:0.8125rem; padding:0.4375rem 1rem;">Terapkan</button>
            </div>
        </div>
    </form>

    {{-- ================================================================
         SECTION 1: STAT CARDS GLOBAL
    ================================================================ --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem;">
        @foreach($globalSummary as $i => $stat)
        <div class="adm-card adm-animate" style="padding:1.25rem 1.375rem; animation-delay:{{ $i * 0.06 }}s;">
            <div style="font-size:1.5rem; margin-bottom:0.5rem;">{{ $stat['icon'] }}</div>
            <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.375rem;">{{ $stat['label'] }}</p>
            <p class="font-serif" style="font-size:1.25rem; font-weight:700; color:var(--pods-espresso); line-height:1.1; margin-bottom:0.25rem; font-variant-numeric:tabular-nums;">{{ $stat['value'] }}</p>
            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">{{ $stat['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ================================================================
         SECTION 2: TABEL PENDAPATAN PER CABANG
    ================================================================ --}}
    <div class="adm-card adm-animate" style="overflow:hidden; margin-bottom:1.5rem; animation-delay:0.24s;">
        <div style="padding:1.125rem 1.375rem 0.875rem; border-bottom:1px solid #F0E8DC;">
            <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.125rem;">Breakdown</p>
            <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:var(--pods-espresso);">Pendapatan per Cabang</h2>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;" role="table" aria-label="Tabel pendapatan per cabang">
                <thead>
                    <tr style="background:#FBF6EE;">
                        <th style="padding:0.625rem 1.375rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted);">Cabang</th>
                        <th style="padding:0.625rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); text-align:center;">Order Selesai</th>
                        <th style="padding:0.625rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); text-align:center;">Dibatalkan</th>
                        <th style="padding:0.625rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); text-align:right;">Revenue Bruto</th>
                        <th style="padding:0.625rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); text-align:right;">Total Diskon</th>
                        <th style="padding:0.625rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); text-align:right;">Revenue Bersih</th>
                        <th style="padding:0.625rem 1.375rem 0.625rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted);">Proporsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perBranch as $b)
                    @php $pct = round(($b['revenue'] / $totalRevenue) * 100); @endphp
                    <tr style="border-top:1px solid #F0E8DC; transition:background 0.15s;" onmouseover="this.style.background='#FFFBF4'" onmouseout="this.style.background='transparent'">
                        <td style="padding:1rem 1.375rem; font-size:0.9375rem; font-weight:600; color:var(--pods-espresso); white-space:nowrap;">{{ $b['branch'] }}</td>
                        <td style="padding:1rem; text-align:center; font-size:0.9375rem; font-weight:600; color:var(--pods-espresso); font-variant-numeric:tabular-nums;">{{ number_format($b['completed'], 0, ',', '.') }}</td>
                        <td style="padding:1rem; text-align:center; font-size:0.9375rem; font-weight:600; color:#DC2626; font-variant-numeric:tabular-nums;">{{ $b['canceled'] }}</td>
                        <td style="padding:1rem; text-align:right; font-size:0.875rem; font-weight:500; color:var(--pods-espresso); white-space:nowrap; font-variant-numeric:tabular-nums;">Rp {{ number_format($b['revenue'], 0, ',', '.') }}</td>
                        <td style="padding:1rem; text-align:right; font-size:0.875rem; font-weight:500; color:#059669; white-space:nowrap; font-variant-numeric:tabular-nums;">-Rp {{ number_format($b['discount'], 0, ',', '.') }}</td>
                        <td style="padding:1rem; text-align:right; font-size:0.9375rem; font-weight:700; color:var(--pods-espresso); white-space:nowrap; font-variant-numeric:tabular-nums;">Rp {{ number_format($b['net'], 0, ',', '.') }}</td>
                        <td style="padding:1rem 1.375rem 1rem 1rem; min-width:120px;">
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <div style="flex:1; height:6px; background:#EDE0CC; border-radius:9999px; overflow:hidden;">
                                    <div style="height:100%; width:{{ $pct }}%; background:var(--pods-caramel); border-radius:9999px;"></div>
                                </div>
                                <span style="font-size:0.75rem; font-weight:600; color:var(--pods-muted); white-space:nowrap; min-width:28px; text-align:right;">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- baris total --}}
                    <tr style="border-top:2px solid #EDE0CC; background:#FBF6EE;">
                        <td style="padding:0.875rem 1.375rem; font-size:0.875rem; font-weight:700; color:var(--pods-espresso);">TOTAL GLOBAL</td>
                        <td style="padding:0.875rem 1rem; text-align:center; font-size:0.9375rem; font-weight:700; color:var(--pods-espresso); font-variant-numeric:tabular-nums;">{{ number_format(array_sum(array_column($perBranch, 'completed')), 0, ',', '.') }}</td>
                        <td style="padding:0.875rem 1rem; text-align:center; font-size:0.9375rem; font-weight:700; color:#DC2626; font-variant-numeric:tabular-nums;">{{ array_sum(array_column($perBranch, 'canceled')) }}</td>
                        <td style="padding:0.875rem 1rem; text-align:right; font-size:0.9375rem; font-weight:700; color:var(--pods-espresso); white-space:nowrap; font-variant-numeric:tabular-nums;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                        <td style="padding:0.875rem 1rem; text-align:right; font-size:0.9375rem; font-weight:700; color:#059669; white-space:nowrap; font-variant-numeric:tabular-nums;">-Rp {{ number_format(array_sum(array_column($perBranch, 'discount')), 0, ',', '.') }}</td>
                        <td style="padding:0.875rem 1rem; text-align:right; font-size:0.9375rem; font-weight:700; color:var(--pods-caramel); white-space:nowrap; font-variant-numeric:tabular-nums;">Rp {{ number_format(array_sum(array_column($perBranch, 'net')), 0, ',', '.') }}</td>
                        <td style="padding:0.875rem 1.375rem 0.875rem 1rem;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================================================================
         SECTION 3: Transaksi Terbaru + Top Seller per Cabang
    ================================================================ --}}
    <div style="display:grid; grid-template-columns:1fr 340px; gap:1.25rem; align-items:flex-start;">

        {{-- TRANSAKSI LINTAS CABANG TERBARU --}}
        <div class="adm-card adm-animate" style="overflow:hidden; animation-delay:0.3s;">
            <div style="padding:1.125rem 1.375rem 0.875rem; border-bottom:1px solid #F0E8DC; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.125rem;">Lintas Cabang</p>
                    <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:var(--pods-espresso);">Transaksi Terbaru</h2>
                </div>
                <span style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">{{ count($recentTransactions) }} transaksi</span>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr style="background:#FBF6EE;">
                            <th style="padding:0.5rem 1.375rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); white-space:nowrap;">No. Order</th>
                            <th style="padding:0.5rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted);">Cabang</th>
                            <th style="padding:0.5rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted);">Status</th>
                            <th style="padding:0.5rem 1.375rem 0.5rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); text-align:right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $trx)
                        @php $sc = $statusCfg[$trx['status']] ?? ['bg' => '#F3F4F6', 'text' => '#374151', 'dot' => '#9CA3AF', 'label' => $trx['status']]; @endphp
                        <tr style="border-top:1px solid #F0E8DC; {{ $trx['status'] === 'canceled' ? 'opacity:0.6;' : '' }}" onmouseover="this.style.background='#FFFBF4'" onmouseout="this.style.background='transparent'">
                            <td style="padding:0.75rem 1.375rem; font-size:0.75rem; font-weight:600; color:var(--pods-espresso); font-family:'Courier New',monospace; white-space:nowrap;">{{ $trx['order_number'] }}</td>
                            <td style="padding:0.75rem 1rem;">
                                <p style="font-size:0.8125rem; font-weight:500; color:var(--pods-espresso); white-space:nowrap;">{{ $trx['branch'] }}</p>
                                <p style="font-size:0.6875rem; color:var(--pods-muted); font-weight:300;">{{ $trx['customer'] }}</p>
                            </td>
                            <td style="padding:0.75rem 1rem;">
                                <span style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px 2px 6px; border-radius:9999px; font-size:0.625rem; font-weight:600; background:{{ $sc['bg'] }}; color:{{ $sc['text'] }}; white-space:nowrap;">
                                    <span style="width:5px; height:5px; border-radius:9999px; background:{{ $sc['dot'] }};" aria-hidden="true"></span>
                                    {{ $sc['label'] }}
                                </span>
                            </td>
                            <td style="padding:0.75rem 1.375rem 0.75rem 1rem; font-size:0.875rem; font-weight:700; color:var(--pods-espresso); text-align:right; white-space:nowrap; font-variant-numeric:tabular-nums;">
                                Rp {{ number_format($trx['grand_total'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TOP SELLER PER CABANG --}}
        <div class="adm-card adm-animate" style="overflow:hidden; animation-delay:0.36s;">
            <div style="padding:1.125rem 1.375rem 0.75rem; border-bottom:1px solid #F0E8DC;">
                <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.125rem;">Bulan Ini · Top 3</p>
                <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:var(--pods-espresso);">Best Seller per Cabang</h2>
            </div>
            @foreach($topSellersPerBranch as $branchName => $sellers)
            <div style="{{ !$loop->last ? 'border-bottom:1px solid #F0E8DC;' : '' }} padding:0.875rem 1.375rem;">
                <p style="font-size:0.6875rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:var(--pods-caramel); margin-bottom:0.625rem;">{{ $branchName }}</p>
                @foreach($sellers as $idx => $s)
                <div style="display:flex; align-items:center; gap:0.625rem; padding:0.375rem 0; {{ $idx < count($sellers)-1 ? 'border-bottom:1px dashed #F0E8DC;' : '' }}">
                    <span style="width:18px; height:18px; border-radius:50%; background:{{ $idx === 0 ? 'var(--pods-caramel)' : '#EDE0CC' }}; color:{{ $idx === 0 ? '#1C0F0A' : 'var(--pods-muted)' }}; font-size:0.625rem; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;">{{ $idx + 1 }}</span>
                    <p style="flex:1; font-size:0.8125rem; font-weight:500; color:var(--pods-espresso); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $s['name'] }}</p>
                    <span style="font-size:0.75rem; font-weight:600; color:var(--pods-muted); white-space:nowrap; font-variant-numeric:tabular-nums;">{{ $s['qty'] }} pcs</span>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

    </div>

</div>

@push('scripts')
<script>
(function () {
    document.querySelectorAll('.sales-preset-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('from').value = btn.dataset.from;
            document.getElementById('to').value   = btn.dataset.to;
            document.getElementById('sales-filter-form').submit();
        });
    });
}());
</script>
@endpush

@endsection