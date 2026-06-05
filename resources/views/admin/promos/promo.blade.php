{{-- ADMIN PROMOS: daftar program diskon nasional dan lokal --}}
@extends('admin.layouts.app')

@section('title', "Manajemen Promo — Pod's Admin")
@section('page-title', 'Manajemen Promo')

@section('content')

@php
    $today    = now()->format('Y-m-d');
    $active   = $promos->filter(fn($p) => $p['is_active']);
    $inactive = $promos->filter(fn($p) => !$p['is_active']);
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

    {{-- ================================================================
         HEADER: ringkasan + CTA tambah promo
    ================================================================ --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
            <div style="display:flex; align-items:center; gap:0.5rem; background:#D1FAE5; border-radius:9999px; padding:0.375rem 0.875rem 0.375rem 0.625rem;">
                <span style="width:7px; height:7px; border-radius:9999px; background:#059669;" aria-hidden="true"></span>
                <span style="font-size:0.75rem; font-weight:600; color:#065F46;">{{ count($active) }} Aktif</span>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem; background:#F3F4F6; border-radius:9999px; padding:0.375rem 0.875rem 0.375rem 0.625rem;">
                <span style="width:7px; height:7px; border-radius:9999px; background:#9CA3AF;" aria-hidden="true"></span>
                <span style="font-size:0.75rem; font-weight:600; color:#374151;">{{ count($inactive) }} Nonaktif</span>
            </div>
        </div>
        <a href="{{ route('admin.promos.create') }}" class="pods-btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Promo Baru
        </a>
    </div>

    {{-- ================================================================
         TABEL PROMO
    ================================================================ --}}
    <div class="adm-card adm-animate" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;" role="table" aria-label="Tabel manajemen promo">
                <thead>
                    <tr style="background:var(--pods-espresso);">
                        <th style="padding:0.75rem 1.5rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">#</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Nama Promo</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Cakupan</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Diskon</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Produk Berlaku</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Masa Berlaku</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6); text-align:center;">Status</th>
                        <th style="padding:0.75rem 1.5rem 0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promos as $idx => $promo)
                    @php
                        $isExpired = $promo['end_date'] < $today;
                        $rowOpacity = (!$promo['is_active'] || $isExpired) ? 'opacity:0.65;' : '';
                    @endphp
                    <tr
                        style="border-top:1px solid #F0E8DC; transition:background 0.15s; {{ $rowOpacity }}"
                        onmouseover="this.style.background='#FFFBF4'"
                        onmouseout="this.style.background='transparent'"
                    >
                        <td style="padding:1rem 1.5rem; font-size:0.8125rem; color:var(--pods-muted); font-weight:300;">{{ $idx + 1 }}</td>

                        <td style="padding:1rem;">
                            <p style="font-size:0.9375rem; font-weight:600; color:var(--pods-espresso);">{{ $promo['name'] }}</p>
                        </td>

                        {{-- cakupan: Nasional atau Lokal per cabang --}}
                        <td style="padding:1rem;">
                            @if($promo['branch'] === null)
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px 3px 7px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:rgba(37,99,235,0.1); color:#1E40AF; white-space:nowrap;">
                                🌐 Nasional
                            </span>
                            @else
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px 3px 7px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:rgba(200,129,59,0.1); color:#92400E; white-space:nowrap;">
                                📍 {{ $promo['branch'] }}
                            </span>
                            @endif
                        </td>

                        {{-- nilai diskon --}}
                        <td style="padding:1rem; white-space:nowrap;">
                            <span style="font-size:0.9375rem; font-weight:700; color:var(--pods-caramel); font-variant-numeric:tabular-nums;">
                                @if($promo['discount_type'] === 'percentage')
                                    {{ $promo['discount_value'] }}%
                                @else
                                    Rp {{ number_format($promo['discount_value'], 0, ',', '.') }}
                                @endif
                            </span>
                            <span style="font-size:0.6875rem; color:var(--pods-muted); display:block; font-weight:300;">
                                {{ $promo['discount_type'] === 'percentage' ? 'Persentase' : 'Nominal' }}
                            </span>
                        </td>

                        {{-- produk yang dapat diskon --}}
                        <td style="padding:1rem; max-width:220px;">
                            <div style="display:flex; flex-wrap:wrap; gap:0.3rem;">
                                @foreach(array_slice($promo['products'], 0, 2) as $prod)
                                <span style="display:inline-block; padding:1px 7px; border-radius:4px; background:#F0E8DC; font-size:0.6875rem; font-weight:500; color:var(--pods-espresso); white-space:nowrap;">{{ $prod }}</span>
                                @endforeach
                                @if(count($promo['products']) > 2)
                                <span style="display:inline-block; padding:1px 7px; border-radius:4px; background:#EDE0CC; font-size:0.6875rem; font-weight:600; color:var(--pods-muted);">+{{ count($promo['products']) - 2 }} lagi</span>
                                @endif
                            </div>
                        </td>

                        {{-- masa berlaku --}}
                        <td style="padding:1rem; white-space:nowrap;">
                            <p style="font-size:0.8125rem; font-weight:500; color:var(--pods-espresso);">
                                {{ \Carbon\Carbon::parse($promo['start_date'])->format('d M Y') }}
                            </p>
                            <p style="font-size:0.75rem; color:{{ $isExpired ? '#DC2626' : 'var(--pods-muted)' }}; font-weight:300;">
                                s/d {{ \Carbon\Carbon::parse($promo['end_date'])->format('d M Y') }}
                                @if($isExpired) <span style="font-weight:600;">(Berakhir)</span> @endif
                            </p>
                        </td>

                        {{-- status badge --}}
                        <td style="padding:1rem; text-align:center;">
                            @if($promo['is_active'] && !$isExpired)
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px 3px 8px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:#D1FAE5; color:#065F46;">
                                <span style="width:6px; height:6px; border-radius:9999px; background:#059669;" aria-hidden="true"></span>Aktif
                            </span>
                            @else
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px 3px 8px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:#F3F4F6; color:#6B7280;">
                                <span style="width:6px; height:6px; border-radius:9999px; background:#9CA3AF;" aria-hidden="true"></span>{{ $isExpired ? 'Berakhir' : 'Nonaktif' }}
                            </span>
                            @endif
                        </td>

                        {{-- aksi --}}
                        <td style="padding:1rem 1.5rem 1rem 1rem;">
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <a href="{{ route('admin.promos.edit', $promo['id']) }}" class="pods-btn-ghost" style="font-size:0.8125rem; padding:0.375rem 0.875rem;">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.promos.destroy', $promo['id']) }}" class="promo-delete-form" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="pods-btn-danger promo-delete-btn" data-promo-name="{{ $promo['name'] }}" style="font-size:0.8125rem; padding:0.375rem 0.875rem;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:0.75rem 1.5rem; background:#FBF6EE; border-top:1px solid #EDE0CC;">
            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">Total {{ $promos->total() }} promo terdaftar</p>
        </div>
        {{ $promos->links() }}
    </div>

</div>

@push('scripts')
<script>
(function () {
    document.querySelectorAll('.promo-delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var name = btn.dataset.promoName;
            var form = btn.closest('form');
            window.SwalModal.fire({
                title: 'Hapus Promo?',
                html: '<p style="font-size:0.875rem;color:#6B7280;">Promo <strong>' + name + '</strong> akan dihapus permanen.</p>',
                icon: 'warning', showCancelButton: true,
                confirmButtonText: 'Ya, Hapus', confirmButtonColor: '#DC2626',
                cancelButtonText: 'Batal', reverseButtons: true,
            }).then(function (r) { if (r.isConfirmed) form.submit(); });
        });
    });
}());
</script>
@endpush

@endsection