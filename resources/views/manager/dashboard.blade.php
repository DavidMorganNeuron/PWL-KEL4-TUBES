{{-- MANAGER DASHBOARD --}}
@extends('manager.layouts.app')

@section('title', "Dashboard — Pod's Manager")
@section('page-title', 'Dashboard')

@section('content')

@php
    /* konfigurasi visual stat card: nilai diambil dari variabel controller */
    $statCards = [
        [
            'label'  => 'Pendapatan Hari Ini',
            'value'  => 'Rp ' . number_format($revenue, 0, ',', '.'),
            'delta'  => 'Dari pesanan selesai hari ini',
            'up'     => null,
            'accent' => '#C8813B',
            'bg'     => 'rgba(200,129,59,0.08)',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 8v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        ],
        [
            'label'  => 'Total Pesanan',
            'value'  => $totalOrders . ' Pesanan',
            'delta'  => 'Masuk hari ini',
            'up'     => null,
            'accent' => '#2563EB',
            'bg'     => 'rgba(37,99,235,0.07)',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
        ],
        [
            'label'  => 'Pesanan Aktif',
            'value'  => $activeOrders . ' Antrean',
            'delta'  => $paidCount . ' paid · ' . $cookingCount . ' cooking',
            'up'     => null,
            'accent' => '#7C3AED',
            'bg'     => 'rgba(124,58,237,0.07)',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
        ],
        [
            'label'  => 'Stok Kritis',
            'value'  => $criticalStocks->count() . ' Produk',
            'delta'  => $criticalStocks->count() > 0 ? 'Perlu restock segera' : 'Semua stok aman',
            'up'     => $criticalStocks->count() > 0 ? false : null,
            'accent' => '#DC2626',
            'bg'     => 'rgba(220,38,38,0.07)',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
        ],
    ];

    $badgeCfg = [
        'paid'      => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'dot' => '#2563EB', 'label' => 'Lunas'],
        'cooking'   => ['bg' => '#FEF3C7', 'text' => '#92400E', 'dot' => '#D97706', 'label' => 'Dimasak', 'pulse' => true],
        'completed' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#059669', 'label' => 'Selesai'],
        'canceled'  => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#DC2626', 'label' => 'Dibatalkan'],
    ];
@endphp

<div style="padding: 2rem; background: #F0E8DC; min-height: calc(100vh - 64px);">
<div style="max-width: 1024px;">

    {{-- ================================================================
         SECTION 1: STAT CARDS
    ================================================================ --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.75rem;">
        @foreach($statCards as $i => $stat)
        <div class="mgr-card mgr-animate" style="padding: 1.25rem 1.375rem; animation-delay: {{ $i * 0.06 }}s;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 0.875rem;">
                <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: var(--pods-muted); line-height: 1.3; max-width: 110px;">
                    {{ $stat['label'] }}
                </p>
                <div style="width: 34px; height: 34px; border-radius: 8px; background: {{ $stat['bg'] }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="{{ $stat['accent'] }}" stroke-width="2" aria-hidden="true">
                        {!! $stat['icon'] !!}
                    </svg>
                </div>
            </div>
            <p class="font-serif" style="font-size: 1.375rem; font-weight: 700; color: var(--pods-espresso); line-height: 1.1; margin-bottom: 0.375rem; font-variant-numeric: tabular-nums;">
                {{ $stat['value'] }}
            </p>
            <p style="font-size: 0.75rem; font-weight: 400; color: {{ $stat['up'] === true ? '#059669' : ($stat['up'] === false ? '#DC2626' : 'var(--pods-muted)') }};">
                @if($stat['up'] === true) ↑ @elseif($stat['up'] === false) ↓ @endif
                {{ $stat['delta'] }}
            </p>
        </div>
        @endforeach
    </div>

    {{-- ================================================================
         SECTION 2: Best Seller + Stok Kritis
    ================================================================ --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.75rem;">

        {{-- BEST SELLER HARI INI --}}
        <div class="mgr-card mgr-animate" style="animation-delay: 0.24s; overflow: hidden;">
            <div style="padding: 1.125rem 1.375rem 0.75rem; border-bottom: 1px solid #F0E8DC; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--pods-muted); margin-bottom: 0.125rem;">Hari Ini</p>
                    <h2 class="font-serif" style="font-size: 1rem; font-weight: 700; color: var(--pods-espresso);">Best Seller</h2>
                </div>
                <a href="{{ route('manager.report') }}" style="font-size: 0.75rem; color: var(--pods-caramel); font-weight: 500; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                    Lihat Laporan →
                </a>
            </div>

            @if($bestSellers->isNotEmpty())
            <ul style="list-style: none; margin: 0; padding: 0.5rem 0;" role="list">
                @foreach($bestSellers as $item)
                <li style="display: flex; align-items: center; gap: 0.875rem; padding: 0.625rem 1.375rem; {{ !$loop->last ? 'border-bottom: 1px solid #F8F0E6;' : '' }}">
                    <span style="width: 22px; height: 22px; border-radius: 50%; background: {{ $loop->first ? 'var(--pods-caramel)' : '#EDE0CC' }}; color: {{ $loop->first ? '#1C0F0A' : 'var(--pods-muted)' }}; font-size: 0.6875rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        {{ $loop->iteration }}
                    </span>
                    <div style="flex: 1; min-width: 0;">
                        <p style="font-size: 0.875rem; font-weight: 500; color: var(--pods-espresso); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->name }}</p>
                        <p style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300;">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</p>
                    </div>
                    <span style="font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); white-space: nowrap; font-variant-numeric: tabular-nums;">{{ $item->total_qty }} pcs</span>
                </li>
                @endforeach
            </ul>
            @else
            <div style="padding: 2.5rem 1.375rem; text-align: center;">
                <p style="font-size: 0.875rem; color: var(--pods-muted); font-weight: 300;">Belum ada pesanan selesai hari ini.</p>
            </div>
            @endif
        </div>

        {{-- STOK KRITIS --}}
        <div class="mgr-card mgr-animate" style="animation-delay: 0.3s; overflow: hidden;">
            <div style="padding: 1.125rem 1.375rem 0.75rem; border-bottom: 1px solid #F0E8DC; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: #DC2626; margin-bottom: 0.125rem;">Perlu Perhatian</p>
                    <h2 class="font-serif" style="font-size: 1rem; font-weight: 700; color: var(--pods-espresso);">Stok Kritis</h2>
                </div>
                <a href="{{ route('manager.request_form') }}" class="pods-btn-primary" style="font-size: 0.75rem; padding: 0.4375rem 0.875rem;">
                    + Ajukan Restock
                </a>
            </div>

            @if($criticalStocks->isNotEmpty())
            <ul style="list-style: none; margin: 0; padding: 0.5rem 0;" role="list" aria-label="Daftar stok kritis">
                @foreach($criticalStocks as $stk)
                <li style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1.375rem; {{ !$loop->last ? 'border-bottom: 1px solid #F8F0E6;' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.625rem;">
                        <span style="width: 7px; height: 7px; border-radius: 9999px; background: #DC2626; flex-shrink: 0; animation: kds-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;" aria-hidden="true"></span>
                        <p style="font-size: 0.875rem; font-weight: 500; color: var(--pods-espresso);">{{ $stk->name }}</p>
                    </div>
                    {{-- criticalStocks dari DB::table tidak punya kolom unit — tampilkan physical_qty saja --}}
                    <span style="font-size: 0.875rem; font-weight: 700; color: #DC2626; font-variant-numeric: tabular-nums;">
                        {{ $stk->physical_qty }}
                    </span>
                </li>
                @endforeach
            </ul>
            <div style="padding: 0.75rem 1.375rem; background: #FFF8F0; border-top: 1px solid #F0E8DC;">
                <p style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300;">
                    Ajukan restock ke Admin Pusat agar stok segera dipulihkan.
                </p>
            </div>
            @else
            <div style="padding: 2.5rem 1.375rem; text-align: center;">
                <p style="font-size: 0.875rem; color: var(--pods-muted); font-weight: 300;">Semua stok dalam kondisi aman ✓</p>
            </div>
            @endif
        </div>

    </div>

    {{-- ================================================================
         SECTION 3: GRAFIK PENDAPATAN
    ================================================================ --}}
    <div class="mgr-card mgr-animate" style="animation-delay: 0.36s; overflow: hidden; margin-bottom: 1.75rem;">

        <div style="padding: 1.125rem 1.375rem 0.875rem; border-bottom: 1px solid #F0E8DC; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--pods-muted); margin-bottom: 0.125rem;">Tren Cabang</p>
                <h2 class="font-serif" style="font-size: 1rem; font-weight: 700; color: var(--pods-espresso);">Pendapatan Bersih</h2>
            </div>

            {{-- toggle periode --}}
            <div style="display: flex; gap: 0.375rem;" role="group" aria-label="Pilih periode grafik">
                @foreach(['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan'] as $key => $label)
                <button
                    type="button"
                    class="chart-period-btn {{ $key === 'daily' ? 'chart-period-active' : '' }}"
                    data-period="{{ $key }}"
                    style="padding: 0.375rem 0.875rem; border-radius: 9999px; font-size: 0.75rem; font-weight: {{ $key === 'daily' ? '600' : '500' }}; border: 1.5px solid {{ $key === 'daily' ? '#C8813B' : '#D4C4AE' }}; background: {{ $key === 'daily' ? '#C8813B' : 'transparent' }}; color: {{ $key === 'daily' ? '#1C0F0A' : 'var(--pods-muted)' }}; cursor: pointer; transition: all 0.15s;"
                >
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>

        <div style="padding: 1.25rem 1.375rem 1rem; position: relative; height: 240px;">
            <canvas id="revenue-chart" style="width: 100%; height: 100%;" aria-label="Grafik pendapatan bersih cabang" role="img"></canvas>
        </div>

        {{-- keterangan: pendapatan bersih = revenue - waste --}}
        <div style="padding: 0.75rem 1.375rem; background: #FBF6EE; border-top: 1px solid #F0E8DC; display: flex; align-items: center; gap: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 12px; height: 4px; border-radius: 9999px; background: #C8813B; display: inline-block;"></span>
                <span style="font-size: 0.75rem; color: var(--pods-muted);">Pendapatan Bersih (revenue selesai − kerugian waste)</span>
            </div>
        </div>
    </div>

    {{-- ================================================================
         SECTION 4: AKTIVITAS PESANAN TERBARU
    ================================================================ --}}
    <div class="mgr-card mgr-animate" style="animation-delay: 0.36s; overflow: hidden;">
        <div style="padding: 1.125rem 1.375rem 0.75rem; border-bottom: 1px solid #F0E8DC; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--pods-muted); margin-bottom: 0.125rem;">Real-time</p>
                <h2 class="font-serif" style="font-size: 1rem; font-weight: 700; color: var(--pods-espresso);">Aktivitas Pesanan Terbaru</h2>
            </div>
            <a href="{{ route('manager.kds') }}" style="font-size: 0.75rem; color: var(--pods-caramel); font-weight: 500; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                Buka KDS →
            </a>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;" role="table" aria-label="Tabel aktivitas pesanan terbaru">
                <thead>
                    <tr style="background: #FBF6EE;">
                        <th style="padding: 0.625rem 1.375rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted); white-space: nowrap;">No. Pesanan</th>
                        <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted);">Pelanggan</th>
                        <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted);">Status</th>
                        <th style="padding: 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted); text-align: right;">Total</th>
                        <th style="padding: 0.625rem 1.375rem 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted); text-align: right;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    @php $bc = $badgeCfg[$order->status] ?? ['bg' => '#F3F4F6', 'text' => '#374151', 'dot' => '#9CA3AF', 'label' => $order->status]; @endphp
                    <tr style="border-top: 1px solid #F0E8DC;" onmouseover="this.style.background='#FFFBF4'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 0.875rem 1.375rem; font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); white-space: nowrap; font-variant-numeric: tabular-nums;">{{ $order->order_number }}</td>
                        <td style="padding: 0.875rem 1rem; font-size: 0.875rem; color: var(--pods-espresso);">{{ $order->user->name ?? '—' }}</td>
                        <td style="padding: 0.875rem 1rem;">
                            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px 3px 7px; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: {{ $bc['bg'] }}; color: {{ $bc['text'] }}; white-space: nowrap;">
                                <span style="width: 5px; height: 5px; border-radius: 9999px; background: {{ $bc['dot'] }}; {{ isset($bc['pulse']) && $bc['pulse'] ? 'animation: kds-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;' : '' }}" aria-hidden="true"></span>
                                {{ $bc['label'] }}
                            </span>
                        </td>
                        <td style="padding: 0.875rem 1rem; font-size: 0.875rem; font-weight: 600; color: var(--pods-espresso); text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        <td style="padding: 0.875rem 1.375rem 0.875rem 1rem; font-size: 0.8125rem; color: var(--pods-muted); text-align: right; font-variant-numeric: tabular-nums;">{{ $order->created_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; font-size: 0.875rem; color: var(--pods-muted); font-weight: 300;">Belum ada aktivitas pesanan hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

@push('head-scripts')
<style>
    @keyframes kds-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.4; transform: scale(0.85); }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@push('scripts')
<script>
(function () {
    /* DATA GRAFIK DARI CONTROLLER */
    var chartDatasets = @json($chartData);

    var ctx = document.getElementById('revenue-chart');
    if (!ctx) return;

    var chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels:   chartDatasets.daily.labels,
            datasets: [{
                label:           'Pendapatan Bersih',
                data:            chartDatasets.daily.data,
                borderColor:     '#C8813B',
                backgroundColor: 'rgba(200,129,59,0.08)',
                borderWidth:     2.5,
                pointRadius:     3,
                pointHoverRadius:6,
                pointBackgroundColor: '#C8813B',
                tension:         0.35,
                fill:            true,
            }],
        },
        options: {
            responsive:          true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1C0F0A',
                    titleColor:      'rgba(245,233,211,0.6)',
                    bodyColor:       '#F5E9D3',
                    padding:         10,
                    cornerRadius:    8,
                    callbacks: {
                        label: function (ctx) {
                            /* format angka ke Rupiah */
                            return ' Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID');
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid:  { color: 'rgba(160,128,96,0.1)' },
                    ticks: { color: '#A08060', font: { size: 11 } },
                },
                y: {
                    grid:  { color: 'rgba(160,128,96,0.1)' },
                    ticks: {
                        color: '#A08060',
                        font:  { size: 11 },
                        callback: function (val) {
                            if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                            if (val >= 1000)    return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                            return 'Rp ' + val;
                        },
                    },
                    beginAtZero: true,
                },
            },
        },
    });

    /* TOGGLE PERIODE */
    document.querySelectorAll('.chart-period-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var period = btn.dataset.period;
            var ds     = chartDatasets[period];
            if (!ds) return;

            /* update data chart */
            chartInstance.data.labels            = ds.labels;
            chartInstance.data.datasets[0].data  = ds.data;
            chartInstance.update('active');

            /* gaya tombol aktif */
            document.querySelectorAll('.chart-period-btn').forEach(function (b) {
                b.classList.remove('chart-period-active');
                b.style.background   = 'transparent';
                b.style.borderColor  = '#D4C4AE';
                b.style.color        = 'var(--pods-muted)';
                b.style.fontWeight   = '500';
            });
            btn.classList.add('chart-period-active');
            btn.style.background  = '#C8813B';
            btn.style.borderColor = '#C8813B';
            btn.style.color       = '#1C0F0A';
            btn.style.fontWeight  = '600';
        });
    });
}());
</script>
@endpush

@endsection