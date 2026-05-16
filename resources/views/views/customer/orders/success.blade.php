{{-- ORDERS/SUCCESS: struk digital + navigasi post-order --}}
@extends('customer.layouts.app')

@section('title', "Success — Pod's")

@section('content')

<div style="min-height: 100vh; background: #FBF6EE; display: flex; align-items: flex-start; justify-content: center;">

    <div style="width: 1280px; margin: 0 auto; padding: 3.5rem 2.5rem 5rem; display: grid; grid-template-columns: 1fr 480px 1fr; gap: 2rem;">

        <div></div>

        {{-- ==========================================
             STRUK DIGITAL
        ========================================== --}}
        <div>

            <div style="animation: receipt-enter 0.5s cubic-bezier(0.22,1,0.36,1) both;">

                {{-- header sukses: banner espresso + ikon centang --}}
                <div style="
                    background: linear-gradient(135deg, #1C0F0A 0%, #3D1F0F 100%);
                    border-radius: 1.25rem 1.25rem 0 0;
                    padding: 2.5rem 2rem;
                    text-align: center;
                ">
                    {{-- ikon centang animasi --}}
                    <div style="
                        width: 64px; height: 64px;
                        border-radius: 9999px;
                        background: #C8813B;
                        display: flex; align-items: center; justify-content: center;
                        margin: 0 auto 1.125rem;
                        box-shadow: 0 0 0 8px rgba(200,129,59,0.15);
                        animation: check-pop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s both;
                    " aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="#1C0F0A" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <h1 style="font-family: var(--font-serif); font-size: 1.75rem; font-weight: 900; color: #F5E9D3; margin-bottom: 0.375rem;">
                        Pembayaran Berhasil!
                    </h1>
                    <p style="font-size: 0.875rem; color: rgba(245,233,211,0.55); font-weight: 300;">
                        Pesanan kamu sedang disiapkan oleh tim Pod's.
                    </p>

                    {{-- nomor pesanan --}}
                    <div style="
                        display: inline-block;
                        margin-top: 1.25rem;
                        padding: 0.5rem 1.25rem;
                        border-radius: 0.625rem;
                        background: rgba(200,129,59,0.15);
                        border: 1px solid rgba(200,129,59,0.3);
                    ">
                        <p style="font-size: 0.7rem; color: rgba(245,233,211,0.4); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.15rem;">Nomor Pesanan</p>
                        <p style="font-family: 'Courier New', Courier, monospace; font-size: 1rem; font-weight: 700; color: #C8813B; letter-spacing: 0.05em;">
                            #{{ $order->order_number }}
                        </p>
                    </div>
                </div>

                {{-- body struk: detail item + summary --}}
                <div style="background: #FFFFFF; border-left: 1px solid #EDE0CC; border-right: 1px solid #EDE0CC;">

                    {{-- info cabang + waktu --}}
                    <div style="padding: 1.25rem 1.75rem; border-bottom: 1px dashed #EDE0CC; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p style="font-size: 0.7rem; color: #A08060; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">Cabang</p>
                            <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A;">{{ $order->branch->name }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.7rem; color: #A08060; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">Waktu</p>
                            <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A;">
                                {{ $order->created_at->format('d M Y') }}
                            </p>
                            <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">{{ $order->created_at->format('H:i') }} WIB</p>
                        </div>
                    </div>

                    {{-- daftar item pesanan --}}
                    <div style="padding: 0 1.75rem;">
                        @foreach($order->items as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.875rem 0; border-bottom: 1px solid #F5ECE0;">
                            <div>
                                <p style="font-size: 0.9375rem; font-weight: 500; color: #1C0F0A;">{{ $item->product->name ?? '—' }}</p>
                                <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">
                                    Rp {{ number_format($item->base_price, 0, ',', '.') }} × {{ $item->qty }}
                                </p>
                            </div>
                            <span style="font-size: 0.9375rem; font-weight: 600; color: #3D1F0F; white-space: nowrap;">
                                Rp {{ number_format($item->subtotal_price, 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    {{-- baris diskon (jika ada) --}}
                    @if($order->total_discount > 0)
                    <div style="padding: 0.875rem 1.75rem; display: flex; justify-content: space-between; border-top: 1px dashed #EDE0CC;">
                        <span style="font-size: 0.875rem; color: #059669; font-weight: 500;">Diskon</span>
                        <span style="font-size: 0.875rem; color: #059669; font-weight: 600;">
                            − Rp {{ number_format($order->total_discount, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif

                    {{-- total grand --}}
                    <div style="padding: 1.125rem 1.75rem; background: #FBF6EE; border-top: 2px dashed #EDE0CC; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.9375rem; font-weight: 600; color: #A08060;">Total Dibayar</span>
                        <span style="font-family: var(--font-serif); font-size: 1.375rem; font-weight: 900; color: #1C0F0A;">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- status badge --}}
                    <div style="padding: 1rem 1.75rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #EDE0CC;">
                        <span style="font-size: 0.8125rem; color: #A08060;">Status Pesanan</span>
                        @include('customer.layouts.status_badge', ['status' => $order->status])
                    </div>
                </div>

                {{-- footer struk: navigasi post-order --}}
                <div style="
                    background: #FBF6EE;
                    border: 1px solid #EDE0CC;
                    border-top: none;
                    border-radius: 0 0 1.25rem 1.25rem;
                    padding: 1.5rem 1.75rem;
                    display: flex;
                    flex-direction: column;
                    gap: 0.75rem;
                ">
                    {{-- CTA primer: pesan lagi --}}
                    <a
                        href="{{ route('orders.branch') }}"
                        style="
                            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
                            padding: 0.875rem;
                            border-radius: 0.75rem;
                            background: #C8813B;
                            color: #1C0F0A;
                            font-size: 0.9375rem; font-weight: 600; letter-spacing: 0.04em;
                            text-decoration: none;
                            box-shadow: 0 4px 14px rgba(200,129,59,0.3);
                            transition: background 0.2s, transform 0.1s;
                        "
                        onmouseover="this.style.background='#D99045'"
                        onmouseout="this.style.background='#C8813B'"
                        onmousedown="this.style.transform='scale(0.98)'"
                        onmouseup="this.style.transform='scale(1)'"
                    >
                        Pesan Lagi
                    </a>

                    {{-- CTA sekunder: lihat riwayat --}}
                    <a
                        href="{{ route('history') }}"
                        style="
                            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
                            padding: 0.875rem;
                            border-radius: 0.75rem;
                            border: 1.5px solid #EDE0CC;
                            color: #A08060;
                            font-size: 0.9375rem; font-weight: 500;
                            text-decoration: none;
                            transition: border-color 0.2s, color 0.2s;
                        "
                        onmouseover="this.style.borderColor='#C8813B'; this.style.color='#C8813B';"
                        onmouseout="this.style.borderColor='#EDE0CC'; this.style.color='#A08060';"
                    >
                        Lihat Riwayat Pesanan
                    </a>

                    {{-- footer teks --}}
                    <p style="font-size: 0.75rem; color: #C8B8A0; text-align: center; font-weight: 300; margin-top: 0.25rem;">
                        Terima kasih telah memesan di Pod's ☕
                    </p>
                </div>

            </div>
        </div>

        <div></div>

    </div>
</div>

@endsection

@push('head-scripts')
<style>
    /* animasi masuk struk digital */
    @keyframes receipt-enter {
        from { opacity: 0; transform: translateY(20px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    /* animasi pop ikon centang */
    @keyframes check-pop {
        from { opacity: 0; transform: scale(0.5); }
        to   { opacity: 1; transform: scale(1); }
    }
</style>
@endpush

@push('scripts')
<script>
/* swal modal — menyambut pelanggan setelah pembayaran berhasil */
(function () {
    window.SwalModal.fire({
        icon:              'success',
        title:             'Pembayaran Berhasil!',
        text:              'Pesanan #{{ $order->order_number }} sudah diterima. Silakan tunggu pesananmu siap.',
        confirmButtonText: 'Oke, Mengerti',
    });
}());
</script>
@endpush