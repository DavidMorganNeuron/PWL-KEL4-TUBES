{{-- MANAGER KDS: kitchen display system — antrean pesanan real-time --}}
@extends('manager.layouts.app')

@section('title', "Kitchen Display — Pod's Manager")
@section('page-title', 'Kitchen Display System')

@section('content')

@php
    /* data dummy: simulasi antrean pesanan aktif */
    $orders = [
        [
            'id'            => 1,
            'order_number'  => 'ORD-2026-0044',
            'status'        => 'paid',
            'customer_name' => 'Diana Putri',
            'created_at'    => '14:05',
            'grand_total'   => 78000,
            'items'         => [
                ['name' => 'Caramel Macchiato', 'qty' => 2],
                ['name' => 'Croissant Almond',  'qty' => 1],
            ],
        ],
        [
            'id'            => 2,
            'order_number'  => 'ORD-2026-0045',
            'status'        => 'paid',
            'customer_name' => 'Benny Kusuma',
            'created_at'    => '14:10',
            'grand_total'   => 130000,
            'items'         => [
                ['name' => 'Brown Sugar Latte', 'qty' => 2],
                ['name' => 'Iced Americano',    'qty' => 1],
                ['name' => 'Matcha Latte',      'qty' => 1],
            ],
        ],
        [
            'id'            => 3,
            'order_number'  => 'ORD-2026-0046',
            'status'        => 'cooking',
            'customer_name' => 'Sari Dewi',
            'created_at'    => '14:18',
            'grand_total'   => 52000,
            'items'         => [
                ['name' => 'Iced Americano', 'qty' => 1],
                ['name' => 'Matcha Latte',   'qty' => 1],
            ],
        ],
        [
            'id'            => 4,
            'order_number'  => 'ORD-2026-0047',
            'status'        => 'cooking',
            'customer_name' => 'Andi Wijaya',
            'created_at'    => '14:22',
            'grand_total'   => 95000,
            'items'         => [
                ['name' => 'Caramel Macchiato',    'qty' => 1],
                ['name' => 'Brown Sugar Latte',    'qty' => 2],
            ],
        ],
        [
            'id'            => 5,
            'order_number'  => 'ORD-2026-0040',
            'status'        => 'completed',
            'customer_name' => 'Rizky Hamdani',
            'created_at'    => '13:45',
            'grand_total'   => 45000,
            'items'         => [
                ['name' => 'Iced Americano', 'qty' => 2],
            ],
        ],
        [
            'id'            => 6,
            'order_number'  => 'ORD-2026-0039',
            'status'        => 'completed',
            'customer_name' => 'Lina Hartati',
            'created_at'    => '13:31',
            'grand_total'   => 63000,
            'items'         => [
                ['name' => 'Matcha Latte',      'qty' => 1],
                ['name' => 'Croissant Almond',  'qty' => 1],
            ],
        ],
    ];

    /* partisi pesanan berdasarkan kolom KDS */
    $queuePaid      = array_filter($orders, fn($o) => $o['status'] === 'paid');
    $queueCooking   = array_filter($orders, fn($o) => $o['status'] === 'cooking');
    $queueCompleted = array_filter($orders, fn($o) => $o['status'] === 'completed');

    /* konfigurasi header kolom KDS */
    $columns = [
        ['key' => 'paid',      'label' => 'Menunggu Diproses', 'count' => count($queuePaid),      'accent' => '#2563EB', 'bg' => '#DBEAFE', 'text' => '#1E40AF'],
        ['key' => 'cooking',   'label' => 'Sedang Dimasak',    'count' => count($queueCooking),   'accent' => '#D97706', 'bg' => '#FEF3C7', 'text' => '#92400E'],
        ['key' => 'completed', 'label' => 'Selesai',           'count' => count($queueCompleted), 'accent' => '#059669', 'bg' => '#D1FAE5', 'text' => '#065F46'],
    ];
@endphp

<div style="padding: 1.5rem 2rem; background: #F0E8DC; min-height: calc(100vh - 64px);">

    {{-- ================================================================
         SUBHEADER: ringkasan antrean
    ================================================================ --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">

        <div style="display: flex; align-items: center; gap: 1.5rem;">
            {{-- jumlah total pesanan aktif --}}
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 0.8125rem; color: var(--pods-muted); font-weight: 300;">Total antrean aktif:</span>
                <span style="font-size: 0.9375rem; font-weight: 700; color: var(--pods-espresso);">
                    {{ count($queuePaid) + count($queueCooking) }} pesanan
                </span>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 0.625rem; background: #FFFDF9; border: 1px solid #EDE0CC; border-radius: 9999px; padding: 0.375rem 1rem;">
            <span style="width: 7px; height: 7px; border-radius: 9999px; background: #059669; animation: kds-dot-pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;" aria-hidden="true"></span>
            <span style="font-size: 0.75rem; font-weight: 500; color: var(--pods-espresso);">Live</span>
            <span id="kds-refresh-countdown" style="font-size: 0.75rem; color: var(--pods-muted); font-variant-numeric: tabular-nums;">· refresh dalam 30s</span>
        </div>

    </div>

    {{-- ================================================================
         GRID KITCHEN DISPLAY
    ================================================================ --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; align-items: flex-start;">

        @foreach($columns as $col)
        @php
            /* ambil data pesanan sesuai kolom */
            $colOrders = match($col['key']) {
                'paid'      => $queuePaid,
                'cooking'   => $queueCooking,
                'completed' => $queueCompleted,
                default     => [],
            };
        @endphp

        <div>
            {{-- header kolom: label + jumlah pesanan --}}
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.875rem; padding: 0 0.125rem;">
                <h2 style="font-size: 0.8125rem; font-weight: 700; color: var(--pods-espresso); letter-spacing: 0.04em; text-transform: uppercase;">
                    {{ $col['label'] }}
                </h2>
                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 24px; height: 24px; padding: 0 6px; border-radius: 9999px; background: {{ $col['bg'] }}; color: {{ $col['text'] }}; font-size: 0.75rem; font-weight: 700;">
                    {{ $col['count'] }}
                </span>
            </div>

            {{-- daftar kartu pesanan di kolom ini --}}
            <div style="display: flex; flex-direction: column; gap: 0.875rem;">

                @forelse($colOrders as $order)
                    @include('manager.layouts.order_card', ['order' => $order])
                @empty
                {{-- empty state kolom --}}
                <div style="background: rgba(255,253,249,0.6); border: 1.5px dashed #EDE0CC; border-radius: 12px; padding: 2.5rem 1.5rem; text-align: center;">
                    <p style="font-size: 0.875rem; color: var(--pods-muted); font-weight: 300;">Tidak ada pesanan</p>
                </div>
                @endforelse

            </div>
        </div>
        @endforeach

    </div>

</div>

{{-- ================================================================
     MODAL CANCEL: pop-up konfirmasi + input alasan
================================================================ --}}
@push('head-scripts')
<style>
    @keyframes kds-dot-pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.35; }
    }
    @keyframes kds-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.4; transform: scale(0.85); }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    /* tangkap klik dari semua tombol aksi di KDS */
    document.addEventListener('click', function (e) {

        /* --- tombol MASAK: paid → cooking --- */
        const btnCook = e.target.closest('.kds-btn-cook');
        if (btnCook) {
            const orderId = btnCook.dataset.orderId;
            window.SwalModal.fire({
                title:             'Mulai Memasak?',
                text:              'Status pesanan akan berubah menjadi "Sedang Dimasak".',
                icon:              'question',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Mulai Masak',
                cancelButtonText:  'Batal',
                reverseButtons:    true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.SwalToast.fire({ icon: 'success', title: 'Status diperbarui ke Sedang Dimasak' });
                }
            });
            return;
        }

        /* --- tombol SELESAI: cooking → completed --- */
        const btnDone = e.target.closest('.kds-btn-done');
        if (btnDone) {
            const orderId = btnDone.dataset.orderId;
            window.SwalModal.fire({
                title:             'Pesanan Selesai?',
                text:              'Status pesanan akan berubah menjadi "Selesai & Siap Diambil".',
                icon:              'question',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Tandai Selesai',
                cancelButtonText:  'Batal',
                reverseButtons:    true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.SwalToast.fire({ icon: 'success', title: 'Pesanan ditandai Selesai' });
                }
            });
            return;
        }

        /* --- tombol BATAL: dengan input alasan wajib diisi --- */
        const btnCancel = e.target.closest('.kds-btn-cancel');
        if (btnCancel) {
            const orderId     = btnCancel.dataset.orderId;
            const orderNumber = btnCancel.dataset.orderNumber;

            /* cancel membutuhkan alasan eksplisit */
            window.SwalModal.fire({
                title:             'Batalkan Pesanan?',
                html: `
                    <p style="font-size:0.875rem; color:#6B7280; margin-bottom:1rem;">
                        Pesanan <strong>${orderNumber}</strong> akan dibatalkan dan stok akan dikembalikan.
                    </p>
                    <textarea
                        id="swal-cancel-reason"
                        placeholder="Tulis alasan pembatalan (wajib diisi)..."
                        style="width:100%; padding:0.625rem 0.875rem; border:1.5px solid #D1D5DB; border-radius:8px; font-size:0.875rem; font-family:inherit; resize:vertical; min-height:80px; outline:none;"
                        onfocus="this.style.borderColor='#C8813B'"
                        onblur="this.style.borderColor='#D1D5DB'"
                    ></textarea>
                `,
                icon:              'warning',
                showCancelButton:  true,
                confirmButtonText: 'Batalkan Pesanan',
                confirmButtonColor:'#DC2626',
                cancelButtonText:  'Kembali',
                reverseButtons:    true,
                preConfirm: function () {
                    const reason = document.getElementById('swal-cancel-reason').value.trim();
                    if (!reason) {
                        Swal.showValidationMessage('Alasan pembatalan wajib diisi.');
                        return false;
                    }
                    return reason;
                },
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.SwalToast.fire({ icon: 'info', title: 'Pesanan berhasil dibatalkan' });
                }
            });
        }
    });

    (function startCountdown() {
        const el = document.getElementById('kds-refresh-countdown');
        if (!el) return;
        let seconds = 30;
        setInterval(function () {
            seconds--;
            if (seconds <= 0) seconds = 30;
            el.textContent = '· refresh dalam ' + seconds + 's';
        }, 1000);
    }());
}());
</script>
@endpush

@endsection