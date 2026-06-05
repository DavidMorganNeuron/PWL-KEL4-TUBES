{{-- ADMIN REPORTS ASSETS: laporan kekayaan stok fisik dari global_stocks_view --}}
@extends('admin.layouts.app')

@section('title', "Laporan Aset Stok — Pod's Admin")
@section('page-title', 'Laporan Aset Stok Global')

@section('content')

@php
    $stockLevel = function (int $qty) use ($threshold): array {
        $ratio = $qty / max($threshold, 1);
        if ($ratio <= 0.3) return ['level' => 'critical', 'label' => 'Kritis',  'bar' => '#DC2626', 'bg' => '#FEE2E2', 'text' => '#991B1B'];
        if ($ratio <= 0.6) return ['level' => 'low',      'label' => 'Rendah',  'bar' => '#D97706', 'bg' => '#FEF3C7', 'text' => '#92400E'];
        if ($ratio <= 0.9) return ['level' => 'medium',   'label' => 'Sedang',  'bar' => '#2563EB', 'bg' => '#DBEAFE', 'text' => '#1E40AF'];
        return                     ['level' => 'safe',     'label' => 'Aman',    'bar' => '#059669', 'bg' => '#D1FAE5', 'text' => '#065F46'];
    };
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

         {{-- SECTION 2: FILTER KATEGORI + TABEL GLOBAL STOCKS VIEW --}}
    <div class="adm-card adm-animate" style="overflow:hidden; margin-bottom:1.5rem; animation-delay:0.24s;">
        <div style="padding:1.125rem 1.375rem 0.875rem; border-bottom:1px solid #F0E8DC; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <div>
                <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.125rem;">global_stocks_view</p>
                <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:var(--pods-espresso);">Stok Fisik Semua Cabang</h2>
            </div>

            <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
            {{-- filter cabang --}}
            <div>
                <select id="filter-branch" onchange="window.location.href='{{ route('admin.reports.assets') }}?branch='+this.value"
                    style="padding:0.375rem 0.75rem; border-radius:8px; border:1.5px solid #D4C4AE; background:#FFFDF9; font-size:0.75rem; color:var(--pods-espresso); cursor:pointer; outline:none;">
                    <option value="">Semua Cabang</option>
                    @foreach($branchNames as $bn)
                    <option value="{{ $bn }}" {{ $selectedBranch === $bn ? 'selected' : '' }}>{{ $bn }}</option>
                    @endforeach
                </select>
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
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;" role="table" aria-label="Tabel stok global semua cabang">
                <thead>
                    <tr style="background:var(--pods-espresso);">
                        <th style="padding:0.75rem 1.5rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Cabang</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Produk</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Kategori</th>
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