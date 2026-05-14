@extends('layout.app')

@section('title', "Checkout — Pod's")

@section('content')

<div style="min-height: 100vh; background: #FBF6EE;">

    {{-- ================================================================
         PAGE HEADER
    ================================================================ --}}
    <div style="background: #3D1F0F; padding: 2.25rem 0 2rem; border-bottom: 1px solid rgba(245,233,211,0.07);">
        <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">
            @include('customer._partials.order_stepper', ['currentStep' => 3])
            <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: #C8813B; margin: 1.75rem 0 0.375rem;">
                Langkah 3 dari 4
            </p>
            <h1 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 900; color: #F5E9D3; margin-bottom: 0.375rem;">
                Checkout
            </h1>
            <p style="font-size: 0.875rem; color: rgba(245,233,211,0.5); font-weight: 300;">
                Periksa pesanan dan pilih metode pembayaran.
            </p>
        </div>
    </div>

    {{-- ================================================================
         MAIN LAYOUT: 2 kolom
    ================================================================ --}}
    <div style="width: 1280px; margin: 0 auto; padding: 2.5rem 2.5rem 4rem; display: grid; grid-template-columns: 1fr 380px; gap: 1.75rem; align-items: start;">

        {{-- ==========================================
             KOLOM KIRI: Ringkasan Pesanan
        ========================================== --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- KARTU: Info Cabang --}}
            <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.05); padding: 1.375rem 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.875rem;">
                    <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: rgba(200,129,59,0.11); display: flex; align-items: center; justify-content: center; flex-shrink: 0;" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 0.75rem; color: #A08060; font-weight: 500; margin-bottom: 0.1rem; text-transform: uppercase; letter-spacing: 0.05em;">Cabang</p>
                        <p style="font-size: 1rem; font-weight: 700; color: #1C0F0A;">{{ $branch->name }}</p>
                        @if($branch->address)
                        <p style="font-size: 0.8125rem; color: #A08060; font-weight: 300;">{{ $branch->address }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KARTU: Tabel Rincian Pesanan --}}
            <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.05); overflow: hidden;">
                <div style="padding: 1.125rem 1.5rem; border-bottom: 1px solid #EDE0CC;">
                    <h2 style="font-size: 0.9375rem; font-weight: 700; color: #1C0F0A;">Rincian Pesanan</h2>
                </div>

                <table style="width: 100%; border-collapse: collapse;" role="table" aria-label="Rincian item pesanan">
                    <thead>
                        <tr style="background: #FBF6EE;">
                            <th scope="col" style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #A08060;">
                                Menu
                            </th>
                            <th scope="col" style="padding: 0.75rem 1rem; text-align: center; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #A08060;">
                                Qty
                            </th>
                            <th scope="col" style="padding: 0.75rem 1rem; text-align: right; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #A08060;">
                                Harga
                            </th>
                            <th scope="col" style="padding: 0.75rem 1.5rem; text-align: right; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #A08060;">
                                Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach($cart as $productId => $qty)
                        @php
                            $p = $products->get($productId);
                            if (!$p) continue;
                            $lineTotal  = $p->base_price * $qty;
                            $grandTotal += $lineTotal;
                        @endphp
                        <tr style="border-bottom: 1px solid #EDE0CC;">
                            <td style="padding: 1rem 1.5rem;">
                                <p style="font-size: 0.9375rem; font-weight: 500; color: #1C0F0A; margin-bottom: 0.125rem;">{{ $p->name }}</p>
                                <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">{{ $p->category->name ?? '' }}</p>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <span style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A;">{{ $qty }}</span>
                            </td>
                            <td style="padding: 1rem; text-align: right; white-space: nowrap;">
                                <span style="font-size: 0.875rem; color: #A08060;">Rp {{ number_format($p->base_price, 0, ',', '.') }}</span>
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right; white-space: nowrap;">
                                <span style="font-size: 0.9375rem; font-weight: 600; color: #3D1F0F;">Rp {{ number_format($lineTotal, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    {{-- baris total --}}
                    <tfoot>
                        <tr style="background: #FBF6EE;">
                            <td colspan="3" style="padding: 1rem 1rem 1rem 1.5rem; font-size: 0.9375rem; font-weight: 600; color: #A08060; text-align: right;">
                                Total Pembayaran
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right; white-space: nowrap;">
                                <span style="font-family: var(--font-serif); font-size: 1.25rem; font-weight: 900; color: #1C0F0A;">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

        {{-- ==========================================
             KOLOM KANAN: Metode Bayar + Submit
        ========================================== --}}
        <aside style="position: sticky; top: calc(72px + 1.5rem);" aria-label="Metode pembayaran dan konfirmasi">

            <form method="POST" action="/order/checkout" id="form-checkout">
                @csrf

                <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 2px 12px rgba(28,15,10,0.07); overflow: hidden;">

                    {{-- header --}}
                    <div style="background: #1C0F0A; padding: 1rem 1.375rem;">
                        <h2 style="font-size: 0.9375rem; font-weight: 600; color: #F5E9D3;">Metode Pembayaran</h2>
                    </div>

                    {{-- opsi metode bayar --}}
                    <div style="padding: 1.125rem 1.375rem;" role="radiogroup" aria-labelledby="payment-method-label">
                        <p id="payment-method-label" style="font-size: 0.75rem; color: #A08060; font-weight: 500; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.875rem;">
                            Pilih satu metode
                        </p>

                        @foreach([
                            ['qris',    '📱', 'QRIS',     'Scan QR dari aplikasi dompet digital'],
                            ['ewallet', '💳', 'E-Wallet', 'GoPay, OVO, Dana, dan sejenisnya'],
                        ] as [$value, $icon, $label, $desc])
                        <label
                            for="pay_{{ $value }}"
                            id="pay-card-{{ $value }}"
                            style="
                                display: flex; align-items: center; gap: 0.875rem;
                                padding: 0.875rem 1rem;
                                border-radius: 0.75rem;
                                border: 1.5px solid #EDE0CC;
                                margin-bottom: 0.625rem;
                                cursor: pointer;
                                transition: border-color 0.2s, background 0.2s;
                                position: relative;
                            "
                            onmouseover="if(!document.getElementById('pay_{{ $value }}').checked){ this.style.borderColor='rgba(200,129,59,0.4)'; }"
                            onmouseout="if(!document.getElementById('pay_{{ $value }}').checked){ this.style.borderColor='#EDE0CC'; }"
                        >
                            <input
                                type="radio"
                                name="payment_method"
                                id="pay_{{ $value }}"
                                value="{{ $value }}"
                                required
                                style="position: absolute; opacity: 0; width: 0; height: 0;"
                                onchange="updatePayCard(this)"
                            >
                            <span style="font-size: 1.375rem; line-height: 1;" aria-hidden="true">{{ $icon }}</span>
                            <div style="flex: 1; min-width: 0;">
                                <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.1rem;">{{ $label }}</p>
                                <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">{{ $desc }}</p>
                            </div>
                            {{-- checkmark: muncul saat dipilih --}}
                            <div id="pay-check-{{ $value }}" style="display: none; width: 18px; height: 18px; border-radius: 9999px; background: #C8813B; align-items: center; justify-content: center; flex-shrink: 0;" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="#1C0F0A" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </label>
                        @endforeach

                        @error('payment_method')
                        <p style="font-size: 0.8125rem; color: #EF4444; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- divider + total ringkas --}}
                    <div style="margin: 0 1.375rem; border-top: 1px solid #EDE0CC; padding: 1rem 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.875rem; color: #A08060;">Total</span>
                            <span style="font-family: var(--font-serif); font-size: 1.125rem; font-weight: 900; color: #1C0F0A;">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- tombol konfirmasi: disabled sampai metode dipilih --}}
                    <div style="padding: 0 1.375rem 1.375rem;">
                        <button
                            type="submit"
                            id="btn-checkout-submit"
                            disabled
                            style="
                                width: 100%;
                                padding: 0.875rem;
                                border-radius: 0.75rem;
                                border: none;
                                background: #C2B09A;
                                color: rgba(255,255,255,0.55);
                                font-size: 0.9375rem; font-weight: 600; letter-spacing: 0.04em;
                                cursor: not-allowed;
                                transition: background 0.2s, color 0.2s, transform 0.1s;
                            "
                        >
                            Konfirmasi Pesanan
                        </button>
                    </div>

                </div>

                {{-- navigasi: kembali ke menu --}}
                <a
                    href="{{ route('orders.menu') }}"
                    style="display: flex; align-items: center; justify-content: center; gap: 0.375rem; padding: 0.75rem; margin-top: 0.875rem; border-radius: 0.75rem; border: 1px solid #EDE0CC; background: #FFFFFF; color: #A08060; font-size: 0.8125rem; font-weight: 500; text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.borderColor='#C8813B'; this.style.color='#C8813B';"
                    onmouseout="this.style.borderColor='#EDE0CC'; this.style.color='#A08060';"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Menu
                </a>
            </form>

        </aside>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* updatePayCard: sinkronisasi visual card metode bayar + aktifkan tombol submit */
function updatePayCard(radio) {
    /* reset semua card metode ke state default */
    ['qris', 'ewallet'].forEach(function (v) {
        var card  = document.getElementById('pay-card-' + v);
        var check = document.getElementById('pay-check-' + v);
        if (card)  { card.style.borderColor = '#EDE0CC'; card.style.background = 'transparent'; }
        if (check) { check.style.display = 'none'; }
    });

    /* terapkan state aktif pada card yang dipilih */
    var card  = document.getElementById('pay-card-' + radio.value);
    var check = document.getElementById('pay-check-' + radio.value);
    if (card)  { card.style.borderColor = '#C8813B'; card.style.background = 'rgba(200,129,59,0.05)'; }
    if (check) { check.style.display = 'flex'; }

    /* aktifkan tombol submit */
    var btn = document.getElementById('btn-checkout-submit');
    if (btn && btn.disabled) {
        btn.disabled         = false;
        btn.style.background = '#C8813B';
        btn.style.color      = '#1C0F0A';
        btn.style.cursor     = 'pointer';
        btn.addEventListener('mouseover',  function () { btn.style.background = '#D99045'; });
        btn.addEventListener('mouseout',   function () { btn.style.background = '#C8813B'; });
        btn.addEventListener('mousedown',  function () { btn.style.transform  = 'scale(0.98)'; });
        btn.addEventListener('mouseup',    function () { btn.style.transform  = 'scale(1)'; });
    }
}
</script>
@endpush