{{-- ADMIN REQUESTS SHOW: detail pengajuan restock + aksi Approve/Reject --}}
@extends('admin.layouts.app')

@section('title', "Detail Request — Pod's Admin")
@section('page-title', 'Detail Pengajuan Restock')

@section('content')

@php
    $isPending = $request['status'] === 'pending';

    $statusCfg = [
        'pending'  => ['bg' => '#FEF3C7', 'text' => '#92400E', 'dot' => '#D97706', 'label' => 'Menunggu Validasi Admin'],
        'approved' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#059669', 'label' => 'Disetujui'],
        'rejected' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#DC2626', 'label' => 'Ditolak'],
    ];
    $sc = $statusCfg[$request['status']];
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">
<div style="max-width:680px;">

    {{-- breadcrumb --}}
    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem; font-size:0.8125rem; color:var(--pods-muted);">
        <a href="{{ route('admin.requests.index') }}" style="color:var(--pods-caramel); text-decoration:none; font-weight:500;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Validasi Request</a>
        <span>›</span>
        <span style="color:var(--pods-espresso); font-weight:500;">Detail #{{ $request['id'] }}</span>
    </div>

    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        {{-- informasi pengajuan --}}
        <div class="adm-card adm-animate" style="overflow:hidden;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #F0E8DC; background:var(--pods-espresso); display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.2em; text-transform:uppercase; color:rgba(245,233,211,0.45); margin-bottom:0.2rem;">Request #{{ $request['id'] }}</p>
                    <h2 class="font-serif" style="font-size:1rem; font-weight:700; color:#F5E9D3;">Pengajuan Restock</h2>
                </div>
                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 12px 4px 9px; border-radius:9999px; font-size:0.75rem; font-weight:600; background:{{ $sc['bg'] }}; color:{{ $sc['text'] }};">
                    <span style="width:7px; height:7px; border-radius:9999px; background:{{ $sc['dot'] }};" aria-hidden="true"></span>
                    {{ $sc['label'] }}
                </span>
            </div>
            <div style="padding:1.375rem 1.5rem; display:grid; grid-template-columns:1fr 1fr; gap:1.125rem;">
                @foreach([
                    ['label' => 'Cabang',       'value' => $request['branch']],
                    ['label' => 'Manager',      'value' => $request['manager']],
                    ['label' => 'Produk',       'value' => $request['product']],
                    ['label' => 'Waktu Pengajuan', 'value' => \Carbon\Carbon::parse($request['created_at'])->translatedFormat('d M Y, H:i')],
                ] as $info)
                <div>
                    <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.12em; color:var(--pods-muted); margin-bottom:0.25rem;">{{ $info['label'] }}</p>
                    <p style="font-size:0.9375rem; font-weight:500; color:var(--pods-espresso);">{{ $info['value'] }}</p>
                </div>
                @endforeach

                {{-- qty yang diminta + stok saat ini --}}
                <div>
                    <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.12em; color:var(--pods-muted); margin-bottom:0.25rem;">Jumlah Diminta</p>
                    <p style="font-size:1.375rem; font-weight:700; color:var(--pods-caramel); font-variant-numeric:tabular-nums;">{{ $request['requested_qty'] }} unit</p>
                </div>
                <div>
                    <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.12em; color:var(--pods-muted); margin-bottom:0.25rem;">Stok Fisik Saat Ini</p>
                    <p style="font-size:1.375rem; font-weight:700; color:{{ $currentStock['physical_qty'] < 10 ? '#DC2626' : 'var(--pods-espresso)' }}; font-variant-numeric:tabular-nums;">
                        {{ $currentStock['physical_qty'] }} unit
                        @if($currentStock['physical_qty'] < 10)
                        <span style="font-size:0.75rem; font-weight:600; color:#DC2626; display:block;">⚠ Stok Kritis</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- catatan dari manager --}}
            @if($request['notes'])
            <div style="padding:1rem 1.5rem; border-top:1px solid #F0E8DC; background:#FBF6EE;">
                <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.12em; color:var(--pods-muted); margin-bottom:0.375rem;">Catatan Manager</p>
                <p style="font-size:0.875rem; color:var(--pods-espresso); line-height:1.6; font-style:italic;">"{{ $request['notes'] }}"</p>
            </div>
            @endif
        </div>

        {{-- KARTU AKSI: hanya muncul jika status pending --}}
        @if($isPending)
        <div class="adm-card adm-animate" style="overflow:hidden; animation-delay:0.12s;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #F0E8DC; background:var(--pods-espresso);">
                <h2 class="font-serif" style="font-size:0.9375rem; font-weight:700; color:#F5E9D3;">Keputusan Admin</h2>
            </div>
            <div style="padding:1.5rem;">

                {{-- INFO: apa yang terjadi jika Approve --}}
                <div style="background:rgba(5,150,105,0.06); border:1px solid rgba(5,150,105,0.2); border-radius:8px; padding:0.875rem 1rem; margin-bottom:1.25rem;">
                    <p style="font-size:0.8125rem; font-weight:600; color:#065F46; margin-bottom:0.25rem;">✓ Jika Approve:</p>
                    <p style="font-size:0.8125rem; color:#065F46; font-weight:300; line-height:1.55;">
                        Stok fisik <strong>{{ $request['product'] }}</strong> di cabang <strong>{{ $request['branch'] }}</strong> akan otomatis bertambah sebanyak <strong>{{ $request['requested_qty'] }} unit</strong> dan aktivitas ini dicatat ke <code>stock_log</code>.
                    </p>
                </div>

                {{-- form approve --}}
                <form method="POST" action="{{ route('admin.requests.approve', $request['id']) }}" id="form-approve" style="display:none;">
                    @csrf
                    @method('PATCH')
                </form>

                {{-- form reject --}}
                <form method="POST" action="{{ route('admin.requests.reject', $request['id']) }}" id="form-reject" style="display:none;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="notes" id="reject-reason-input">
                </form>

                <div style="display:flex; gap:0.75rem; align-items:center;">
                    {{-- tombol APPROVE --}}
                    <button
                        type="button"
                        id="btn-approve"
                        style="flex:1; padding:0.875rem; border-radius:10px; border:none; background:#059669; color:#fff; font-family:var(--font-sans); font-size:0.9375rem; font-weight:700; cursor:pointer; transition:background 0.15s, transform 0.1s; display:flex; align-items:center; justify-content:center; gap:0.5rem;"
                        onmouseover="this.style.background='#047857'"
                        onmouseout="this.style.background='#059669'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approve
                    </button>

                    {{-- tombol REJECT --}}
                    <button
                        type="button"
                        id="btn-reject"
                        style="flex:1; padding:0.875rem; border-radius:10px; border:1.5px solid #FECACA; background:#FEE2E2; color:#991B1B; font-family:var(--font-sans); font-size:0.9375rem; font-weight:700; cursor:pointer; transition:background 0.15s; display:flex; align-items:center; justify-content:center; gap:0.5rem;"
                        onmouseover="this.style.background='#FCA5A5'"
                        onmouseout="this.style.background='#FEE2E2'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reject
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- tombol kembali --}}
        <div style="display:flex; justify-content:flex-start;">
            <a href="{{ route('admin.requests.index') }}" class="pods-btn-ghost">
                ← Kembali ke Daftar
            </a>
        </div>

    </div>
</div>
</div>

@push('scripts')
<script>
(function () {
    var btnApprove = document.getElementById('btn-approve');
    var btnReject  = document.getElementById('btn-reject');
    var formApprove = document.getElementById('form-approve');
    var formReject  = document.getElementById('form-reject');
    var rejectInput = document.getElementById('reject-reason-input');

    if (btnApprove) {
        btnApprove.addEventListener('click', function () {
            window.SwalModal.fire({
                title:             'Approve Pengajuan?',
                html:              '<p style="font-size:0.875rem;color:#6B7280;">Stok <strong>{{ $request['product'] }}</strong> di cabang <strong>{{ $request['branch'] }}</strong> akan otomatis bertambah <strong>{{ $request['requested_qty'] }} unit</strong>.</p>',
                icon:              'question',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Approve',
                confirmButtonColor:'#059669',
                cancelButtonText:  'Batal',
                reverseButtons:    true,
            }).then(function (r) {
                if (r.isConfirmed) formApprove.submit();
            });
        });
    }

    if (btnReject) {
        btnReject.addEventListener('click', function () {
            window.SwalModal.fire({
                title: 'Reject Pengajuan?',
                html: `
                    <p style="font-size:0.875rem;color:#6B7280;margin-bottom:1rem;">Tuliskan alasan penolakan untuk manager.</p>
                    <textarea id="swal-reject-reason" placeholder="Contoh: Stok pusat sedang habis, coba lagi minggu depan..."
                        style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D1D5DB;border-radius:8px;font-size:0.875rem;font-family:inherit;resize:vertical;min-height:80px;outline:none;"
                        onfocus="this.style.borderColor='#DC2626'" onblur="this.style.borderColor='#D1D5DB'"></textarea>
                `,
                icon:              'warning',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Reject',
                confirmButtonColor:'#DC2626',
                cancelButtonText:  'Batal',
                reverseButtons:    true,
                preConfirm: function () {
                    var reason = document.getElementById('swal-reject-reason').value.trim();
                    if (!reason) { Swal.showValidationMessage('Alasan penolakan wajib diisi.'); return false; }
                    return reason;
                },
            }).then(function (r) {
                if (r.isConfirmed && rejectInput && formReject) {
                    rejectInput.value = r.value;
                    formReject.submit();
                }
            });
        });
    }
}());
</script>
@endpush

@endsection