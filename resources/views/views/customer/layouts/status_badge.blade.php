{{-- STATUS BADGE PESANAN --}}
{{-- variabel wajib: $status (string) --}}
@php
    /* map setiap status ke konfigurasi visual & aksesibilitas */
    $statusConfig = [
        'pending_payment' => [
            'bg'    => '#FEF3C7', // warna amber
            'text'  => '#92400E',
            'dot'   => '#D97706',
            'label' => 'Menunggu Bayar',
            'a11y'  => 'Status: Menunggu Pembayaran',
            'pulse' => false,
        ],
        'paid' => [
            'bg'    => '#DBEAFE', // warna biru
            'text'  => '#1E40AF',
            'dot'   => '#2563EB',
            'label' => 'Lunas',
            'a11y'  => 'Status: Sudah Dibayar, Menunggu Dimasak',
            'pulse' => false,
        ],
        'cooking' => [
            'bg'    => '#EDE9FE', // warna violet
            'text'  => '#5B21B6',
            'dot'   => '#7C3AED',
            'label' => 'Dimasak',
            'a11y'  => 'Status: Sedang Disiapkan di Dapur',
            'pulse' => true,  /* dot berdenyut: indikator proses aktif */
        ],
        'completed' => [
            'bg'    => '#D1FAE5', // warna emerald
            'text'  => '#065F46',
            'dot'   => '#059669',
            'label' => 'Selesai',
            'a11y'  => 'Status: Pesanan Selesai',
            'pulse' => false,
        ],
        'canceled' => [
            'bg'    => '#FEE2E2', // warna merah
            'text'  => '#991B1B',
            'dot'   => '#DC2626',
            'label' => 'Dibatalkan',
            'a11y'  => 'Status: Pesanan Dibatalkan',
            'pulse' => false,
        ],
    ];

    /* fallback aman untuk status yang tidak terdaftar di konfigurasi */
    $cfg = $statusConfig[$status] ?? [
        'bg'    => '#F3F4F6',
        'text'  => '#374151',
        'dot'   => '#9CA3AF',
        'label' => ucfirst(str_replace('_', ' ', $status)),
        'a11y'  => 'Status: ' . $status,
        'pulse' => false,
    ];
@endphp

{{-- @once: keyframes hanya di-output satu kali meski badge dirender berkali-kali di satu halaman --}}
@once
@push('head-scripts')
<style>
    /* animasi denyut: eksklusif untuk dot status 'cooking' */
    @keyframes pods-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.45; transform: scale(0.88); }
    }
</style>
@endpush
@endonce

{{-- badge: role=status + aria-label untuk aksesibilitas screen reader --}}
<span
    role="status"
    aria-label="{{ $cfg['a11y'] }}"
    style="
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px 3px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        background-color: {{ $cfg['bg'] }};
        color: {{ $cfg['text'] }};
        white-space: nowrap;
        user-select: none;
    "
>
    {{-- dot indikator: pulse hanya aktif saat status cooking --}}
    <span
        aria-hidden="true"
        style="
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            flex-shrink: 0;
            background-color: {{ $cfg['dot'] }};
            {{ $cfg['pulse'] ? 'animation: pods-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;' : '' }}
        "
    ></span>
    {{ $cfg['label'] }}
</span>