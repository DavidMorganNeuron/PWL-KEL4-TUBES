@extends('layout.app')

@section('title', "Pembayaran — Pod's")

@section('content')

<div style="min-height: 100vh; background: #FBF6EE;">

    {{-- ================================================================
         PAGE HEADER
    ================================================================ --}}
    <div style="background: #3D1F0F; padding: 2.25rem 0 2rem; border-bottom: 1px solid rgba(245,233,211,0.07);">
        <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">
            @include('customer._partials.order_stepper', ['currentStep' => 4])
            <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: #C8813B; margin: 1.75rem 0 0.375rem;">
                Langkah 4 dari 4
            </p>
            <h1 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 900; color: #F5E9D3; margin-bottom: 0.375rem;">
                Konfirmasi Pembayaran
            </h1>
            <p style="font-size: 0.875rem; color: rgba(245,233,211,0.5); font-weight: 300;">
                Selesaikan pembayaran lalu klik tombol konfirmasi di bawah.
            </p>
        </div>
    </div>

    {{-- ================================================================
         MAIN LAYOUT: 2 kolom (instruksi kiri + ringkasan order kanan)
    ================================================================ --}}
    <div style="width: 1280px; margin: 0 auto; padding: 2.5rem 2.5rem 4rem; display: grid; grid-template-columns: 1fr 360px; gap: 1.75rem; align-items: start;">

        {{-- ==========================================
             KOLOM KIRI: Instruksi + Aksi Konfirmasi
        ========================================== --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- KARTU: Instruksi pembayaran berdasarkan metode --}}
            <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.05); overflow: hidden;">
                <div style="background: linear-gradient(135deg, #1C0F0A 0%, #3D1F0F 100%); padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 42px; height: 42px; border-radius: 0.75rem; background: rgba(200,129,59,0.2); display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size: 0.75rem; color: rgba(245,233,211,0.5); font-weight: 400; margin-bottom: 0.1rem;">Metode Pembayaran</p>
                            <p style="font-size: 1rem; font-weight: 700; color: #F5E9D3; text-transform: capitalize;">
                                {{ $payment->method === 'qris' ? 'QRIS' : ucfirst($payment->method) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div style="padding: 1.625rem 1.75rem;">
                    @switch($payment->method)
                    @case('cash')
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; gap: 0.875rem; padding: 1rem; background: rgba(200,129,59,0.08); border-radius: 0.875rem; border: 1px solid rgba(200,129,59,0.2);">
                            <span style="font-size: 1.375rem; flex-shrink: 0; margin-top: 0.1rem;" aria-hidden="true">💵</span>
                            <div>
                                <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.25rem;">Bayar di Kasir</p>
                                <p style="font-size: 0.875rem; color: #A08060; font-weight: 300; line-height: 1.6;">
                                    Tunjukkan nomor pesanan <strong style="font-weight: 600; color: #3D1F0F; font-family: 'Courier New', monospace;">#{{ $order->order_number }}</strong> kepada kasir di cabang <strong style="font-weight: 600; color: #1C0F0A;">{{ $order->branch->name }}</strong>, kemudian selesaikan pembayaran tunai.
                                </p>
                            </div>
                        </div>
                        <p style="font-size: 0.8125rem; color: #A08060; font-weight: 300; line-height: 1.65;">
                            Setelah kasir mengkonfirmasi penerimaan, klik tombol <strong style="font-weight: 600;">"Konfirmasi Sudah Bayar"</strong> di bawah untuk melanjutkan.
                        </p>
                    </div>
                    @break
                    @case('qris')
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; gap: 0.875rem; padding: 1rem; background: rgba(200,129,59,0.08); border-radius: 0.875rem; border: 1px solid rgba(200,129,59,0.2);">
                            <span style="font-size: 1.375rem; flex-shrink: 0; margin-top: 0.1rem;" aria-hidden="true">📱</span>
                            <div>
                                <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.25rem;">Scan QR Code</p>
                                <p style="font-size: 0.875rem; color: #A08060; font-weight: 300; line-height: 1.6;">
                                    Buka aplikasi dompet digital (GoPay, OVO, Dana, dll.) dan scan QR Code yang tersedia di meja kasir cabang <strong style="font-weight: 600; color: #1C0F0A;">{{ $order->branch->name }}</strong>.
                                </p>
                            </div>
                        </div>
                        <div style="background: #FBF6EE; border-radius: 0.875rem; padding: 1rem; border: 1px solid #EDE0CC;">
                            <p style="font-size: 0.8125rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.25rem;">Jumlah yang harus dibayar:</p>
                            <p style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 900; color: #C8813B;">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @break
                    @case('transfer')
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; gap: 0.875rem; padding: 1rem; background: rgba(200,129,59,0.08); border-radius: 0.875rem; border: 1px solid rgba(200,129,59,0.2);">
                            <span style="font-size: 1.375rem; flex-shrink: 0; margin-top: 0.1rem;" aria-hidden="true">🏦</span>
                            <div>
                                <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.25rem;">Transfer Bank</p>
                                <p style="font-size: 0.875rem; color: #A08060; font-weight: 300; line-height: 1.6;">
                                    Lakukan transfer ke rekening Pod's yang tertera di kasir cabang <strong style="font-weight: 600; color: #1C0F0A;">{{ $order->branch->name }}</strong> dengan nominal tepat.
                                </p>
                            </div>
                        </div>
                        <div style="background: #FBF6EE; border-radius: 0.875rem; padding: 1rem; border: 1px solid #EDE0CC;">
                            <p style="font-size: 0.8125rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.25rem;">Nominal transfer:</p>
                            <p style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 900; color: #C8813B;">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </p>
                            <p style="font-size: 0.75rem; color: #A08060; margin-top: 0.375rem; font-weight: 300;">
                                Berita transfer: <span style="font-family: 'Courier New', monospace; font-weight: 600; color: #3D1F0F;">{{ $order->order_number }}</span>
                            </p>
                        </div>
                    </div>
                    @break
                    @endswitch
                </div>
            </div>

            {{-- KARTU: Tombol Konfirmasi —  aksi finansial destruktif, wajib SwalModal --}}
            <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.05); padding: 1.5rem;">
                <h2 style="font-size: 0.9375rem; font-weight: 700; color: #1C0F0A; margin-bottom: 0.375rem;">
                    Sudah Selesai Membayar?
                </h2>
                <p style="font-size: 0.8125rem; color: #A08060; font-weight: 300; margin-bottom: 1.375rem; line-height: 1.65;">
                    Klik tombol di bawah setelah pembayaran berhasil. Stok akan dipotong dan pesanan masuk ke dapur.
                </p>

                {{-- form konfirmasi: disubmit via JS setelah SwalModal --}}
                <form id="form-payment-confirm" method="POST" action="/payment/{{ $order->id_orders }}" style="display: none;">
                    @csrf
                </form>

                <button
                    type="button"
                    id="btn-confirm-payment"
                    style="
                        width: 100%;
                        padding: 0.9375rem;
                        border-radius: 0.75rem;
                        border: none;
                        background: #C8813B;
                        color: #1C0F0A;
                        font-size: 1rem; font-weight: 700; letter-spacing: 0.04em;
                        cursor: pointer;
                        box-shadow: 0 4px 18px rgba(200,129,59,0.3);
                        transition: background 0.2s, transform 0.1s;
                    "
                    onmouseover="this.style.background='#D99045'"
                    onmouseout="this.style.background='#C8813B'"
                    onmousedown="this.style.transform='scale(0.98)'"
                    onmouseup="this.style.transform='scale(1)'"
                >
                    ✓ Konfirmasi Sudah Bayar
                </button>

                <p style="font-size: 0.75rem; color: #A08060; font-weight: 300; text-align: center; margin-top: 0.875rem;">
                    Aksi ini tidak dapat dibatalkan setelah dikonfirmasi.
                </p>
            </div>

        </div>

        {{-- ==========================================
             KOLOM KANAN: Ringkasan Order
        ========================================== --}}
        <aside style="position: sticky; top: calc(72px + 1.5rem);" aria-label="Ringkasan pesanan">
            <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 2px 12px rgba(28,15,10,0.07); overflow: hidden;">

                {{-- header --}}
                <div style="background: #1C0F0A; padding: 1rem 1.375rem;">
                    <h2 style="font-size: 0.9375rem; font-weight: 600; color: #F5E9D3;">Ringkasan Pesanan</h2>
                    <p style="font-size: 0.75rem; color: rgba(245,233,211,0.4); font-family: 'Courier New', monospace; margin-top: 0.2rem;">
                        #{{ $order->order_number }}
                    </p>
                </div>

                {{-- daftar item --}}
                <div style="padding: 1rem 1.375rem; border-bottom: 1px solid #EDE0CC;">
                    @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; padding: 0.625rem 0; border-bottom: 1px solid #F5ECE0;">
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 500; color: #1C0F0A; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $item->product->name ?? '—' }}
                            </p>
                            <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">
                                × {{ $item->qty }}
                            </p>
                        </div>
                        <span style="font-size: 0.875rem; font-weight: 600; color: #3D1F0F; white-space: nowrap; flex-shrink: 0;">
                            Rp {{ number_format($item->subtotal_price, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach
                </div>

                {{-- status badge order --}}
                <div style="padding: 0.875rem 1.375rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #EDE0CC;">
                    <span style="font-size: 0.8125rem; color: #A08060;">Status</span>
                    @include('customer._partials.status_badge', ['status' => $order->status])
                </div>

                {{-- total final --}}
                <div style="padding: 1.125rem 1.375rem; background: #FBF6EE;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.9375rem; color: #A08060; font-weight: 500;">Total</span>
                        <span style="font-family: var(--font-serif); font-size: 1.25rem; font-weight: 900; color: #1C0F0A;">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

            </div>
        </aside>

    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    var btn  = document.getElementById('btn-confirm-payment');
    var form = document.getElementById('form-payment-confirm');
    if (!btn || !form) return;

    btn.addEventListener('click', function () {
        /* swal modal — konfirmasi pembayaran adalah aksi finansial destruktif */
        window.SwalModal.fire({
            title:             'Konfirmasi Pembayaran',
            html:              'Pastikan pembayaran sebesar <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong> sudah berhasil.<br><br>Stok akan dipotong dan pesanan langsung masuk ke dapur.',
            icon:              'question',
            showCancelButton:  true,
            confirmButtonText: 'Ya, Sudah Bayar',
            cancelButtonText:  'Belum',
            reverseButtons:    true,
        }).then(function (result) {
            if (result.isConfirmed) {
                /* nonaktifkan tombol agar tidak double-submit */
                btn.disabled         = true;
                btn.textContent      = 'Memproses...';
                btn.style.background = '#A08060';
                form.submit();
            }
        });
    });
}());
</script>
@endpush