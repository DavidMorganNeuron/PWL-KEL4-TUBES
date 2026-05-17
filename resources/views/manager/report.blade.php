{{-- MANAGER REPORT --}}
@extends('manager.layouts.app')

@section('title', "Laporan Penjualan — Pod's Manager")
@section('page-title', 'Laporan Penjualan')

@section('content')

@php
    $periodStats = [
        ['label' => 'Total Pendapatan',      'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'sub' => 'Periode terpilih',   'icon' => '💰'],
        ['label' => 'Pesanan Selesai',        'value' => $totalCompleted,                                   'sub' => 'Transaksi berhasil', 'icon' => '🧾'],
        ['label' => 'Rata-rata Per Pesanan',  'value' => 'Rp ' . number_format($avgPerOrder, 0, ',', '.'), 'sub' => 'Per transaksi',      'icon' => '📊'],
        ['label' => 'Pesanan Dibatalkan',     'value' => $totalCanceled,                                    'sub' => $totalCompleted + $totalCanceled > 0 ? round($totalCanceled / ($totalCompleted + $totalCanceled) * 100, 1) . '% dari total' : '—', 'icon' => '❌'],
    ];

    $badgeCfg = [
        'completed' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#059669', 'label' => 'Selesai'],
        'canceled'  => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#DC2626', 'label' => 'Dibatalkan'],
        'paid'      => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'dot' => '#2563EB', 'label' => 'Lunas'],
        'cooking'   => ['bg' => '#FEF3C7', 'text' => '#92400E', 'dot' => '#D97706', 'label' => 'Dimasak'],
    ];
@endphp

<div style="padding: 2rem; background: #F0E8DC; min-height: calc(100vh - 64px);">

    {{-- ================================================================
         SECTION 1: FILTER PERIODE
    ================================================================ --}}
    <form method="GET" action="{{ route('manager.report') }}" id="report-filter-form">
    <div class="mgr-card mgr-animate" style="padding: 1.125rem 1.375rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1.25rem;">

        <div style="display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="color: var(--pods-muted);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span style="font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); white-space: nowrap;">Filter Periode:</span>
        </div>

        {{-- tombol preset --}}
        <div style="display: flex; gap: 0.5rem;">
            @php
                $presets = [
                    'Hari Ini' => [now()->toDateString(), now()->toDateString()],
                    '7 Hari'   => [now()->subDays(6)->toDateString(), now()->toDateString()],
                    '30 Hari'  => [now()->subDays(29)->toDateString(), now()->toDateString()],
                    'Bulan Ini'=> [now()->startOfMonth()->toDateString(), now()->toDateString()],
                ];
            @endphp
            @foreach($presets as $label => [$presetFrom, $presetTo])
            <button
                type="button"
                class="report-preset-btn"
                data-from="{{ $presetFrom }}"
                data-to="{{ $presetTo }}"
                style="padding: 0.4375rem 0.875rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 500; border: 1.5px solid #D4C4AE; background: #FFFDF9; color: var(--pods-espresso); cursor: pointer; transition: all 0.15s;"
                onmouseover="this.style.borderColor='#C8813B'"
                onmouseout="this.style.borderColor='#D4C4AE'"
            >
                {{ $label }}
            </button>
            @endforeach
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem; margin-left: auto;">
            <label for="report-date-from" class="sr-only">Dari tanggal</label>
            <input
                type="date"
                id="report-date-from"
                name="from"
                value="{{ $from }}"
                style="padding: 0.4375rem 0.75rem; border-radius: 8px; border: 1.5px solid #D4C4AE; background: #FFFDF9; font-size: 0.8125rem; color: var(--pods-espresso); font-family: var(--font-sans); cursor: pointer;"
            >
            <span style="font-size: 0.8125rem; color: var(--pods-muted);">s/d</span>
            <label for="report-date-to" class="sr-only">Sampai tanggal</label>
            <input
                type="date"
                id="report-date-to"
                name="to"
                value="{{ $to }}"
                style="padding: 0.4375rem 0.75rem; border-radius: 8px; border: 1.5px solid #D4C4AE; background: #FFFDF9; font-size: 0.8125rem; color: var(--pods-espresso); font-family: var(--font-sans); cursor: pointer;"
            >
            <button type="submit" class="pods-btn-primary" style="font-size: 0.8125rem; padding: 0.4375rem 1rem;">
                Terapkan
            </button>
        </div>

    </div>
    </form>

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
                    {{ $transactions->total() }} transaksi ditemukan
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
                            <th style="padding: 0.625rem 1.375rem 0.625rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--pods-muted);">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        @php $bc = $badgeCfg[$trx->status] ?? ['bg' => '#F3F4F6', 'text' => '#374151', 'dot' => '#9CA3AF', 'label' => $trx->status]; @endphp
                        <tr
                            style="border-top: 1px solid #F0E8DC; {{ $trx->status === 'canceled' ? 'opacity: 0.75;' : '' }} cursor: pointer;"
                            onmouseover="this.style.background='#FFFBF4'"
                            onmouseout="this.style.background='transparent'"
                            onclick="openTrxDetail({{ $trx->id_orders }})"
                            role="button"
                            aria-label="Lihat detail pesanan {{ $trx->order_number }}"
                        >
                            <td style="padding: 0.875rem 1.375rem; font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); white-space: nowrap; font-variant-numeric: tabular-nums;">{{ $trx->order_number }}</td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.875rem; color: var(--pods-espresso); white-space: nowrap;">{{ $trx->user->name ?? '—' }}</td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.875rem; color: var(--pods-muted); text-align: center;">{{ $trx->items->count() }}</td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.8125rem;">
                                @if($trx->promo)
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; background: rgba(200,129,59,0.1); border: 1px solid rgba(200,129,59,0.2); font-size: 0.6875rem; font-weight: 600; color: #92400E; white-space: nowrap;">
                                    {{ $trx->promo->name }}
                                </span>
                                @else
                                <span style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300;">—</span>
                                @endif
                            </td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.875rem; font-weight: 500; color: {{ $trx->total_discount > 0 ? '#059669' : 'var(--pods-muted)' }}; text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap;">
                                {{ $trx->total_discount > 0 ? '-Rp ' . number_format($trx->total_discount, 0, ',', '.') : '—' }}
                            </td>
                            <td style="padding: 0.875rem 1rem; font-size: 0.9375rem; font-weight: 700; color: var(--pods-espresso); text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap;">
                                Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                            </td>
                            <td style="padding: 0.875rem 1rem;">
                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 9px 2px 7px; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: {{ $bc['bg'] }}; color: {{ $bc['text'] }}; white-space: nowrap;">
                                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: {{ $bc['dot'] }};" aria-hidden="true"></span>
                                    {{ $bc['label'] }}
                                </span>
                            </td>
                            <td style="padding: 0.875rem 1.375rem 0.875rem 1rem; font-size: 0.75rem; color: var(--pods-muted); font-weight: 300; white-space: nowrap;">
                                {{ $trx->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td style="padding: 0.875rem 1.375rem 0.875rem 1rem;">
                                <span style="font-size: 0.8125rem; font-weight: 500; color: var(--pods-caramel); text-decoration: underline; white-space: nowrap;">Detail →</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="padding: 2.5rem; text-align: center; font-size: 0.875rem; color: var(--pods-muted); font-weight: 300;">
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($transactions->hasPages())
            <div style="padding: 0.875rem 1.375rem; border-top: 1px solid #F0E8DC; display: flex; justify-content: center;">
                {{ $transactions->appends(['from' => $from, 'to' => $to])->links() }}
            </div>
            @endif
        </div>

        {{-- TOP SELLER KOLOM KANAN --}}
        <div class="mgr-card mgr-animate" style="animation-delay: 0.3s; overflow: hidden;">
            <div style="padding: 1.125rem 1.375rem 0.75rem; border-bottom: 1px solid #F0E8DC;">
                <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--pods-muted); margin-bottom: 0.125rem;">Periode Terpilih</p>
                <h2 class="font-serif" style="font-size: 1rem; font-weight: 700; color: var(--pods-espresso);">Top Seller</h2>
            </div>

            @if($topSellers->isNotEmpty())
            <ul style="list-style: none; margin: 0; padding: 0.5rem 0;" role="list">
                @foreach($topSellers as $ts)
                @php $maxQty = $topSellers->first()->total_qty; @endphp
                <li style="padding: 0.875rem 1.375rem; {{ !$loop->last ? 'border-bottom: 1px solid #F8F0E6;' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                        <span style="width: 22px; height: 22px; border-radius: 50%; background: {{ $loop->first ? 'var(--pods-caramel)' : '#EDE0CC' }}; color: {{ $loop->first ? '#1C0F0A' : 'var(--pods-muted)' }}; font-size: 0.6875rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">{{ $loop->iteration }}</span>
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 500; color: var(--pods-espresso); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ts->name }}</p>
                        </div>
                        <span style="font-size: 0.8125rem; font-weight: 700; color: var(--pods-espresso); white-space: nowrap; font-variant-numeric: tabular-nums; flex-shrink: 0;">{{ $ts->total_qty }} pcs</span>
                    </div>
                    <div style="height: 4px; background: #EDE0CC; border-radius: 9999px; overflow: hidden; margin-left: 2.5rem;">
                        <div style="height: 100%; width: {{ $maxQty > 0 ? round(($ts->total_qty / $maxQty) * 100) : 0 }}%; background: {{ $loop->first ? 'var(--pods-caramel)' : '#D4C4AE' }}; border-radius: 9999px;"></div>
                    </div>
                    <p style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300; margin-top: 0.25rem; margin-left: 2.5rem; font-variant-numeric: tabular-nums;">
                        Rp {{ number_format($ts->total_revenue, 0, ',', '.') }}
                    </p>
                </li>
                @endforeach
            </ul>
            @else
            <div style="padding: 2.5rem 1.375rem; text-align: center;">
                <p style="font-size: 0.875rem; color: var(--pods-muted); font-weight: 300;">Belum ada data penjualan.</p>
            </div>
            @endif
        </div>

    </div>

</div>

@push('scripts')
<script>
(function () {
    document.querySelectorAll('.report-preset-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('report-date-from').value = btn.dataset.from;
            document.getElementById('report-date-to').value   = btn.dataset.to;
            document.getElementById('report-filter-form').submit();
        });
    });
}());
</script>
@endpush

@endsection

{{-- ================================================================
     DETAIL TRANSAKSI MANAGER
================================================================ --}}
<div id="trx-overlay" style="display:none; position:fixed; inset:0; background:rgba(28,15,10,0.6); z-index:9000; overflow-y:auto; padding:2rem;" aria-modal="true" role="dialog">
    <div style="background:#FFFDF9; border-radius:1.25rem; max-width:540px; margin:0 auto; overflow:hidden; box-shadow:0 24px 60px rgba(28,15,10,0.25);">
        <div style="background:var(--pods-espresso); padding:1.25rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:rgba(245,233,211,0.45); margin-bottom:0.2rem;">Detail Transaksi</p>
                <h2 id="trx-modal-number" style="font-family:'Courier New',monospace; font-size:1rem; font-weight:700; color:#F5E9D3;"></h2>
            </div>
            <button onclick="closeTrxDetail()" style="background:rgba(245,233,211,0.1); border:none; border-radius:0.5rem; padding:0.5rem; cursor:pointer; color:rgba(245,233,211,0.7); transition:background 0.15s;" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="trx-modal-meta" style="padding:1rem 1.5rem; background:#FBF6EE; border-bottom:1px solid #EDE0CC; display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem;"></div>
        <div id="trx-modal-cancel" style="display:none; padding:0.875rem 1.5rem; background:#FEF2F2; border-bottom:1px solid #FECACA;">
            <p style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.1em; color:#991B1B; margin-bottom:0.25rem;">Alasan Pembatalan</p>
            <p id="trx-modal-cancel-text" style="font-size:0.875rem; color:#DC2626;"></p>
        </div>
        <div id="trx-modal-items" style="padding:0.5rem 1.5rem;"></div>
        <div id="trx-modal-summary" style="padding:1rem 1.5rem; background:#FBF6EE; border-top:2px dashed #EDE0CC;"></div>
    </div>
</div>

<script id="trx-data" type="application/json">
@php
    $trxJson = $transactions->map(function ($trx) {
        return [
            'id'            => $trx->id_orders,
            'order_number'  => $trx->order_number,
            'customer'      => $trx->user->name ?? '—',
            'branch'        => $trx->branch->name ?? '—',
            'date'          => $trx->created_at->format('d M Y, H:i'),
            'status'        => $trx->status,
            'cancel_reason' => $trx->cancel_reason,
            'promo'         => $trx->promo ? $trx->promo->name : null,
            'subtotal'      => $trx->subtotal,
            'total_discount'=> $trx->total_discount,
            'grand_total'   => $trx->grand_total,
            'items'         => $trx->items->map(fn($i) => [
                'name'     => $i->product->name ?? '—',
                'qty'      => $i->qty,
                'price'    => $i->base_price,
                'discount' => $i->discount_amount,
                'subtotal' => $i->subtotal_price,
            ])->values(),
        ];
    })->keyBy('id');
@endphp
{!! json_encode($trxJson) !!}
</script>

@push('scripts')
<script>
(function () {
    var preset = document.getElementById('report-filter-form');
    document.querySelectorAll('.report-preset-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('report-date-from').value = btn.dataset.from;
            document.getElementById('report-date-to').value   = btn.dataset.to;
            document.getElementById('report-filter-form').submit();
        });
    });

    /* DETAIL */
    var trxData = {};
    try { trxData = JSON.parse(document.getElementById('trx-data').textContent); } catch(e) {}

    var statusLabel = { completed:'Selesai', canceled:'Dibatalkan', paid:'Lunas', cooking:'Dimasak', pending_payment:'Menunggu Bayar' };
    var statusBg    = { completed:'#D1FAE5', canceled:'#FEE2E2', paid:'#DBEAFE', cooking:'#FEF3C7', pending_payment:'#FEF3C7' };
    var statusTxt   = { completed:'#065F46', canceled:'#991B1B', paid:'#1E40AF', cooking:'#92400E', pending_payment:'#92400E' };

    function fmtRp(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

    window.openTrxDetail = function (id) {
        var t = trxData[id];
        if (!t) return;

        document.getElementById('trx-modal-number').textContent = '#' + t.order_number;

        document.getElementById('trx-modal-meta').innerHTML =
            '<div><p style="font-size:0.7rem;color:var(--pods-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.2rem;">Pelanggan</p><p style="font-size:0.875rem;font-weight:600;color:var(--pods-espresso);">' + t.customer + '</p></div>' +
            '<div><p style="font-size:0.7rem;color:var(--pods-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.2rem;">Tanggal</p><p style="font-size:0.875rem;font-weight:500;color:var(--pods-espresso);">' + t.date + '</p></div>' +
            '<div><p style="font-size:0.7rem;color:var(--pods-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.375rem;">Status</p>' +
            '<span style="display:inline-flex;align-items:center;gap:4px;padding:2px 9px;border-radius:9999px;font-size:0.75rem;font-weight:600;background:' + (statusBg[t.status]||'#F3F4F6') + ';color:' + (statusTxt[t.status]||'#374151') + ';">' + (statusLabel[t.status]||t.status) + '</span></div>';

        /* cancel_reason */
        var cancelBox = document.getElementById('trx-modal-cancel');
        if (t.status === 'canceled' && t.cancel_reason) {
            document.getElementById('trx-modal-cancel-text').textContent = t.cancel_reason;
            cancelBox.style.display = 'block';
        } else {
            cancelBox.style.display = 'none';
        }

        /* item list */
        var html = '';
        t.items.forEach(function (i) {
            html += '<div style="display:flex;justify-content:space-between;padding:0.75rem 0;border-bottom:1px solid #F0E8DC;">' +
                '<div style="flex:1;min-width:0;"><p style="font-size:0.9375rem;font-weight:500;color:var(--pods-espresso);">' + i.name + '</p>' +
                '<p style="font-size:0.75rem;color:var(--pods-muted);">' + fmtRp(i.price) + ' × ' + i.qty + '</p>' +
                (i.discount > 0 ? '<p style="font-size:0.75rem;color:#059669;">Diskon: −' + fmtRp(i.discount) + '</p>' : '') + '</div>' +
                '<span style="font-size:0.9375rem;font-weight:600;color:var(--pods-espresso);white-space:nowrap;">' + fmtRp(i.subtotal) + '</span></div>';
        });
        document.getElementById('trx-modal-items').innerHTML = html;

        /* summary */
        var sum = (t.promo ? '<div style="display:flex;justify-content:space-between;margin-bottom:0.375rem;"><span style="font-size:0.8125rem;color:var(--pods-muted);">Promo</span><span style="font-size:0.8125rem;color:#92400E;font-weight:600;">' + t.promo + '</span></div>' : '') +
            (t.total_discount > 0 ? '<div style="display:flex;justify-content:space-between;margin-bottom:0.375rem;"><span style="font-size:0.875rem;color:var(--pods-muted);">Diskon</span><span style="font-size:0.875rem;color:#059669;">−' + fmtRp(t.total_discount) + '</span></div>' : '') +
            '<div style="display:flex;justify-content:space-between;border-top:1px solid #EDE0CC;padding-top:0.75rem;margin-top:0.375rem;"><span style="font-size:0.9375rem;font-weight:600;color:var(--pods-muted);">Grand Total</span>' +
            '<span style="font-family:var(--font-serif);font-size:1.25rem;font-weight:700;color:var(--pods-espresso);">' + fmtRp(t.grand_total) + '</span></div>';
        document.getElementById('trx-modal-summary').innerHTML = sum;

        document.getElementById('trx-overlay').style.display = 'block';
        document.body.style.overflow = 'hidden';
    };

    window.closeTrxDetail = function () {
        document.getElementById('trx-overlay').style.display = 'none';
        document.body.style.overflow = '';
    };

    document.getElementById('trx-overlay').addEventListener('click', function (e) {
        if (e.target === this) closeTrxDetail();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeTrxDetail();
    });
}());
</script>
@endpush