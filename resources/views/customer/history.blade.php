{{-- CUSTOMER HISTORY --}}
@extends('customer.layouts.app')

@section('title', "History — Pod's")

@section('content')

<div style="padding-top: 72px; min-height: 100vh; background: #FBF6EE;">

    {{-- ================================================================
         PAGE HEADER
    ================================================================ --}}
    <div style="background: #FBF6EE; padding: 2.5rem 0 0;">
        <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">
            <div style="display: flex; align-items: flex-end; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: #C8813B; margin-bottom: 0.25rem;">
                        Pelanggan
                    </p>
                    <h1 style="font-family: var(--font-serif); font-size: 1.875rem; font-weight: 900; color: #1C0F0A;">
                        Riwayat Pesanan
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         MAIN CONTENT AREA
    ================================================================ --}}
    <div style="width: 1280px; margin: 0 auto; padding: 2rem 2.5rem 4rem;">

        @if($orders->isEmpty())
        <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.06); padding: 5rem 2rem; text-align: center;" role="status" aria-live="polite">
            <div style="font-size: 3.5rem; margin-bottom: 1rem; line-height: 1;" aria-hidden="true">☕</div>
            <h2 style="font-family: var(--font-serif); font-size: 1.375rem; font-weight: 700; color: #1C0F0A; margin-bottom: 0.625rem;">Belum Ada Pesanan</h2>
            <p style="font-size: 0.875rem; color: #A08060; font-weight: 300; max-width: 280px; margin: 0 auto 2rem; line-height: 1.65;">Kamu belum pernah memesan di Pod's. Yuk mulai!</p>
            <a href="{{ route('orders.branch') }}" style="display: inline-block; padding: 0.75rem 1.875rem; border-radius: 9999px; background: #C8813B; color: #1C0F0A; font-size: 0.9375rem; font-weight: 600; text-decoration: none; box-shadow: 0 6px 18px rgba(200,129,59,0.3); transition: background 0.2s;" onmouseover="this.style.background='#D99045'" onmouseout="this.style.background='#C8813B'">
                Mulai Pesan Sekarang
            </a>
        </div>

        @else

        {{-- TABEL RIWAYAT --}}
        <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.06); overflow: hidden; margin-bottom: 1.5rem;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; text-align: left; border-collapse: collapse;" role="table" aria-label="Tabel riwayat pesanan">
                    <thead>
                        <tr style="background: #1C0F0A;">
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap;">No. Pesanan</th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap;">Tanggal</th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap;">Cabang</th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap;">Item</th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap; text-align: right;">Total</th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap; text-align: center;">Status</th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap; text-align: center;">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr
                            style="border-bottom: 1px solid #EDE0CC; transition: background 0.12s; cursor: pointer;"
                            onmouseover="this.style.background='#FBF6EE'"
                            onmouseout="this.style.background='transparent'"
                            onclick="openOrderDetail({{ $order->id_orders }})"
                            role="button"
                            tabindex="0"
                            aria-label="Lihat detail pesanan {{ $order->order_number }}"
                            onkeydown="if(event.key==='Enter'||event.key===' ') openOrderDetail({{ $order->id_orders }})"
                        >
                            <td style="padding: 1rem 1.375rem;">
                                <span style="font-family: 'Courier New', Courier, monospace; font-size: 0.875rem; font-weight: 600; color: #1C0F0A;">#{{ $order->order_number }}</span>
                            </td>
                            <td style="padding: 1rem 1.375rem; white-space: nowrap;">
                                <p style="font-size: 0.875rem; color: #1C0F0A; font-weight: 500; margin-bottom: 0.125rem;">{{ $order->created_at->format('d M Y') }}</p>
                                <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">{{ $order->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td style="padding: 1rem 1.375rem; white-space: nowrap;">
                                <span style="font-size: 0.875rem; color: #1C0F0A; font-weight: 500;">{{ $order->branch->name ?? '—' }}</span>
                            </td>
                            <td style="padding: 1rem 1.375rem;">
                                @php
                                    $preview   = $order->items->take(2);
                                    $remaining = $order->items->count() - $preview->count();
                                @endphp
                                <div style="display: flex; flex-direction: column; gap: 0.125rem;">
                                    @foreach($preview as $item)
                                    <p style="font-size: 0.875rem; color: #1C0F0A; margin: 0;">
                                        {{ $item->product->name ?? '—' }}
                                        <span style="color: #A08060; font-weight: 300;">×{{ $item->qty }}</span>
                                    </p>
                                    @endforeach
                                    @if($remaining > 0)
                                    <p style="font-size: 0.75rem; color: #C8813B; font-weight: 600; margin: 0;">+{{ $remaining }} item lainnya</p>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 1rem 1.375rem; text-align: right; white-space: nowrap;">
                                <span style="font-size: 0.9375rem; font-weight: 700; color: #1C0F0A;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </td>
                            <td style="padding: 1rem 1.375rem; text-align: center;">
                                @include('customer.layouts.status_badge', ['status' => $order->status])
                            </td>
                            <td style="padding: 1rem 1.375rem; text-align: center;">
                                <span style="font-size: 0.8125rem; font-weight: 500; color: #C8813B; text-decoration: underline;">Lihat →</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 0.875rem 1.375rem; background: #FBF6EE; border-top: 1px solid #EDE0CC; display: flex; align-items: center; justify-content: space-between;">
                <p style="font-size: 0.8125rem; color: #A08060; font-weight: 300;">
                    Menampilkan <strong style="font-weight: 600; color: #3D1F0F;">{{ $orders->count() }}</strong> pesanan terakhir
                </p>
                <a href="{{ route('orders.branch') }}" style="font-size: 0.8125rem; font-weight: 600; color: #C8813B; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                    + Buat Pesanan Baru
                </a>
            </div>
        </div>

        @endif
    </div>
</div>

{{-- ================================================================
     DETAIL ORDER
================================================================ --}}
<div id="order-detail-overlay" style="display:none; position:fixed; inset:0; background:rgba(28,15,10,0.55); z-index:9000; overflow-y:auto; padding: 2rem;" aria-modal="true" role="dialog" aria-label="Detail pesanan">
    <div id="order-detail-modal" style="background:#FFFFFF; border-radius:1.25rem; max-width:560px; margin:0 auto; overflow:hidden; box-shadow: 0 24px 60px rgba(28,15,10,0.25);">

        {{-- header --}}
        <div style="background: linear-gradient(135deg, #1C0F0A 0%, #3D1F0F 100%); padding: 1.375rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:rgba(245,233,211,0.45); margin-bottom:0.2rem;">Detail Pesanan</p>
                <h2 id="modal-order-number" style="font-family:var(--font-serif); font-size:1.0625rem; font-weight:700; color:#F5E9D3; font-family:'Courier New',monospace;"></h2>
            </div>
            <button onclick="closeOrderDetail()" style="background:rgba(245,233,211,0.1); border:none; border-radius:0.5rem; padding:0.5rem; cursor:pointer; color:rgba(245,233,211,0.7); transition:background 0.15s;" onmouseover="this.style.background='rgba(245,233,211,0.2)'" onmouseout="this.style.background='rgba(245,233,211,0.1)'" aria-label="Tutup modal">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- cabang + tanggal + status --}}
        <div id="modal-meta" style="padding:1rem 1.5rem; border-bottom:1px solid #EDE0CC; display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; background:#FBF6EE;"></div>

        {{-- cancel_reason: hanya muncul jika status = canceled --}}
        <div id="modal-cancel-reason" style="display:none; padding:0.875rem 1.5rem; background:#FEF2F2; border-bottom:1px solid #FECACA;">
            <p style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.1em; color:#991B1B; margin-bottom:0.25rem;">Alasan Pembatalan</p>
            <p id="modal-cancel-reason-text" style="font-size:0.875rem; color:#DC2626; font-weight:400;"></p>
        </div>

        {{-- daftar item --}}
        <div id="modal-items" style="padding:0.5rem 1.5rem;"></div>

        {{-- summary finansial --}}
        <div id="modal-summary" style="padding:1rem 1.5rem; background:#FBF6EE; border-top:2px dashed #EDE0CC;"></div>
    </div>
</div>

<script id="orders-data" type="application/json">
@php
    $ordersJson = $orders->map(function ($order) {
        return [
            'id'            => $order->id_orders,
            'order_number'  => $order->order_number,
            'branch'        => $order->branch->name ?? '—',
            'date'          => $order->created_at->format('d M Y'),
            'time'          => $order->created_at->format('H:i'),
            'status'        => $order->status,
            'cancel_reason' => $order->cancel_reason,
            'subtotal'      => $order->subtotal,
            'total_discount'=> $order->total_discount,
            'grand_total'   => $order->grand_total,
            'items'         => $order->items->map(fn($i) => [
                'name'          => $i->product->name ?? '—',
                'qty'           => $i->qty,
                'base_price'    => $i->base_price,
                'discount'      => $i->discount_amount,
                'subtotal'      => $i->subtotal_price,
            ])->values(),
        ];
    })->keyBy('id');
@endphp
{!! json_encode($ordersJson) !!}
</script>

@push('head-scripts')
<style>
    .font-mono { font-family: 'Courier New', Courier, monospace; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var ordersData = JSON.parse(document.getElementById('orders-data').textContent);

    var statusLabel = {
        'pending_payment': 'Menunggu Bayar',
        'paid':            'Lunas',
        'cooking':         'Dimasak',
        'completed':       'Selesai',
        'canceled':        'Dibatalkan',
    };
    var statusColor = {
        'pending_payment': { bg:'#FEF3C7', text:'#92400E' },
        'paid':            { bg:'#DBEAFE', text:'#1E40AF' },
        'cooking':         { bg:'#EDE9FE', text:'#5B21B6' },
        'completed':       { bg:'#D1FAE5', text:'#065F46' },
        'canceled':        { bg:'#FEE2E2', text:'#991B1B' },
    };

    function formatRp(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }

    window.openOrderDetail = function (orderId) {
        var o = ordersData[orderId];
        if (!o) return;

        /* nomor pesanan */
        document.getElementById('modal-order-number').textContent = '#' + o.order_number;

        /* cabang, tanggal, status */
        var sc = statusColor[o.status] || { bg:'#F3F4F6', text:'#374151' };
        var sl = statusLabel[o.status] || o.status;
        document.getElementById('modal-meta').innerHTML =
            '<div>' +
            '  <p style="font-size:0.7rem;color:#A08060;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.2rem;">Cabang</p>' +
            '  <p style="font-size:0.9375rem;font-weight:600;color:#1C0F0A;">' + o.branch + '</p>' +
            '</div>' +
            '<div>' +
            '  <p style="font-size:0.7rem;color:#A08060;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.2rem;">Waktu</p>' +
            '  <p style="font-size:0.9375rem;font-weight:600;color:#1C0F0A;">' + o.date + '</p>' +
            '  <p style="font-size:0.75rem;color:#A08060;">' + o.time + ' WIB</p>' +
            '</div>' +
            '<div style="grid-column:1/-1;">' +
            '  <p style="font-size:0.7rem;color:#A08060;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.375rem;">Status</p>' +
            '  <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px 3px 8px;border-radius:9999px;font-size:0.8125rem;font-weight:600;background:' + sc.bg + ';color:' + sc.text + ';">' + sl + '</span>' +
            '</div>';

        /* cancel_reason: tampilkan hanya untuk status canceled */
        var crBox  = document.getElementById('modal-cancel-reason');
        var crText = document.getElementById('modal-cancel-reason-text');
        if (o.status === 'canceled' && o.cancel_reason) {
            crText.textContent    = o.cancel_reason;
            crBox.style.display   = 'block';
        } else {
            crBox.style.display   = 'none';
        }

        /* daftar item */
        var itemsHtml = '';
        o.items.forEach(function (item) {
            itemsHtml +=
                '<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.75rem;padding:0.75rem 0;border-bottom:1px solid #F5ECE0;">' +
                '  <div style="flex:1;min-width:0;">' +
                '    <p style="font-size:0.9375rem;font-weight:500;color:#1C0F0A;">' + item.name + '</p>' +
                '    <p style="font-size:0.75rem;color:#A08060;font-weight:300;">Rp ' + Number(item.base_price).toLocaleString('id-ID') + ' × ' + item.qty + '</p>' +
                (item.discount > 0 ? '<p style="font-size:0.75rem;color:#059669;">Diskon: −Rp ' + Number(item.discount).toLocaleString('id-ID') + '</p>' : '') +
                '  </div>' +
                '  <span style="font-size:0.9375rem;font-weight:600;color:#3D1F0F;white-space:nowrap;">Rp ' + Number(item.subtotal).toLocaleString('id-ID') + '</span>' +
                '</div>';
        });
        document.getElementById('modal-items').innerHTML = itemsHtml || '<p style="padding:1rem 0;color:#A08060;font-size:0.875rem;">—</p>';

        /* summary finansial */
        var sumHtml =
            (o.total_discount > 0
                ? '<div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;"><span style="font-size:0.875rem;color:#A08060;">Subtotal</span><span style="font-size:0.875rem;color:#1C0F0A;">' + formatRp(o.subtotal) + '</span></div>' +
                  '<div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;"><span style="font-size:0.875rem;color:#059669;">Diskon</span><span style="font-size:0.875rem;color:#059669;">−' + formatRp(o.total_discount) + '</span></div>'
                : '') +
            '<div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #EDE0CC;padding-top:0.75rem;margin-top:0.25rem;">' +
            '  <span style="font-size:0.9375rem;font-weight:600;color:#A08060;">Total Dibayar</span>' +
            '  <span style="font-family:var(--font-serif);font-size:1.375rem;font-weight:900;color:#1C0F0A;">' + formatRp(o.grand_total) + '</span>' +
            '</div>';
        document.getElementById('modal-summary').innerHTML = sumHtml;

        /* tampilkan overlay */
        document.getElementById('order-detail-overlay').style.display = 'block';
        document.body.style.overflow = 'hidden';
    };

    window.closeOrderDetail = function () {
        document.getElementById('order-detail-overlay').style.display = 'none';
        document.body.style.overflow = '';
    };

    /* tutup modal saat klik overlay */
    document.getElementById('order-detail-overlay').addEventListener('click', function (e) {
        if (e.target === this) closeOrderDetail();
    });

    /* tutup modal dengan Escape */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeOrderDetail();
    });
}());
</script>
@endpush

@endsection