{{-- MANAGER REPORT: laporan penjualan & transaksi cabang --}}
@extends('manager.layouts.app')

@section('title', "Laporan Penjualan — Pod's Manager")
@section('page-title', 'Laporan Penjualan')

@section('content')

@php
    /* data dummy: statistik laporan periode ini */
    $periodStats = [
        ['label' => 'Total Pendapatan',     'value' => 'Rp 48.720.000', 'sub' => 'Bulan ini',       'icon' => '💰'],
        ['label' => 'Total Transaksi',      'value' => '634',            'sub' => 'Pesanan selesai', 'icon' => '🧾'],
        ['label' => 'Rata-rata Per Pesanan','value' => 'Rp 76.846',      'sub' => 'Per transaksi',   'icon' => '📊'],
        ['label' => 'Pesanan Dibatalkan',   'value' => '12',             'sub' => '1.9% dari total', 'icon' => '❌'],
    ];

    /* tabel transaksi terbaru */
    $transactions = [
        ['order_number' => 'ORD-2026-0047', 'customer' => 'Andi Wijaya',   'items_count' => 3, 'promo' => 'Happy Hour',  'subtotal' => 115000, 'discount' => 20000,  'grand_total' => 95000,  'status' => 'completed', 'date' => '16 Mei 2026, 14:22'],
        ['order_number' => 'ORD-2026-0046', 'customer' => 'Sari Dewi',     'items_count' => 2, 'promo' => null,          'subtotal' => 52000,  'discount' => 0,      'grand_total' => 52000,  'status' => 'completed', 'date' => '16 Mei 2026, 14:18'],
        ['order_number' => 'ORD-2026-0045', 'customer' => 'Benny Kusuma',  'items_count' => 4, 'promo' => 'Weekend Deal','subtotal' => 156000, 'discount' => 26000,  'grand_total' => 130000, 'status' => 'completed', 'date' => '16 Mei 2026, 14:10'],
        ['order_number' => 'ORD-2026-0044', 'customer' => 'Diana Putri',   'items_count' => 2, 'promo' => null,          'subtotal' => 78000,  'discount' => 0,      'grand_total' => 78000,  'status' => 'completed', 'date' => '16 Mei 2026, 14:05'],
        ['order_number' => 'ORD-2026-0043', 'customer' => 'Rizky Hamdani', 'items_count' => 1, 'promo' => null,          'subtotal' => 45000,  'discount' => 0,      'grand_total' => 45000,  'status' => 'canceled',  'date' => '16 Mei 2026, 13:58'],
        ['order_number' => 'ORD-2026-0042', 'customer' => 'Lina Hartati',  'items_count' => 2, 'promo' => 'Happy Hour',  'subtotal' => 75000,  'discount' => 12000,  'grand_total' => 63000,  'status' => 'completed', 'date' => '16 Mei 2026, 13:31'],
        ['order_number' => 'ORD-2026-0041', 'customer' => 'Fajar Nugroho', 'items_count' => 3, 'promo' => null,          'subtotal' => 92000,  'discount' => 0,      'grand_total' => 92000,  'status' => 'completed', 'date' => '16 Mei 2026, 13:15'],
    ];

    /* top seller bulan ini */
    $topSellers = [
        ['rank' => 1, 'name' => 'Caramel Macchiato',   'qty' => 218, 'revenue' => 5668000],
        ['rank' => 2, 'name' => 'Iced Americano',       'qty' => 187, 'revenue' => 3927000],
        ['rank' => 3, 'name' => 'Brown Sugar Latte',    'qty' => 156, 'revenue' => 4524000],
        ['rank' => 4, 'name' => 'Croissant Almond',     'qty' => 98,  'revenue' => 2352000],
        ['rank' => 5, 'name' => 'Matcha Latte',         'qty' => 87,  'revenue' => 2523000],
    ];

    /* badge status untuk tabel */
    $badgeCfg = [
        'completed' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#059669', 'label' => 'Selesai'],
        'canceled'  => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#DC2626', 'label' => 'Dibatalkan'],
    ];
@endphp

<div style="padding: 2rem; background: #F0E8DC; min-height: calc(100vh - 64px);">

    {{-- ================================================================
         SECTION 1: FILTER PERIODE
    ================================================================ --}}
    <div class="mgr-card mgr-animate" style="padding: 1.125rem 1.375rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1.25rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="color: var(--pods-muted);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span style="font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); white-space: nowrap;">Filter Periode:</span>
        </div>

        {{-- tombol preset periode --}}
        <div style="display: flex; gap: 0.5rem;">
            @foreach(['Hari Ini', '7 Hari', '30 Hari', 'Bulan Ini'] as $i => $preset)
            <button
                type="button"
                class="report-period-btn {{ $i === 3 ? 'report-period-active' : '' }}"
                style="padding: 0.4375rem 0.875rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: {{ $i === 3 ? '600' : '500' }}; border: 1.5px solid {{ $i === 3 ? '#C8813B' : '#D4C4AE' }}; background: {{ $i === 3 ? '#C8813B' : '#FFFDF9' }}; color: {{ $i === 3 ? '#1C0F0A' : 'var(--pods-espresso)' }}; cursor: pointer; transition: all 0.15s;"
            >
                {{ $preset }}
            </button>
            @endforeach
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem; margin-left: auto;">
            <label for="report-date-from" class="sr-only">Dari tanggal</label>
            <input
                type="date"
                id="report-date-from"
                value="2026-05-01"
                style="padding: 0.4375rem 0.75rem; border-radius: 8px; border: 1.5px solid #D4C4AE; background: #FFFDF9; font-size: 0.8125rem; color: var(--pods-espresso); font-family: var(--font-sans); cursor: pointer;"
            >
            <span style="font-size: 0.8125rem; color: var(--pods-muted);">s/d</span>
            <label for="report-date-to" class="sr-only">Sampai tanggal</label>
            <input
                type="date"
                id="report-date-to"
                value="2026-05-16"
                style="padding: 0.4375rem 0.75rem; border-radius: 8px; border: 1.5px solid #D4C4AE; background: #FFFDF9; font-size: 0.8125rem; color: var(--pods-espresso); font-family: var(--font-sans); cursor: pointer;"
            >
            <button type="button" class="pods-btn-primary" style="font-size: 0.8125rem; padding: 0.4375rem 1rem;">
                Terapkan
            </button>
        </div>
    </div>

    {{-- ================================================================
         SECTION 2: STAT CARDS PERIODE
    ================================================================ --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
        @foreach($periodStats as $i => $stat)
        <div class="mgr-card mgr-animate" style="padding: 1.25rem 1.375rem; animation-delay: {{ $i * 0.06 }}s;">
            <div style="font-size: 1.625rem; margin-bottom: 0.625rem; line-height: 1;">{{ $stat['icon'] }}</div>
            <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted); margin-bottom: 0.375rem;">{{ $stat['label'] }}</p>
            <p class="font-serif" style="font-size: 1.375rem; font-weight: 700; color: var(--pods-espresso); line-height: 1.1; margin-bottom: 0.25rem; font-variant-numeric: tabular-nums;">{{ $stat['value'] }}</p>
            <p style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300;">{{ $stat['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ================================================================
         SECTION 3: Tabel Transaksi + Top Seller
    ================================================================ --}}
    <div style="display: grid; grid-template-columns: 1fr 320px; gap: 1rem; align-items: flex-start;">

        {{-- TABEL TRANSAKSI --}}
        <div class="mgr-card mgr-animate" style="animation-delay: 0.24s; overflow: hidden;">
            <div style="padding: 1.125rem 1.375rem 0.875rem; border-bottom: 1px solid #F0E8DC; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--pods-muted); margin-bottom: 0.125rem;">Riwayat</p>
                    <h2 class="font-serif" style="font-size: 1rem; font-weight: 700; color: var(--pods-espresso);">Daftar Transaksi</h2>
                </div>
                <span style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300;">
                    {{ count($transactions) }} transaksi ditampilkan
                </span>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;" role="table" aria-label="Tabel daftar transaksi">
                    <thead>
                        <tr style="background: #FBF6EE;">
                            <th style="padding: 0.625rem 1.375rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted); white-space: nowrap;">No. Order</th>
                            <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted);">Pelanggan</th>
                            <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted); text-align: center;">Item</th>
                            <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted);">Promo</th>
                            <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted); text-align: right;">Diskon</th>
                            <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted); text-align: right;">Grand Total</th>
                            <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted);">Status</th>
                            <th style="padding: 0.625rem 1.375rem 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted); white-space: nowrap;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $trx)
                        @php $bc = $badgeCfg[$trx['status']] ?? ['bg' => '#F3F4F6', 'text' => '#374151', 'dot' => '#9CA3AF', 'label' => $trx['status']]; @endphp
                        <tr
                            style="border-top: 1px solid #F0E8DC; {{ $trx['status'] === 'canceled' ? 'opacity: 0.65;' : '' }}"
                            onmouseover="this.style.background='#FFFBF4'"
                            onmouseout="this.style.background='transparent'"
                        >
                            <td style="padding: 0.875rem 1.375rem; font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); white-space: nowrap; font-variant-numeric: tabular-nums;">{{ $trx['order_number'] }}</td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.875rem; color: var(--pods-espresso); white-space: nowrap;">{{ $trx['customer'] }}</td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.875rem; color: var(--pods-muted); text-align: center;">{{ $trx['items_count'] }}</td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.8125rem;">
                                @if($trx['promo'])
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; background: rgba(200,129,59,0.1); border: 1px solid rgba(200,129,59,0.2); font-size: 0.6875rem; font-weight: 600; color: #92400E; white-space: nowrap;">
                                    {{ $trx['promo'] }}
                                </span>
                                @else
                                <span style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300;">—</span>
                                @endif
                            </td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.875rem; font-weight: 500; color: {{ $trx['discount'] > 0 ? '#059669' : 'var(--pods-muted)' }}; text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap;">
                                {{ $trx['discount'] > 0 ? '-Rp ' . number_format($trx['discount'], 0, ',', '.') : '—' }}
                            </td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.9375rem; font-weight: 700; color: var(--pods-espresso); text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap;">
                                Rp {{ number_format($trx['grand_total'], 0, ',', '.') }}
                            </td>
                            <td style="padding: 0.875rem 1rem;">
                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 9px 2px 7px; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: {{ $bc['bg'] }}; color: {{ $bc['text'] }}; white-space: nowrap;">
                                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: {{ $bc['dot'] }};" aria-hidden="true"></span>
                                    {{ $bc['label'] }}
                                </span>
                            </td>
                            <td style="padding: 0.875rem 1.375rem 0.875rem 1rem; font-size: 0.75rem; color: var(--pods-muted); font-weight: 300; white-space: nowrap;">{{ $trx['date'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TOP SELLER KOLOM KANAN --}}
        <div class="mgr-card mgr-animate" style="animation-delay: 0.3s; overflow: hidden;">
            <div style="padding: 1.125rem 1.375rem 0.75rem; border-bottom: 1px solid #F0E8DC;">
                <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--pods-muted); margin-bottom: 0.125rem;">Bulan Ini</p>
                <h2 class="font-serif" style="font-size: 1rem; font-weight: 700; color: var(--pods-espresso);">Top Seller</h2>
            </div>
            <ul style="list-style: none; margin: 0; padding: 0.5rem 0;" role="list">
                @foreach($topSellers as $ts)
                @php
                    /* lebar bar relatif terhadap top 1 */
                    $maxQty  = $topSellers[0]['qty'];
                    $barPct  = round(($ts['qty'] / $maxQty) * 100);
                @endphp
                <li style="padding: 0.875rem 1.375rem; {{ !$loop->last ? 'border-bottom: 1px solid #F8F0E6;' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                        <span style="width: 22px; height: 22px; border-radius: 50%; background: {{ $ts['rank'] === 1 ? 'var(--pods-caramel)' : '#EDE0CC' }}; color: {{ $ts['rank'] === 1 ? '#1C0F0A' : 'var(--pods-muted)' }}; font-size: 0.6875rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">{{ $ts['rank'] }}</span>
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 500; color: var(--pods-espresso); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ts['name'] }}</p>
                        </div>
                        <span style="font-size: 0.8125rem; font-weight: 700; color: var(--pods-espresso); white-space: nowrap; font-variant-numeric: tabular-nums; flex-shrink: 0;">{{ $ts['qty'] }} pcs</span>
                    </div>
                    {{-- progress bar relatif --}}
                    <div style="height: 4px; background: #EDE0CC; border-radius: 9999px; overflow: hidden; margin-left: 2.5rem;">
                        <div style="height: 100%; width: {{ $barPct }}%; background: {{ $ts['rank'] === 1 ? 'var(--pods-caramel)' : '#D4C4AE' }}; border-radius: 9999px;"></div>
                    </div>
                    <p style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300; margin-top: 0.25rem; margin-left: 2.5rem; font-variant-numeric: tabular-nums;">
                        Rp {{ number_format($ts['revenue'], 0, ',', '.') }}
                    </p>
                </li>
                @endforeach
            </ul>
        </div>

    </div>

</div>

@endsection