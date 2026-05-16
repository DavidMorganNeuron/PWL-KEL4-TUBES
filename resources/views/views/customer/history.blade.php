{{-- CUSTOMER HISTORY: riwayat pesanan pelanggan --}}
{{-- status: pending_payment | paid | cooking | completed | canceled --}}
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

        {{-- EMPTY STATE --}}
        <div
            style="
                background: #FFFFFF;
                border-radius: 1.125rem;
                border: 1px solid #EDE0CC;
                box-shadow: 0 1px 4px rgba(28,15,10,0.06);
                padding: 5rem 2rem;
                text-align: center;
            "
            role="status"
            aria-live="polite"
        >
            <div style="font-size: 3.5rem; margin-bottom: 1rem; line-height: 1;" aria-hidden="true">☕</div>
            <h2 style="font-family: var(--font-serif); font-size: 1.375rem; font-weight: 700; color: #1C0F0A; margin-bottom: 0.625rem;">
                Belum Ada Pesanan
            </h2>
            <p style="font-size: 0.875rem; color: #A08060; font-weight: 300; max-width: 280px; margin: 0 auto 2rem; line-height: 1.65;">
                Kamu belum pernah memesan di Pod's. Yuk mulai petualangan rasamu!
            </p>
            <a
                href="{{ route('orders.branch') }}"
                style="
                    display: inline-block;
                    padding: 0.75rem 1.875rem;
                    border-radius: 9999px;
                    background: #C8813B;
                    color: #1C0F0A;
                    font-size: 0.9375rem;
                    font-weight: 600;
                    letter-spacing: 0.04em;
                    text-decoration: none;
                    box-shadow: 0 6px 18px rgba(200,129,59,0.3);
                    transition: background 0.2s, transform 0.1s;
                "
                onmouseover="this.style.background='#D99045'"
                onmouseout="this.style.background='#C8813B'"
                onmousedown="this.style.transform='scale(0.96)'"
                onmouseup="this.style.transform='scale(1)'"
            >
                Mulai Pesan Sekarang
            </a>
        </div>

        @else

        {{-- TABEL RIWAYAT  --}}
        <div style="
            background: #FFFFFF;
            border-radius: 1.125rem;
            border: 1px solid #EDE0CC;
            box-shadow: 0 1px 4px rgba(28,15,10,0.06);
            overflow: hidden;
            margin-bottom: 1.5rem;
        ">
            {{-- overflow-x: auto agar horizontal scroll jika konten melebihi container --}}
            <div style="overflow-x: auto;">
                <table
                    style="width: 100%; text-align: left; border-collapse: collapse;"
                    role="table"
                    aria-label="Tabel riwayat pesanan kamu di Pod's"
                >

                    {{-- THEAD --}}
                    <thead>
                        <tr style="background: #1C0F0A;">
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap;">
                                No. Pesanan
                            </th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap;">
                                Tanggal
                            </th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap;">
                                Cabang
                            </th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap;">
                                Item
                            </th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap; text-align: right;">
                                Total
                            </th>
                            <th scope="col" style="padding: 1rem 1.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(245,233,211,0.55); white-space: nowrap; text-align: center;">
                                Status
                            </th>
                        </tr>
                    </thead>

                    {{-- TBODY --}}
                    <tbody>
                        @foreach($orders as $order)
                        <tr
                            style="border-bottom: 1px solid #EDE0CC; transition: background 0.12s;"
                            onmouseover="this.style.background='#FBF6EE'"
                            onmouseout="this.style.background='transparent'"
                        >

                            {{-- nomor pesanan --}}
                            <td style="padding: 1rem 1.375rem;">
                                <span style="font-family: 'Courier New', Courier, monospace; font-size: 0.875rem; font-weight: 600; color: #1C0F0A; letter-spacing: -0.01em;">
                                    #{{ $order->order_number }}
                                </span>
                            </td>

                            {{-- tanggal + jam --}}
                            <td style="padding: 1rem 1.375rem; white-space: nowrap;">
                                <p style="font-size: 0.875rem; color: #1C0F0A; font-weight: 500; margin-bottom: 0.125rem;">
                                    {{ $order->created_at->format('d M Y') }}
                                </p>
                                <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">
                                    {{ $order->created_at->format('H:i') }} WIB
                                </p>
                            </td>

                            {{-- nama cabang --}}
                            <td style="padding: 1rem 1.375rem; white-space: nowrap;">
                                <span style="font-size: 0.875rem; color: #1C0F0A; font-weight: 500;">
                                    {{ $order->branch->name ?? '—' }}
                                </span>
                            </td>

                            {{-- item summary: maks 2 preview + sisa --}}
                            <td style="padding: 1rem 1.375rem;">
                                @php
                                    $preview   = $order->items->take(2);  /* preview 2 item pertama */
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
                                    <p style="font-size: 0.75rem; color: #C8813B; font-weight: 600; margin: 0;">
                                        +{{ $remaining }} item lainnya
                                    </p>
                                    @endif
                                </div>
                            </td>

                            {{-- total harga --}}
                            <td style="padding: 1rem 1.375rem; text-align: right; white-space: nowrap;">
                                <span style="font-size: 0.9375rem; font-weight: 700; color: #1C0F0A;">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- status badge --}}
                            <td style="padding: 1rem 1.375rem; text-align: center;">
                                @include('customer.layouts.status_badge', ['status' => $order->status])
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- FOOTER TABEL: count + CTA buat pesanan baru --}}
            <div style="
                padding: 0.875rem 1.375rem;
                background: #FBF6EE;
                border-top: 1px solid #EDE0CC;
                display: flex;
                align-items: center;
                justify-content: space-between;
            ">
                <p style="font-size: 0.8125rem; color: #A08060; font-weight: 300;">
                    Menampilkan <strong style="font-weight: 600; color: #3D1F0F;">{{ $orders->count() }}</strong> pesanan terakhir
                </p>
                <a
                    href="{{ route('orders.branch') }}"
                    style="font-size: 0.8125rem; font-weight: 600; color: #C8813B; text-decoration: none; transition: text-decoration 0.15s;"
                    onmouseover="this.style.textDecoration='underline'"
                    onmouseout="this.style.textDecoration='none'"
                >
                    + Buat Pesanan Baru
                </a>
            </div>
        </div>

        {{-- LEGENDA STATUS --}}
        <div style="
            background: #FFFFFF;
            border-radius: 1.125rem;
            border: 1px solid #EDE0CC;
            box-shadow: 0 1px 4px rgba(28,15,10,0.06);
            padding: 1.25rem 1.5rem;
        ">
            <h2 style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #A08060; margin-bottom: 0.875rem;">
                Keterangan Status
            </h2>
            <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: nowrap;" role="list" aria-label="Legenda status pesanan">
                @foreach([
                    ['pending', ''],
                    ['paid',            ''],
                    ['cooking',         ''],
                    ['completed',       ''],
                    ['canceled',        ''],
                ] as [$s, $keterangan])
                <div role="listitem" style="display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
                    @include('customer.layouts.status_badge', ['status' => $s])
                    <span style="font-size: 0.75rem; color: #A08060;">{{ $keterangan }}</span>
                </div>
                @endforeach
            </div>
        </div>

        @endif
    </div>

</div>

@endsection

@push('head-scripts')
<style>
    /* font mono untuk nomor pesanan */
    .font-mono { font-family: 'Courier New', Courier, monospace; letter-spacing: -0.01em; }
</style>
@endpush