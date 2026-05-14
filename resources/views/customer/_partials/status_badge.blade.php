{{-- PARTIAL: STATUS BADGE PESANAN --}}
{{-- reusable di: history.blade.php, orders/payment.blade.php, orders/success.blade.php --}}
{{-- variabel wajib: $status (string) --}}
@php
    /* map setiap status ke konfigurasi visual & aksesibilitas */
    $statusConfig = [
        'pending_payment' => [
            'bg'    => '#FEF3C7',  // amber-100
            'text'  => '#92400E',  // amber-800
            'dot'   => '#D97706',  // amber-600
            'label' => 'Menunggu Bayar',
            'a11y'  => 'Status: Menunggu Pembayaran',
            'pulse' => false,
        ],
        'paid' => [
            'bg'    => '#DBEAFE',  // blue-100
            'text'  => '#1E40AF',  // blue-800
            'dot'   => '#2563EB',  // blue-600
            'label' => 'Lunas',
            'a11y'  => 'Status: Sudah Dibayar, Menunggu Dimasak',
            'pulse' => false,
        ],
        'cooking' => [
            'bg'    => '#EDE9FE',  // violet-100
            'text'  => '#5B21B6',  // violet-800
            'dot'   => '#7C3AED',  // violet-600
            'label' => 'Dimasak',
            'a11y'  => 'Status: Sedang Disiapkan di Dapur',
            'pulse' => true,  /* indikator aktif: dot berdenyut */
        ],
        'completed' => [
            'bg'    => '#D1FAE5',  // emerald-100
            'text'  => '#065F46',  // emerald-800
            'dot'   => '#059669',  // emerald-600
            'label' => 'Selesai',
            'a11y'  => 'Status: Pesanan Selesai',
            'pulse' => false,
        ],
        'canceled' => [
            'bg'    => '#FEE2E2',  // red-100
            'text'  => '#991B1B',  // red-800
            'dot'   => '#DC2626',  // red-600
            'label' => 'Dibatalkan',
            'a11y'  => 'Status: Pesanan Dibatalkan',
            'pulse' => false,
        ],
    ];

    /* fallback aman untuk status yang tidak terdaftar */
    $cfg = $statusConfig[$status] ?? [
        'bg'    => '#F3F4F6',
        'text'  => '#374151',
        'dot'   => '#9CA3AF',
        'label' => ucfirst(str_replace('_', ' ', $status)),
        'a11y'  => 'Status: ' . $status,
        'pulse' => false,
    ];
@endphp

{{-- keyframes pulse: didefinisikan inline agar tidak bergantung pada Tailwind --}}
{{-- @once memastikan style ini hanya di-output satu kali per halaman --}}
@once
@push('head-scripts')
<style>
    /* animasi denyut: hanya untuk dot status 'cooking' */
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
    {{-- dot indikator status: pulse hanya saat cooking --}}
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