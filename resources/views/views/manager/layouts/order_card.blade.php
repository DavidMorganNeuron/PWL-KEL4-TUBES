{{-- KARTU PESANAN KDS --}}

@php
    /* konfigurasi visual per status pesanan */
    $statusConfig = [
        'paid' => [
            'bg'        => '#DBEAFE',
            'text'      => '#1E40AF',
            'dot'       => '#2563EB',
            'label'     => 'Menunggu Diproses',
            'pulse'     => false,
            'border'    => '#BFDBFE',
        ],
        'cooking' => [
            'bg'        => '#FEF3C7',
            'text'      => '#92400E',
            'dot'       => '#D97706',
            'label'     => 'Sedang Dimasak',
            'pulse'     => true,
            'border'    => '#FDE68A',
        ],
        'completed' => [
            'bg'        => '#D1FAE5',
            'text'      => '#065F46',
            'dot'       => '#059669',
            'label'     => 'Selesai',
            'pulse'     => false,
            'border'    => '#A7F3D0',
        ],
        'canceled' => [
            'bg'        => '#FEE2E2',
            'text'      => '#991B1B',
            'dot'       => '#DC2626',
            'label'     => 'Dibatalkan',
            'pulse'     => false,
            'border'    => '#FECACA',
        ],
    ];

    $cfg = $statusConfig[$order['status']] ?? [
        'bg' => '#F3F4F6', 'text' => '#374151', 'dot' => '#9CA3AF',
        'label' => ucfirst($order['status']), 'pulse' => false, 'border' => '#E5E7EB',
    ];

    /* warna border kiri kartu berbasis status untuk visual hierarki */
    $cardBorderColor = [
        'paid'      => '#2563EB',
        'cooking'   => '#D97706',
        'completed' => '#059669',
        'canceled'  => '#DC2626',
    ][$order['status']] ?? '#D4C4AE';
@endphp

@once
@push('head-scripts')
<style>
    /* animasi denyut dot status cooking */
    @keyframes kds-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.4; transform: scale(0.85); }
    }
    /* hover kartu: efek angkat ringan */
    .kds-order-card { transition: box-shadow 0.2s, transform 0.2s; }
    .kds-order-card:hover { box-shadow: 0 6px 24px rgba(28,15,10,0.12) !important; transform: translateY(-2px); }
</style>
@endpush
@endonce

<article
    class="kds-order-card mgr-card"
    style="border-left: 3.5px solid {{ $cardBorderColor }}; overflow: hidden;"
    aria-label="Pesanan {{ $order['order_number'] }}, status: {{ $cfg['label'] }}"
>

    {{-- HEADER KARTU: nomor pesanan + badge status --}}
    <div style="padding: 1rem 1.125rem 0.75rem; border-bottom: 1px solid #F0E8DC; display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;">

        <div>
            <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--pods-muted); margin-bottom: 0.125rem;">
                Nomor Pesanan
            </p>
            <p class="font-serif" style="font-size: 1.0625rem; font-weight: 700; color: var(--pods-espresso); letter-spacing: 0.01em;">
                {{ $order['order_number'] }}
            </p>
        </div>

        {{-- badge status --}}
        <span
            role="status"
            aria-label="Status: {{ $cfg['label'] }}"
            style="
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 3px 10px 3px 8px;
                border-radius: 9999px;
                font-size: 0.6875rem;
                font-weight: 600;
                letter-spacing: 0.02em;
                background-color: {{ $cfg['bg'] }};
                color: {{ $cfg['text'] }};
                border: 1px solid {{ $cfg['border'] }};
                white-space: nowrap;
                flex-shrink: 0;
            "
        >
            <span
                aria-hidden="true"
                style="
                    width: 6px; height: 6px; border-radius: 9999px; flex-shrink: 0;
                    background-color: {{ $cfg['dot'] }};
                    {{ $cfg['pulse'] ? 'animation: kds-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;' : '' }}
                "
            ></span>
            {{ $cfg['label'] }}
        </span>
    </div>

    {{-- META PESANAN: nama pelanggan + waktu masuk --}}
    <div style="padding: 0.625rem 1.125rem; display: flex; justify-content: space-between; align-items: center; background: #FFFBF4; border-bottom: 1px solid #F0E8DC;">
        <span style="font-size: 0.8125rem; font-weight: 500; color: var(--pods-espresso);">
            {{ $order['customer_name'] }}
        </span>
        <span style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300;">
            {{ $order['created_at'] }}
        </span>
    </div>

    {{-- DAFTAR ITEM PESANAN --}}
    <ul style="list-style: none; margin: 0; padding: 0.75rem 1.125rem;" role="list" aria-label="Item pesanan">
        @foreach($order['items'] as $item)
        <li style="display: flex; justify-content: space-between; align-items: baseline; padding: 0.3125rem 0; border-bottom: 1px dashed #F0E8DC; {{ $loop->last ? 'border-bottom: none;' : '' }}">
            <span style="font-size: 0.875rem; color: var(--pods-espresso); font-weight: 400;">
                <span style="display: inline-block; min-width: 1.5rem; font-weight: 700; color: var(--pods-caramel);">{{ $item['qty'] }}×</span>
                {{ $item['name'] }}
            </span>
        </li>
        @endforeach
    </ul>

    {{-- FOOTER KARTU: total + tombol aksi --}}
    <div style="padding: 0.75rem 1.125rem 1rem; border-top: 1px solid #F0E8DC; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">

        <div>
            <p style="font-size: 0.6875rem; color: var(--pods-muted); font-weight: 300; margin-bottom: 0.125rem;">Total</p>
            <p style="font-size: 1rem; font-weight: 600; color: var(--pods-espresso); font-variant-numeric: tabular-nums;">
                Rp {{ number_format($order['grand_total'], 0, ',', '.') }}
            </p>
        </div>

        {{-- tombol aksi: tampil kondisional berdasarkan status --}}
        <div style="display: flex; gap: 0.5rem; align-items: center;">

            @if($order['status'] === 'paid')
                {{-- tombol: mulai memasak → ubah status ke cooking --}}
                <button
                    type="button"
                    class="pods-btn-primary kds-btn-cook"
                    data-order-id="{{ $order['id'] }}"
                    style="font-size: 0.8125rem; padding: 0.5rem 1rem; gap: 0.3rem;"
                    aria-label="Mulai memasak pesanan {{ $order['order_number'] }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Masak
                </button>
                {{-- tombol cancel: aksi destruktif, butuh konfirmasi modal --}}
                <button
                    type="button"
                    class="pods-btn-danger kds-btn-cancel"
                    data-order-id="{{ $order['id'] }}"
                    data-order-number="{{ $order['order_number'] }}"
                    style="font-size: 0.8125rem; padding: 0.5rem 1rem; gap: 0.3rem;"
                    aria-label="Batalkan pesanan {{ $order['order_number'] }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </button>

            @elseif($order['status'] === 'cooking')
                {{-- tombol: selesai dimasak → ubah status ke completed --}}
                <button
                    type="button"
                    class="pods-btn-primary kds-btn-done"
                    data-order-id="{{ $order['id'] }}"
                    style="font-size: 0.8125rem; padding: 0.5rem 1rem; gap: 0.3rem; background: #059669;"
                    aria-label="Tandai selesai pesanan {{ $order['order_number'] }}"
                    onmouseover="this.style.background='#047857'"
                    onmouseout="this.style.background='#059669'"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Selesai
                </button>
                {{-- tombol cancel: masih diizinkan saat cooking --}}
                <button
                    type="button"
                    class="pods-btn-danger kds-btn-cancel"
                    data-order-id="{{ $order['id'] }}"
                    data-order-number="{{ $order['order_number'] }}"
                    style="font-size: 0.8125rem; padding: 0.5rem 1rem; gap: 0.3rem;"
                    aria-label="Batalkan pesanan {{ $order['order_number'] }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </button>

            @else
                {{-- status completed/canceled: tidak ada tombol aksi --}}
                <span style="font-size: 0.75rem; color: var(--pods-muted); font-style: italic; font-weight: 300;">Tidak ada aksi</span>
            @endif

        </div>
    </div>

</article>