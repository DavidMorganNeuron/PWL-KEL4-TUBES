{{-- MANAGER STOCK: monitoring stok fisik lokal cabang --}}
@extends('manager.layouts.app')

@section('title', "Stok Lokal — Pod's Manager")
@section('page-title', 'Monitoring Stok Lokal')

@section('content')

@php
    const STOCK_THRESHOLD = 10;

    $stockLevel = function ($item) {
        $ratio = $item->physical_qty / max(STOCK_THRESHOLD, 1);
        if ($ratio <= 0.3) return ['level' => 'critical', 'label' => 'Kritis',  'bar' => '#DC2626', 'bg' => '#FEE2E2', 'text' => '#991B1B'];
        if ($ratio <= 0.6) return ['level' => 'low',      'label' => 'Rendah',  'bar' => '#D97706', 'bg' => '#FEF3C7', 'text' => '#92400E'];
        if ($ratio <= 0.9) return ['level' => 'medium',   'label' => 'Sedang',  'bar' => '#2563EB', 'bg' => '#DBEAFE', 'text' => '#1E40AF'];
        return                     ['level' => 'safe',     'label' => 'Aman',    'bar' => '#059669', 'bg' => '#D1FAE5', 'text' => '#065F46'];
    };

    $summary = ['critical' => 0, 'low' => 0, 'medium' => 0, 'safe' => 0];
    foreach ($stocks as $item) {
        $summary[$stockLevel($item)['level']]++;
    }

    $categories = $stocks->pluck('category_name')->unique()->sort()->values();
@endphp

<div style="padding: 2rem; background: #F0E8DC; min-height: calc(100vh - 64px);">

    {{-- ================================================================
         SECTION 1: RINGKASAN LEVEL STOK + CTA
    ================================================================ --}}
    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 1.75rem;">

        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            @foreach([
                ['level' => 'critical', 'label' => 'Kritis',  'bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#DC2626'],
                ['level' => 'low',      'label' => 'Rendah',  'bg' => '#FEF3C7', 'text' => '#92400E', 'dot' => '#D97706'],
                ['level' => 'medium',   'label' => 'Sedang',  'bg' => '#DBEAFE', 'text' => '#1E40AF', 'dot' => '#2563EB'],
                ['level' => 'safe',     'label' => 'Aman',    'bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#059669'],
            ] as $lvl)
            <div style="display: flex; align-items: center; gap: 0.5rem; background: {{ $lvl['bg'] }}; border-radius: 9999px; padding: 0.375rem 0.875rem 0.375rem 0.625rem;">
                <span style="width: 7px; height: 7px; border-radius: 9999px; background: {{ $lvl['dot'] }}; {{ $lvl['level'] === 'critical' ? 'animation: stock-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;' : '' }}" aria-hidden="true"></span>
                <span style="font-size: 0.75rem; font-weight: 600; color: {{ $lvl['text'] }};">
                    {{ $summary[$lvl['level']] }} {{ $lvl['label'] }}
                </span>
            </div>
            @endforeach
        </div>

        <a href="{{ route('manager.request_form') }}" class="pods-btn-primary" style="white-space: nowrap; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Restock
        </a>
    </div>

    {{-- ================================================================
         SECTION 2: FILTER KATEGORI
    ================================================================ --}}
    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
        <button type="button" class="stock-filter-tab stock-tab-active" data-filter="all"
            style="padding: 0.4375rem 1rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 600; border: 1.5px solid #C8813B; background: #C8813B; color: #1C0F0A; cursor: pointer; transition: all 0.15s;">
            Semua
        </button>
        @foreach($categories as $cat)
        <button type="button" class="stock-filter-tab" data-filter="{{ $cat }}"
            style="padding: 0.4375rem 1rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 500; border: 1.5px solid #D4C4AE; background: #FFFDF9; color: var(--pods-espresso); cursor: pointer; transition: all 0.15s;"
            onmouseover="if(!this.classList.contains('stock-tab-active')) this.style.borderColor='#C8813B';"
            onmouseout="if(!this.classList.contains('stock-tab-active')) this.style.borderColor='#D4C4AE';">
            {{ $cat }}
        </button>
        @endforeach
    </div>

    {{-- ================================================================
         SECTION 3: TABEL STOK LOKAL
    ================================================================ --}}
    <div class="mgr-card mgr-animate" style="overflow: hidden;">
        <div style="overflow-x: auto;">
            <table id="stock-table" style="width: 100%; border-collapse: collapse; text-align: left;" role="table" aria-label="Tabel stok fisik lokal cabang">
                <thead>
                    <tr style="background: var(--pods-espresso);">
                        <th style="padding: 0.75rem 1.5rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(245,233,211,0.6); white-space: nowrap;">#</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(245,233,211,0.6);">Nama Produk</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(245,233,211,0.6);">Kategori</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(245,233,211,0.6); text-align: center;">Stok Fisik</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(245,233,211,0.6); text-align: center;">Ditahan</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(245,233,211,0.6); text-align: center;">Tersedia</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(245,233,211,0.6);">Level</th>
                        <th style="padding: 0.75rem 1.5rem 0.75rem 1rem; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(245,233,211,0.6);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $idx => $item)
                    @php
                        $lvl    = $stockLevel($item);
                        $barPct = min(100, round(($item->physical_qty / STOCK_THRESHOLD) * 100));
                    @endphp
                    <tr
                        class="stock-row"
                        data-category="{{ $item->category_name }}"
                        style="border-top: 1px solid #F0E8DC; transition: background 0.15s;"
                        onmouseover="this.style.background='#FFFBF4'"
                        onmouseout="this.style.background='transparent'"
                    >
                        <td style="padding: 1rem 1.5rem; font-size: 0.8125rem; color: var(--pods-muted); font-weight: 300; font-variant-numeric: tabular-nums;">{{ $idx + 1 }}</td>

                        <td style="padding: 1rem; font-size: 0.9375rem; font-weight: 500; color: var(--pods-espresso); white-space: nowrap;">
                            {{ $item->product_name }}
                        </td>

                        <td style="padding: 1rem;">
                            <span style="display: inline-block; padding: 2px 10px; border-radius: 9999px; background: rgba(200,129,59,0.1); border: 1px solid rgba(200,129,59,0.2); font-size: 0.6875rem; font-weight: 600; color: #92400E; white-space: nowrap;">
                                {{ $item->category_name }}
                            </span>
                        </td>

                        <td style="padding: 1rem; text-align: center; font-size: 0.9375rem; font-weight: 700; color: var(--pods-espresso); font-variant-numeric: tabular-nums;">
                            {{ $item->physical_qty }}
                        </td>

                        <td style="padding: 1rem; text-align: center; font-size: 0.875rem; font-weight: 400; color: var(--pods-muted); font-variant-numeric: tabular-nums;">
                            {{ $item->reserved_qty }}
                        </td>

                        <td style="padding: 1rem; text-align: center; font-size: 0.9375rem; font-weight: 700; color: {{ $item->available_qty <= 0 ? '#DC2626' : 'var(--pods-espresso)' }}; font-variant-numeric: tabular-nums;">
                            {{ $item->available_qty }}
                        </td>

                        <td style="padding: 1rem; min-width: 160px;">
                            <div style="display: flex; align-items: center; gap: 0.625rem;">
                                <div style="flex: 1; height: 6px; background: #EDE0CC; border-radius: 9999px; overflow: hidden;">
                                    <div style="height: 100%; width: {{ $barPct }}%; background: {{ $lvl['bar'] }}; border-radius: 9999px;"></div>
                                </div>
                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: {{ $lvl['bg'] }}; color: {{ $lvl['text'] }}; white-space: nowrap; flex-shrink: 0;">
                                    @if($lvl['level'] === 'critical')
                                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: {{ $lvl['bar'] }}; animation: stock-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;" aria-hidden="true"></span>
                                    @endif
                                    {{ $lvl['label'] }}
                                </span>
                            </div>
                        </td>

                        <td style="padding: 1rem 1.5rem 1rem 1rem;">
                            <a
                                href="{{ route('manager.request_form') }}?product_id={{ $item->product_id }}"
                                style="font-size: 0.8125rem; font-weight: 500; color: var(--pods-caramel); text-decoration: none; white-space: nowrap; display: inline-flex; align-items: center; gap: 0.25rem;"
                                onmouseover="this.style.textDecoration='underline'"
                                onmouseout="this.style.textDecoration='none'"
                                aria-label="Ajukan restock untuk {{ $item->product_name }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Restock
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 2.5rem; text-align: center; font-size: 0.875rem; color: var(--pods-muted); font-weight: 300;">
                            Data stok tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 0.75rem 1.5rem; background: #FBF6EE; border-top: 1px solid #EDE0CC; display: flex; justify-content: space-between; align-items: center;">
            <p style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300;">
                Menampilkan <span id="stock-count">{{ $stocks->count() }}</span> dari {{ $stocks->count() }} item stok
            </p>
            <p style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300;">
                Data diperbarui: {{ now()->format('d M Y, H:i') }} WIB
            </p>
        </div>
    </div>

</div>

@push('head-scripts')
<style>
    @keyframes stock-pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.35; }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const tabs    = document.querySelectorAll('.stock-filter-tab');
    const rows    = document.querySelectorAll('.stock-row');
    const countEl = document.getElementById('stock-count');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const filter = tab.dataset.filter;

            tabs.forEach(function (t) {
                t.classList.remove('stock-tab-active');
                t.style.background  = '#FFFDF9';
                t.style.borderColor = '#D4C4AE';
                t.style.color       = 'var(--pods-espresso)';
                t.style.fontWeight  = '500';
            });

            tab.classList.add('stock-tab-active');
            tab.style.background  = '#C8813B';
            tab.style.borderColor = '#C8813B';
            tab.style.color       = '#1C0F0A';
            tab.style.fontWeight  = '600';

            let visible = 0;
            rows.forEach(function (row) {
                const match = filter === 'all' || row.dataset.category === filter;
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            if (countEl) countEl.textContent = visible;
        });
    });
}());
</script>
@endpush

@endsection