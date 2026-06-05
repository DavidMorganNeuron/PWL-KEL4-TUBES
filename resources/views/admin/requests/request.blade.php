{{-- ADMIN REQUESTS: antrean validasi pengajuan restock dari manager cabang --}}
@extends('admin.layouts.app')

@section('title', "Validasi Request — Pod's Admin")
@section('page-title', 'Validasi Request Restock')

@section('content')

@php
    $tabs = [
        'all'      => ['label' => 'Semua',     'count' => $requests->count()],
        'pending'  => ['label' => 'Pending',   'count' => $requests->filter(fn($r) => $r['status'] === 'pending')->count()],
        'approved' => ['label' => 'Disetujui', 'count' => $requests->filter(fn($r) => $r['status'] === 'approved')->count()],
        'rejected' => ['label' => 'Ditolak',   'count' => $requests->filter(fn($r) => $r['status'] === 'rejected')->count()],
    ];

    $statusCfg = [
        'pending'  => ['bg' => '#FEF3C7', 'text' => '#92400E', 'dot' => '#D97706', 'label' => 'Pending',   'pulse' => true],
        'approved' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#059669', 'label' => 'Disetujui', 'pulse' => false],
        'rejected' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#DC2626', 'label' => 'Ditolak',   'pulse' => false],
    ];
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

    {{-- ================================================================
         TAB FILTER STATUS
    ================================================================ --}}
    <div style="display:flex; gap:0.5rem; margin-bottom:1.5rem; align-items:center;">
        @foreach($tabs as $key => $tab)
        <button
            type="button"
            class="req-tab-btn {{ $key === 'all' ? 'req-tab-active' : '' }}"
            data-status="{{ $key }}"
            style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; border-radius:9999px; font-size:0.8125rem; font-weight:{{ $key === 'all' ? '600' : '500' }}; border:1.5px solid {{ $key === 'all' ? '#C8813B' : '#D4C4AE' }}; background:{{ $key === 'all' ? '#C8813B' : '#FFFDF9' }}; color:{{ $key === 'all' ? '#1C0F0A' : 'var(--pods-espresso)' }}; cursor:pointer; transition:all 0.15s;"
            onmouseover="if(!this.classList.contains('req-tab-active'))this.style.borderColor='#C8813B';"
            onmouseout="if(!this.classList.contains('req-tab-active'))this.style.borderColor='#D4C4AE';"
        >
            {{ $tab['label'] }}
            <span style="display:inline-flex; align-items:center; justify-content:center; min-width:18px; height:18px; padding:0 4px; border-radius:9999px; font-size:0.625rem; font-weight:700; background:{{ $key === 'pending' ? '#DC2626' : ($key === 'all' ? 'rgba(28,15,10,0.15)' : '#EDE0CC') }}; color:{{ $key === 'pending' ? '#fff' : ($key === 'all' ? '#1C0F0A' : 'var(--pods-muted)') }};">
                {{ $tab['count'] }}
            </span>
        </button>
        @endforeach
    </div>

    {{-- ================================================================
         TABEL PENGAJUAN
    ================================================================ --}}
    <div class="adm-card adm-animate" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;" role="table" aria-label="Tabel antrean pengajuan restock">
                <thead>
                    <tr style="background:var(--pods-espresso);">
                        <th style="padding:0.75rem 1.5rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">ID</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Cabang · Manager</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Produk</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6); text-align:center;">Qty</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Catatan</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6); white-space:nowrap;">Tanggal</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6); text-align:center;">Status</th>
                        <th style="padding:0.75rem 1.5rem 0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    @php $sc = $statusCfg[$req['status']]; @endphp
                    <tr
                        class="req-row"
                        data-status="{{ $req['status'] }}"
                        style="border-top:1px solid #F0E8DC; transition:background 0.15s;"
                        onmouseover="this.style.background='#FFFBF4'"
                        onmouseout="this.style.background='transparent'"
                    >
                        <td style="padding:1rem 1.5rem; font-size:0.8125rem; color:var(--pods-muted); font-weight:300; font-variant-numeric:tabular-nums;">#{{ $req['id'] }}</td>

                        <td style="padding:1rem;">
                            <p style="font-size:0.875rem; font-weight:600; color:var(--pods-espresso);">{{ $req['branch'] }}</p>
                            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">{{ $req['manager'] }}</p>
                        </td>

                        <td style="padding:1rem; font-size:0.9375rem; font-weight:500; color:var(--pods-espresso); white-space:nowrap;">
                            {{ $req['product'] }}
                        </td>

                        <td style="padding:1rem; text-align:center; font-size:0.9375rem; font-weight:700; color:var(--pods-espresso); font-variant-numeric:tabular-nums;">
                            {{ $req['requested_qty'] }}
                        </td>

                        <td style="padding:1rem; font-size:0.8125rem; color:var(--pods-muted); font-weight:300; max-width:180px;">
                            {{ $req['notes'] ?? '—' }}
                        </td>

                        <td style="padding:1rem; font-size:0.8125rem; color:var(--pods-muted); font-weight:300; white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($req['created_at'])->format('d M Y') }}
                            <span style="display:block; font-size:0.75rem;">{{ \Carbon\Carbon::parse($req['created_at'])->format('H:i') }}</span>
                        </td>

                        <td style="padding:1rem; text-align:center;">
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px 3px 8px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:{{ $sc['bg'] }}; color:{{ $sc['text'] }}; white-space:nowrap;">
                                <span style="width:6px; height:6px; border-radius:9999px; background:{{ $sc['dot'] }}; {{ $sc['pulse'] ? 'animation:req-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;' : '' }}" aria-hidden="true"></span>
                                {{ $sc['label'] }}
                            </span>
                        </td>

                        <td style="padding:1rem 1.5rem 1rem 1rem;">
                            <a href="{{ route('admin.requests.show', $req['id']) }}" class="pods-btn-ghost" style="font-size:0.8125rem; padding:0.375rem 0.875rem; white-space:nowrap;">
                                {{ $req['status'] === 'pending' ? 'Review →' : 'Detail →' }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:0.75rem 1.5rem; background:#FBF6EE; border-top:1px solid #EDE0CC;">
            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">
                Menampilkan <span id="req-visible-count">{{ count($requests) }}</span> dari {{ $requests->total() }} pengajuan
            </p>
        </div>
        {{ $requests->links() }}
    </div>

</div>

@push('head-scripts')
<style>
    @keyframes req-pulse { 0%, 100% { opacity:1; } 50% { opacity:0.35; } }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var tabBtns   = document.querySelectorAll('.req-tab-btn');
    var rows      = document.querySelectorAll('.req-row');
    var countEl   = document.getElementById('req-visible-count');

    tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var status = btn.dataset.status;

            tabBtns.forEach(function (b) {
                b.classList.remove('req-tab-active');
                b.style.background  = '#FFFDF9';
                b.style.borderColor = '#D4C4AE';
                b.style.color       = 'var(--pods-espresso)';
                b.style.fontWeight  = '500';
            });
            btn.classList.add('req-tab-active');
            btn.style.background  = '#C8813B';
            btn.style.borderColor = '#C8813B';
            btn.style.color       = '#1C0F0A';
            btn.style.fontWeight  = '600';

            var visible = 0;
            rows.forEach(function (row) {
                var show = status === 'all' || row.dataset.status === status;
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