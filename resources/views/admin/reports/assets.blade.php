{{-- ADMIN REPORTS ASSETS: laporan kekayaan stok fisik dari global_stocks_view --}}
@extends('admin.layouts.app')

@section('title', "Laporan Aset Stok — Pod's Admin")
@section('page-title', 'Laporan Aset Stok Global')

@section('content')

@php
    $globalStocks = [
        /* Dr. Mansyur */
        ['branch' => 'Dr. Mansyur',   'category' => 'Kopi',     'product' => 'Biji Kopi Arabika',    'physical_qty' => 5,   'reserved_qty' => 0],
        ['branch' => 'Dr. Mansyur',   'category' => 'Kopi',     'product' => 'Biji Kopi Robusta',    'physical_qty' => 18,  'reserved_qty' => 2],
        ['branch' => 'Dr. Mansyur',   'category' => 'Susu',     'product' => 'Susu Oat (1L)',         'physical_qty' => 2,   'reserved_qty' => 0],
        ['branch' => 'Dr. Mansyur',   'category' => 'Susu',     'product' => 'Susu Full Cream (1L)', 'physical_qty' => 24,  'reserved_qty' => 4],
        ['branch' => 'Dr. Mansyur',   'category' => 'Sirup',    'product' => 'Sirup Karamel',         'physical_qty' => 3,   'reserved_qty' => 0],
        ['branch' => 'Dr. Mansyur',   'category' => 'Makanan',  'product' => 'Croissant Plain',       'physical_qty' => 30,  'reserved_qty' => 6],
        ['branch' => 'Dr. Mansyur',   'category' => 'Makanan',  'product' => 'Croissant Almond',      'physical_qty' => 22,  'reserved_qty' => 3],
        /* Jamin Ginting */
        ['branch' => 'Jamin Ginting', 'category' => 'Kopi',     'product' => 'Biji Kopi Arabika',    'physical_qty' => 22,  'reserved_qty' => 0],
        ['branch' => 'Jamin Ginting', 'category' => 'Kopi',     'product' => 'Biji Kopi Robusta',    'physical_qty' => 18,  'reserved_qty' => 1],
        ['branch' => 'Jamin Ginting', 'category' => 'Susu',     'product' => 'Susu Oat (1L)',         'physical_qty' => 15,  'reserved_qty' => 0],
        ['branch' => 'Jamin Ginting', 'category' => 'Susu',     'product' => 'Susu Full Cream (1L)', 'physical_qty' => 20,  'reserved_qty' => 2],
        ['branch' => 'Jamin Ginting', 'category' => 'Sirup',    'product' => 'Sirup Karamel',         'physical_qty' => 12,  'reserved_qty' => 0],
        ['branch' => 'Jamin Ginting', 'category' => 'Makanan',  'product' => 'Croissant Plain',       'physical_qty' => 35,  'reserved_qty' => 4],
        ['branch' => 'Jamin Ginting', 'category' => 'Makanan',  'product' => 'Croissant Almond',      'physical_qty' => 28,  'reserved_qty' => 2],
        /* Gatot Subroto */
        ['branch' => 'Gatot Subroto', 'category' => 'Kopi',     'product' => 'Biji Kopi Arabika',    'physical_qty' => 18,  'reserved_qty' => 0],
        ['branch' => 'Gatot Subroto', 'category' => 'Kopi',     'product' => 'Biji Kopi Robusta',    'physical_qty' => 7,   'reserved_qty' => 0],
        ['branch' => 'Gatot Subroto', 'category' => 'Susu',     'product' => 'Susu Oat (1L)',         'physical_qty' => 9,   'reserved_qty' => 0],
        ['branch' => 'Gatot Subroto', 'category' => 'Susu',     'product' => 'Susu Full Cream (1L)', 'physical_qty' => 11,  'reserved_qty' => 1],
        ['branch' => 'Gatot Subroto', 'category' => 'Sirup',    'product' => 'Sirup Karamel',         'physical_qty' => 6,   'reserved_qty' => 0],
        ['branch' => 'Gatot Subroto', 'category' => 'Makanan',  'product' => 'Croissant Plain',       'physical_qty' => 25,  'reserved_qty' => 3],
        ['branch' => 'Gatot Subroto', 'category' => 'Makanan',  'product' => 'Croissant Almond',      'physical_qty' => 15,  'reserved_qty' => 1],
    ];

    $threshold = 10; /* ambang batas stok kritis */

    /* helper: level stok */
    $stockLevel = function (int $qty) use ($threshold): array {
        $ratio = $qty / max($threshold, 1);
        if ($ratio <= 0.3) return ['level' => 'critical', 'label' => 'Kritis',  'bar' => '#DC2626', 'bg' => '#FEE2E2', 'text' => '#991B1B'];
        if ($ratio <= 0.6) return ['level' => 'low',      'label' => 'Rendah',  'bar' => '#D97706', 'bg' => '#FEF3C7', 'text' => '#92400E'];
        if ($ratio <= 0.9) return ['level' => 'medium',   'label' => 'Sedang',  'bar' => '#2563EB', 'bg' => '#DBEAFE', 'text' => '#1E40AF'];
        return                     ['level' => 'safe',     'label' => 'Aman',    'bar' => '#059669', 'bg' => '#D1FAE5', 'text' => '#065F46'];
    };

    /* agregasi global: total fisik dan ditahan */
    $totalPhysical  = array_sum(array_column($globalStocks, 'physical_qty'));
    $totalReserved  = array_sum(array_column($globalStocks, 'reserved_qty'));
    $totalAvailable = $totalPhysical - $totalReserved;
    $criticalCount  = count(array_filter($globalStocks, fn($s) => $s['physical_qty'] < $threshold));

    /* grup per cabang untuk tabel breakdown */
    $byBranch = [];
    foreach ($globalStocks as $s) {
        $byBranch[$s['branch']][] = $s;
    }

    /* filter kategori unik */
    $categories = array_unique(array_column($globalStocks, 'category'));
    sort($categories);
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

    {{-- ================================================================
         SECTION 1: RINGKASAN ASET GLOBAL
    ================================================================ --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem;">
        @foreach([
            ['label' => 'Total Stok Fisik Global',  'value' => number_format($totalPhysical, 0, ',', '.') . ' unit',  'sub' => 'Dari semua cabang',          'icon' => '📦', 'color' => 'var(--pods-espresso)'],
            ['label' => 'Stok Tersedia (Net)',       'value' => number_format($totalAvailable, 0, ',', '.') . ' unit', 'sub' => 'Fisik minus yang ditahan',   'icon' => '✅', 'color' => '#059669'],
            ['label' => 'Stok Ditahan (Reserved)',   'value' => number_format($totalReserved, 0, ',', '.') . ' unit',  'sub' => 'Soft-lock checkout aktif',   'icon' => '🔒', 'color' => '#D97706'],
            ['label' => 'Item Stok Kritis',          'value' => $criticalCount . ' Item',                              'sub' => 'Physical qty < ' . $threshold . ' unit', 'icon' => '⚠️', 'color' => '#DC2626'],
        ] as $i => $stat)
        <div class="adm-card adm-animate" style="padding:1.25rem 1.375rem; animation-delay:{{ $i * 0.06 }}s;">
            <div style="font-size:1.5rem; margin-bottom:0.5rem;">{{ $stat['icon'] }}</div>
            <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.375rem;">{{ $stat['label'] }}</p>
            <p class="font-serif" style="font-size:1.25rem; font-weight:700; color:{{ $stat['color'] }}; line-height:1.1; margin-bottom:0.25rem; font-variant-numeric:tabular-nums;">{{ $stat['value'] }}</p>
            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">{{ $stat['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ================================================================
         SECTION 2: FILTER KATEGORI + TABEL GLOBAL STOCKS VIEW
    ================================================================ --}}
    <div class="adm-card adm-animate" style="overflow:hidden; margin-bottom:1.5rem; animation-delay:0.24s;">
        <div style="padding:1.125rem 1.375rem 0.875rem; border-bottom:1px solid #F0E8DC; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <div>
                <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.125rem;">global_stocks_view</p>
                <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:var(--pods-espresso);">Stok Fisik Semua Cabang</h2>
            </div>

            {{-- filter kategori --}}
            <div style="display:flex; gap:0.375rem; flex-wrap:wrap;">
                <button type="button" class="asset-cat-btn asset-cat-active" data-cat="all"
                    style="padding:0.375rem 0.875rem; border-radius:9999px; font-size:0.75rem; font-weight:600; border:1.5px solid #C8813B; background:#C8813B; color:#1C0F0A; cursor:pointer; transition:all 0.15s;">
                    Semua
                </button>
                @foreach($categories as $cat)
                <button type="button" class="asset-cat-btn" data-cat="{{ $cat }}"
                    style="padding:0.375rem 0.875rem; border-radius:9999px; font-size:0.75rem; font-weight:500; border:1.5px solid #D4C4AE; background:#FFFDF9; color:var(--pods-espresso); cursor:pointer; transition:all 0.15s;"
                    onmouseover="if(!this.classList.contains('asset-cat-active'))this.style.borderColor='#C8813B';"
                    onmouseout="if(!this.classList.contains('asset-cat-active'))this.style.borderColor='#D4C4AE';">
                    {{ $cat }}
                </button>
                @endforeach
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;" role="table" aria-label="Tabel stok global semua cabang">
                <thead>
                    <tr style="background:var(--pods-espresso);">
                        <th style="padding:0.75rem 1.5rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Cabang</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Produk</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Kategori</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6); text-align:center;">Fisik</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6); text-align:center;">Ditahan</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6); text-align:center;">Tersedia</th>
                        <th style="padding:0.75rem 1.5rem 0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($globalStocks as $s)
                    @php
                        $lvl     = $stockLevel($s['physical_qty']);
                        $avail   = $s['physical_qty'] - $s['reserved_qty'];
                        $barPct  = min(100, round(($s['physical_qty'] / $threshold) * 100));
                    @endphp
                    <tr
                        class="asset-row"
                        data-category="{{ $s['category'] }}"
                        style="border-top:1px solid #F0E8DC; transition:background 0.15s;"
                        onmouseover="this.style.background='#FFFBF4'"
                        onmouseout="this.style.background='transparent'"
                    >
                        <td style="padding:0.875rem 1.5rem; font-size:0.875rem; font-weight:600; color:var(--pods-espresso); white-space:nowrap;">{{ $s['branch'] }}</td>
                        <td style="padding:0.875rem 1rem; font-size:0.875rem; font-weight:500; color:var(--pods-espresso); white-space:nowrap;">{{ $s['product'] }}</td>
                        <td style="padding:0.875rem 1rem;">
                            <span style="display:inline-block; padding:2px 9px; border-radius:9999px; background:rgba(200,129,59,0.1); border:1px solid rgba(200,129,59,0.2); font-size:0.6875rem; font-weight:600; color:#92400E; white-space:nowrap;">{{ $s['category'] }}</span>
                        </td>
                        <td style="padding:0.875rem 1rem; text-align:center; font-size:0.9375rem; font-weight:700; color:var(--pods-espresso); font-variant-numeric:tabular-nums;">{{ $s['physical_qty'] }}</td>
                        <td style="padding:0.875rem 1rem; text-align:center; font-size:0.875rem; color:var(--pods-muted); font-variant-numeric:tabular-nums;">{{ $s['reserved_qty'] }}</td>
                        <td style="padding:0.875rem 1rem; text-align:center; font-size:0.9375rem; font-weight:700; color:{{ $avail <= 0 ? '#DC2626' : 'var(--pods-espresso)' }}; font-variant-numeric:tabular-nums;">{{ $avail }}</td>
                        <td style="padding:0.875rem 1.5rem 0.875rem 1rem; min-width:160px;">
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <div style="flex:1; height:6px; background:#EDE0CC; border-radius:9999px; overflow:hidden;">
                                    <div style="height:100%; width:{{ $barPct }}%; background:{{ $lvl['bar'] }}; border-radius:9999px;"></div>
                                </div>
                                <span style="display:inline-flex; align-items:center; gap:3px; padding:2px 7px; border-radius:9999px; font-size:0.625rem; font-weight:600; background:{{ $lvl['bg'] }}; color:{{ $lvl['text'] }}; white-space:nowrap; flex-shrink:0;">
                                    @if($lvl['level'] === 'critical')
                                    <span style="width:4px; height:4px; border-radius:9999px; background:{{ $lvl['bar'] }}; animation:asset-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;" aria-hidden="true"></span>
                                    @endif
                                    {{ $lvl['label'] }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                {{-- baris footer: total global --}}
                <tfoot>
                    <tr style="border-top:2px solid #EDE0CC; background:#FBF6EE;">
                        <td style="padding:0.875rem 1.5rem; font-size:0.875rem; font-weight:700; color:var(--pods-espresso);" colspan="3">TOTAL GLOBAL</td>
                        <td style="padding:0.875rem 1rem; text-align:center; font-size:0.9375rem; font-weight:700; color:var(--pods-espresso); font-variant-numeric:tabular-nums;">{{ number_format($totalPhysical, 0, ',', '.') }}</td>
                        <td style="padding:0.875rem 1rem; text-align:center; font-size:0.9375rem; font-weight:700; color:#D97706; font-variant-numeric:tabular-nums;">{{ number_format($totalReserved, 0, ',', '.') }}</td>
                        <td style="padding:0.875rem 1rem; text-align:center; font-size:0.9375rem; font-weight:700; color:var(--pods-caramel); font-variant-numeric:tabular-nums;">{{ number_format($totalAvailable, 0, ',', '.') }}</td>
                        <td style="padding:0.875rem 1.5rem 0.875rem 1rem;"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="padding:0.75rem 1.5rem; background:#FBF6EE; border-top:1px solid #EDE0CC; display:flex; justify-content:space-between; align-items:center;">
            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">
                Menampilkan <span id="asset-visible-count">{{ count($globalStocks) }}</span> dari {{ count($globalStocks) }} entri stok
            </p>
            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">
                Sumber: <code style="font-size:0.6875rem; background:#EDE0CC; padding:1px 5px; border-radius:4px;">global_stocks_view</code> · Diperbarui: {{ now()->format('d M Y, H:i') }} WIB
            </p>
        </div>
    </div>

    {{-- ================================================================
         SECTION 3: RINGKASAN STOK PER CABANG
    ================================================================ --}}
    <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:var(--pods-espresso); margin-bottom:0.875rem;">Ringkasan Stok Lokal per Cabang</h2>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;">
        @foreach($byBranch as $branchName => $items)
        @php
            $branchPhysical  = array_sum(array_column($items, 'physical_qty'));
            $branchReserved  = array_sum(array_column($items, 'reserved_qty'));
            $branchCritical  = count(array_filter($items, fn($s) => $s['physical_qty'] < $threshold));
        @endphp
        <div class="adm-card adm-animate" style="overflow:hidden;">
            <div style="background:var(--pods-espresso); padding:0.875rem 1.125rem; display:flex; align-items:center; justify-content:space-between;">
                <p class="font-serif" style="font-size:0.9375rem; font-weight:700; color:#F5E9D3;">{{ $branchName }}</p>
                @if($branchCritical > 0)
                <span style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px 2px 6px; border-radius:9999px; font-size:0.625rem; font-weight:700; background:rgba(220,38,38,0.25); color:#FCA5A5;">
                    <span style="width:5px; height:5px; border-radius:9999px; background:#F87171; animation:asset-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;" aria-hidden="true"></span>
                    {{ $branchCritical }} Kritis
                </span>
                @else
                <span style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px 2px 6px; border-radius:9999px; font-size:0.625rem; font-weight:600; background:rgba(5,150,105,0.2); color:#6EE7B7;">
                    ✓ Aman
                </span>
                @endif
            </div>
            <div style="padding:0.875rem 1.125rem; display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.625rem;">
                @foreach([
                    ['label' => 'Fisik',    'value' => $branchPhysical,  'color' => 'var(--pods-espresso)'],
                    ['label' => 'Ditahan',  'value' => $branchReserved,  'color' => '#D97706'],
                    ['label' => 'Tersedia', 'value' => $branchPhysical - $branchReserved, 'color' => 'var(--pods-caramel)'],
                ] as $stat)
                <div style="text-align:center; padding:0.5rem; background:#FBF6EE; border-radius:8px;">
                    <p style="font-size:0.5625rem; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.2rem;">{{ $stat['label'] }}</p>
                    <p style="font-size:1rem; font-weight:700; color:{{ $stat['color'] }}; font-variant-numeric:tabular-nums;">{{ $stat['value'] }}</p>
                </div>
                @endforeach
            </div>
            {{-- daftar item stok kritis di cabang ini --}}
            @php $criticalItems = array_filter($items, fn($s) => $s['physical_qty'] < $threshold); @endphp
            @if(count($criticalItems) > 0)
            <div style="padding:0.75rem 1.125rem; border-top:1px solid #F0E8DC; background:#FFF8F0;">
                <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.1em; color:#DC2626; margin-bottom:0.375rem;">Perlu Perhatian</p>
                @foreach($criticalItems as $ci)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.25rem 0;">
                    <p style="font-size:0.75rem; color:var(--pods-espresso);">{{ $ci['product'] }}</p>
                    <span style="font-size:0.75rem; font-weight:700; color:#DC2626; font-variant-numeric:tabular-nums;">{{ $ci['physical_qty'] }} unit</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>

</div>

@push('head-scripts')
<style>
    @keyframes asset-pulse { 0%, 100% { opacity:1; } 50% { opacity:0.35; } }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var catBtns   = document.querySelectorAll('.asset-cat-btn');
    var rows      = document.querySelectorAll('.asset-row');
    var countEl   = document.getElementById('asset-visible-count');

    catBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cat = btn.dataset.cat;

            catBtns.forEach(function (b) {
                b.classList.remove('asset-cat-active');
                b.style.background  = '#FFFDF9';
                b.style.borderColor = '#D4C4AE';
                b.style.color       = 'var(--pods-espresso)';
                b.style.fontWeight  = '500';
            });
            btn.classList.add('asset-cat-active');
            btn.style.background  = '#C8813B';
            btn.style.borderColor = '#C8813B';
            btn.style.color       = '#1C0F0A';
            btn.style.fontWeight  = '600';

            var visible = 0;
            rows.forEach(function (row) {
                var show = cat === 'all' || row.dataset.category === cat;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (countEl) countEl.textContent = visible;
        });
    });
}());
</script>
@endpush

@endsection