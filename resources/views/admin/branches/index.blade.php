{{-- Cabang Index: daftar semua cabang dengan status dan aksi --}}
@extends('admin.layouts.app')

@section('title', "Manajemen Cabang — Pod's Admin")
@section('page-title', 'Manajemen Cabang')

@section('content')

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

    {{-- HEADER: judul + tombol tambah --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
        <div>
            <h1 class="font-serif" style="font-size:1.5rem; font-weight:900; color:var(--pods-espresso); margin-bottom:0.25rem;">Manajemen Cabang</h1>
            <p style="font-size:0.8125rem; color:var(--pods-muted); font-weight:300;">Kelola cabang, stok, dan tutup cabang jika diperlukan.</p>
        </div>
        <a href="{{ route('admin.branches.create') }}" class="pods-btn-primary" style="text-decoration:none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Cabang
        </a>
    </div>

    {{-- TABEL CABANG --}}
    <div class="adm-card" style="overflow:hidden;">
        <table style="width:100%; border-collapse:collapse; font-size:0.8125rem;">
            <thead>
                <tr style="background:var(--pods-espresso); color:#F5E9D3; text-align:left;">
                    <th style="padding:0.875rem 1rem; font-weight:600; letter-spacing:0.04em;">Nama Cabang</th>
                    <th style="padding:0.875rem 1rem; font-weight:600; letter-spacing:0.04em;">Alamat</th>
                    <th style="padding:0.875rem 1rem; font-weight:600; letter-spacing:0.04em;">Manager</th>
                    <th style="padding:0.875rem 1rem; font-weight:600; letter-spacing:0.04em;">Jam Operasional</th>
                    <th style="padding:0.875rem 1rem; font-weight:600; letter-spacing:0.04em;">Status</th>
                    <th style="padding:0.875rem 1rem; font-weight:600; letter-spacing:0.04em;">Stok Kritis</th>
                    <th style="padding:0.875rem 1rem; font-weight:600; letter-spacing:0.04em;">Order Hari Ini</th>
                    <th style="padding:0.875rem 1rem; font-weight:600; letter-spacing:0.04em; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $b)
                <tr style="border-bottom:1px solid #EDE0CC; transition:background 0.15s;" onmouseover="this.style.background='#FFFCF8'" onmouseout="this.style.background='transparent'">
                    <td style="padding:0.875rem 1rem;">
                        <span style="font-weight:600; color:var(--pods-espresso);">{{ $b['name'] }}</span>
                    </td>
                    <td style="padding:0.875rem 1rem; color:var(--pods-muted); font-weight:300; max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $b['address'] }}
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        @if($b['manager'])
                        <div>
                            <p style="font-weight:500; color:var(--pods-espresso); margin-bottom:1px;">{{ $b['manager']['name'] }}</p>
                            <p style="font-size:0.75rem; color:var(--pods-muted);">{{ $b['manager']['email'] }}</p>
                        </div>
                        @else
                        <span style="color:var(--pods-muted); font-style:italic;">Tidak ada</span>
                        @endif
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        @if($b['is_always_open'])
                        <span style="color:#059669; font-weight:600;">Buka 24 Jam</span>
                        @else
                        <span style="color:var(--pods-espresso);">
                            {{ $b['open_time'] }} – {{ $b['close_time'] }}
                        </span>
                        @endif
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        @if($b['deleted_at'])
                        <span style="display:inline-flex; padding:2px 10px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:#FEE2E2; color:#991B1B;">Ditutup</span>
                        @elseif($b['is_closing'])
                        <span style="display:inline-flex; padding:2px 10px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:#FEF3C7; color:#92400E;">Menutup</span>
                        @elseif($b['is_active'])
                        <span style="display:inline-flex; padding:2px 10px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:#D1FAE5; color:#065F46;">Aktif</span>
                        @else
                        <span style="display:inline-flex; padding:2px 10px; border-radius:9999px; font-size:0.6875rem; font-weight:600; background:#FEE2E2; color:#991B1B;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding:0.875rem 1rem; text-align:center;">
                        <span style="font-weight:600; {{ $b['critical_stock'] > 0 ? 'color:#DC2626;' : 'color:#059669;' }}">
                            {{ $b['critical_stock'] }}
                        </span>
                    </td>
                    <td style="padding:0.875rem 1rem; text-align:center;">
                        <span style="font-weight:600; color:var(--pods-espresso);">{{ $b['orders_today'] }}</span>
                    </td>
                    <td style="padding:0.875rem 1rem; text-align:center;">
                        <div style="display:flex; align-items:center; justify-content:center; gap:0.375rem;">
                            <a href="{{ route('admin.branches.edit', $b['id']) }}" class="pods-btn-ghost" style="padding:0.375rem 0.75rem; font-size:0.75rem; text-decoration:none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            @if($b['is_active'] && !$b['is_closing'] && !$b['deleted_at'] && !in_array($b['name'], ['Dr. Mansyur', 'Gatot Subroto', 'Jamin Ginting'], true))
                            <button type="button" class="pods-btn-danger" style="padding:0.375rem 0.75rem; font-size:0.75rem;" onclick="confirmClose({{ $b['id'] }}, '{{ addslashes($b['name']) }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Tutup
                            </button>
                            @elseif(in_array($b['name'], ['Dr. Mansyur', 'Gatot Subroto', 'Jamin Ginting'], true) && $b['is_active'])
                            <span style="display:inline-flex; padding:0.375rem 0.75rem; font-size:0.75rem; color:#A08060; font-weight:400; border:1px dashed #D4C4AE; border-radius:6px; white-space:nowrap;">
                                Cabang Utama
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:3rem 1rem; text-align:center; color:var(--pods-muted);">
                        Belum ada cabang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

@push('scripts')
<script>
function confirmClose(id, name) {
    window.SwalModal.fire({
        title: 'Tutup Cabang ' + name + '?',
        text: 'Tindakan ini akan membatalkan order aktif, mentransfer stok, dan menonaktifkan cabang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#DC2626',
        reverseButtons: true,
    }).then(function (r) {
        if (r.isConfirmed) {
            window.location.href = '{{ url("admin/branches") }}/' + id + '/close';
        }
    });
}
</script>
@endpush
