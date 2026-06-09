{{-- Cabang Close: wizard penutupan cabang --}}
@extends('admin.layouts.app')

@section('title', "Tutup Cabang — Pod's Admin")
@section('page-title', 'Penutupan Cabang')

@section('content')

@php
$branch = $branch ?? null;
$activeOrders = $activeOrders ?? 0;
$stockSummary = $stockSummary ?? [];
$otherBranches = $otherBranches ?? [];
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

    {{-- Untuk munculin peringatan --}}
    <div style="padding:1rem 1.25rem; background:#FEF2F2; border:1.5px solid #FECACA; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:flex-start; gap:0.75rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="2" aria-hidden="true" style="flex-shrink:0; margin-top:1px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54-3.37 1.54-3.37 1.54 0a2 2 0 01-1.54-2V5a2 2 0 00-2-2H6.646a2 2 0 00-2 2v12a2 2 0 001.54 2z"/>
        </svg>
        <div>
            <p style="font-size:0.875rem; font-weight:600; color:#991B1B; margin-bottom:0.25rem;">Peringatan!</p>
            <p style="font-size:0.8125rem; color:#B91C1C; font-weight:300;">Tindakan ini tidak dapat dibatalkan. Semua order aktif akan dibatalkan, stok akan ditransfer atau dibuang, dan cabang akan dinonaktifkan secara permanen.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.branches.executeClose', $branch['id_branches']) }}" style="max-width:800px;">
        @csrf

        {{-- Ringkasan Cabang --}}
        <div class="adm-card" style="padding:1.5rem; margin-bottom:1.25rem;">
            <h2 class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-espresso); margin-bottom:1rem; padding-bottom:0.75rem; border-bottom:1px solid #EDE0CC;">
                Ringkasan Cabang
            </h2>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <p style="font-size:0.75rem; font-weight:600; color:var(--pods-muted); letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.25rem;">Nama Cabang</p>
                    <p style="font-size:0.9375rem; font-weight:600; color:var(--pods-espresso);">{{ $branch['name'] }}</p>
                </div>
                <div>
                    <p style="font-size:0.75rem; font-weight:600; color:var(--pods-muted); letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.25rem;">Manager</p>
                    <p style="font-size:0.9375rem; font-weight:600; color:var(--pods-espresso);">{{ $branch['manager']['name'] ?? '-' }}</p>
                </div>
                <div style="grid-column:1/-1;">
                    <p style="font-size:0.75rem; font-weight:600; color:var(--pods-muted); letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.25rem;">Alamat</p>
                    <p style="font-size:0.8125rem; font-weight:300; color:var(--pods-muted);">{{ $branch['address'] }}</p>
                </div>
            </div>
        </div>

        {{-- Ringkasan Order Aktif --}}
        <div class="adm-card" style="padding:1.5rem; margin-bottom:1.25rem;">
            <h2 class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-espresso); margin-bottom:0.75rem; padding-bottom:0.75rem; border-bottom:1px solid #EDE0CC;">
                Order Aktif
            </h2>
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <span style="font-size:2rem; font-weight:900; color:var(--pods-espresso);">{{ $activeOrders }}</span>
                <span style="font-size:0.8125rem; color:var(--pods-muted);">order dalam status Paid / Cooking akan dibatalkan.</span>
            </div>
        </div>

        {{-- Ringkasan Stok --}}
        <div class="adm-card" style="padding:1.5rem; margin-bottom:1.25rem;">
            <h2 class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-espresso); margin-bottom:0.75rem; padding-bottom:0.75rem; border-bottom:1px solid #EDE0CC;">
                Stok Tersisa
            </h2>
            <table style="width:100%; border-collapse:collapse; font-size:0.8125rem;">
                <thead>
                    <tr style="background:#F5F0E8; text-align:left;">
                        <th style="padding:0.5rem 0.75rem; font-weight:600; color:var(--pods-espresso);">Produk</th>
                        <th style="padding:0.5rem 0.75rem; font-weight:600; color:var(--pods-espresso); text-align:center;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockSummary as $s)
                    <tr style="border-bottom:1px solid #EDE0CC;">
                        <td style="padding:0.5rem 0.75rem; color:var(--pods-espresso);">{{ $s['name'] }}</td>
                        <td style="padding:0.5rem 0.75rem; text-align:center; font-weight:600;">{{ $s['physical_qty'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding:1rem; text-align:center; color:var(--pods-muted);">Tidak ada stok tersisa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pilihan Transfer Stok --}}
        <div class="adm-card" style="padding:1.5rem; margin-bottom:1.25rem;">
            <h2 class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-espresso); margin-bottom:1rem; padding-bottom:0.75rem; border-bottom:1px solid #EDE0CC;">
                Transfer Stok
            </h2>
            <div style="margin-bottom:0.75rem;">
                <label for="transfer_to_branch_id" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">Pilih Cabang Tujuan</label>
                <select id="transfer_to_branch_id" name="transfer_to_branch_id"
                    style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1.5px solid #EDE0CC; font-size:0.875rem; background:#FFFFFF;">
                    <option value="">— Buang stok (tidak transfer) —</option>
                    @foreach($otherBranches as $ob)
                    <option value="{{ $ob['id_branches'] }}">{{ $ob['name'] }}</option>
                    @endforeach
                </select>
                <p style="font-size:0.75rem; color:var(--pods-muted); margin-top:0.375rem;">Stok akan ditambahkan ke cabang tujuan. Jika tidak dipilih, stok akan dibuang.</p>
            </div>
        </div>

        {{-- Konfirmasi --}}
        <div class="adm-card" style="padding:1.5rem; margin-bottom:1.25rem;">
            <h2 class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-espresso); margin-bottom:0.75rem; padding-bottom:0.75rem; border-bottom:1px solid #EDE0CC;">
                Konfirmasi
            </h2>
            <div>
                <label for="confirm_name" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">
                    Ketik <span style="color:#DC2626; font-weight:700;">{{ $branch['name'] }}</span> untuk konfirmasi <span style="color:#DC2626;">*</span>
                </label>
                <input type="text" id="confirm_name" name="confirm_name" value="{{ old('confirm_name') }}" required
                    style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1.5px solid {{ $errors->has('confirm_name') ? '#DC2626' : '#EDE0CC' }}; font-size:0.875rem;"
                    placeholder="Ketik nama cabang...">
                @error('confirm_name')
                <p style="font-size:0.75rem; color:#DC2626; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Error Global --}}
        @error('error')
        <div style="padding:0.875rem 1rem; background:#FEE2E2; border:1px solid #FECACA; border-radius:8px; color:#991B1B; font-size:0.8125rem; margin-bottom:1.25rem;">
            {{ $message }}
        </div>
        @enderror

        {{-- Tombol Aksi --}}
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <button type="button" id="btn-execute-close" class="pods-btn-danger" style="padding:0.625rem 1.5rem; font-size:0.875rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Tutup Cabang Sekarang
            </button>
            <a href="{{ route('admin.branches.index') }}" class="pods-btn-ghost">Batal</a>
        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
(function () {
    var btn = document.getElementById('btn-execute-close');
    var form = btn ? btn.closest('form') : null;
    if (!btn || !form) return;

    btn.addEventListener('click', function () {
        var nameInput = document.getElementById('confirm_name');
        var expected = {!! json_encode($branch['name']) !!};
        if (!nameInput || nameInput.value.trim() !== expected) {
            window.SwalModal.fire({
                title: 'Konfirmasi Gagal',
                text: 'Nama cabang yang diketik tidak sesuai. Silakan ketik ulang nama cabang dengan benar.',
                icon: 'error',
                confirmButtonText: 'Mengerti',
            });
            return;
        }

        window.SwalModal.fire({
            title: 'Tutup Cabang?',
            text: 'Tindakan ini tidak dapat dibatalkan. Semua data akan diproses sesuai konfigurasi.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tutup Cabang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#DC2626',
            reverseButtons: true,
        }).then(function (r) {
            if (r.isConfirmed) {
                form.submit();
            }
        });
    });
}());
</script>
@endpush
