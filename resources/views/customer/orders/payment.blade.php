@extends('customer.layouts.app')

@section('title', "Payment — Pod's")

@section('content')

<div style="min-height: 100vh; background: #FBF6EE;">

    {{-- ================================================================
         PAGE HEADER
    ================================================================ --}}
    <div style="background: #3D1F0F; padding: 2.25rem 0 2rem; border-bottom: 1px solid rgba(245,233,211,0.07);">
        <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">
            <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: #C8813B; margin: 1.75rem 0 0.375rem;">
                Langkah 4 dari 4
            </p>
            <h1 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 900; color: #F5E9D3; margin-bottom: 0.375rem;">
                Konfirmasi Pembayaran
            </h1>
            <p style="font-size: 0.875rem; color: rgba(245,233,211,0.5); font-weight: 300;">
                Selesaikan pembayaran dalam waktu yang tersedia.
            </p>
        </div>
    </div>

    {{-- ================================================================
         MAIN LAYOUT
    ================================================================ --}}
    <div style="width: 1280px; margin: 0 auto; padding: 2.5rem 2.5rem 4rem; display: grid; grid-template-columns: 1fr 360px; gap: 1.75rem; align-items: start;">

        {{-- ==============================================
             KOLOM KIRI: Instruksi + Countdown + Konfirmasi
        ============================================== --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- COUNTDOWN TIMER: 2 menit --}}
            <div id="payment-timer-card" style="background: #FFFFFF; border-radius: 1.125rem; border: 1.5px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.05); padding: 1.125rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span style="font-size: 0.875rem; font-weight: 500; color: #1C0F0A;">Batas waktu pembayaran:</span>
                </div>
                <span id="countdown-display" style="font-family: 'Courier New', Courier, monospace; font-size: 1.375rem; font-weight: 700; color: #C8813B; letter-spacing: 0.05em;" aria-live="polite" aria-label="Sisa waktu pembayaran">02:00</span>
            </div>

            {{-- Instruksi pembayaran --}}
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
                                {{ $payment->method === 'QRIS' ? 'QRIS' : 'E-Wallet' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div style="padding: 1.625rem 1.75rem;">
                    @if($payment->method === 'QRIS')
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; gap: 0.875rem; padding: 1rem; background: rgba(200,129,59,0.08); border-radius: 0.875rem; border: 1px solid rgba(200,129,59,0.2);">
                            <span style="font-size: 1.375rem; flex-shrink: 0; margin-top: 0.1rem;" aria-hidden="true">📱</span>
                            <div>
                                <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.25rem;">Scan QR Code</p>
                                <p style="font-size: 0.875rem; color: #A08060; font-weight: 300; line-height: 1.6;">
                                    Buka aplikasi dompet digital dan scan QR Code di meja kasir cabang <strong style="font-weight: 600; color: #1C0F0A;">{{ $order->branch->name }}</strong>.
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
                    @else
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; gap: 0.875rem; padding: 1rem; background: rgba(200,129,59,0.08); border-radius: 0.875rem; border: 1px solid rgba(200,129,59,0.2);">
                            <span style="font-size: 1.375rem; flex-shrink: 0; margin-top: 0.1rem;" aria-hidden="true">💳</span>
                            <div>
                                <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.25rem;">E-Wallet</p>
                                <p style="font-size: 0.875rem; color: #A08060; font-weight: 300; line-height: 1.6;">
                                    Lakukan pembayaran via GoPay, OVO, atau Dana di kasir cabang <strong style="font-weight: 600; color: #1C0F0A;">{{ $order->branch->name }}</strong>.
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
                    @endif
                </div>
            </div>

            {{-- Tombol Konfirmasi --}}
            <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.05); padding: 1.5rem;">
                <h2 style="font-size: 0.9375rem; font-weight: 700; color: #1C0F0A; margin-bottom: 0.375rem;">
                    Sudah Selesai Membayar?
                </h2>
                <p style="font-size: 0.8125rem; color: #A08060; font-weight: 300; margin-bottom: 1.375rem; line-height: 1.65;">
                    Klik tombol di bawah setelah pembayaran berhasil. Pesanan akan langsung masuk ke dapur.
                </p>

                <form id="form-payment-confirm" method="POST" action="/payment/{{ $order->id_orders }}" style="display: none;">
                    @csrf
                </form>

                {{-- form abandon --}}
                <form id="form-abandon" method="POST" action="{{ route('payment.abandon', $order->id_orders) }}" style="display: none;">
                    @csrf
                </form>

                <button
                    type="button"
                    id="btn-confirm-payment"
                    style="width: 100%; padding: 0.9375rem; border-radius: 0.75rem; border: none; background: #C8813B; color: #1C0F0A; font-size: 1rem; font-weight: 700; letter-spacing: 0.04em; cursor: pointer; box-shadow: 0 4px 18px rgba(200,129,59,0.3); transition: background 0.2s, transform 0.1s;"
                    onmouseover="this.style.background='#D99045'"
                    onmouseout="this.style.background='#C8813B'"
                    onmousedown="this.style.transform='scale(0.98)'"
                    onmouseup="this.style.transform='scale(1)'"
                >
                    ✓ Konfirmasi Sudah Bayar
                </button>

                {{-- tombol kembali --}}
                <button
                    type="button"
                    id="btn-go-back"
                    style="width: 100%; margin-top: 0.75rem; padding: 0.75rem; border-radius: 0.75rem; border: 1.5px solid #EDE0CC; background: transparent; color: #A08060; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.15s;"
                    onmouseover="this.style.borderColor='#DC2626'; this.style.color='#DC2626';"
                    onmouseout="this.style.borderColor='#EDE0CC'; this.style.color='#A08060';"
                >
                    Batalkan &amp; Kembali
                </button>

                <p style="font-size: 0.75rem; color: #A08060; font-weight: 300; text-align: center; margin-top: 0.875rem;">
                    Pesanan akan otomatis dibatalkan jika waktu habis.
                </p>
            </div>

        </div>

        {{-- ==========================================
             KOLOM KANAN: Ringkasan Order
        ========================================== --}}
        <aside style="position: sticky; top: calc(72px + 1.5rem);" aria-label="Ringkasan pesanan">
            <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 2px 12px rgba(28,15,10,0.07); overflow: hidden;">
                <div style="background: #1C0F0A; padding: 1rem 1.375rem;">
                    <h2 style="font-size: 0.9375rem; font-weight: 600; color: #F5E9D3;">Ringkasan Pesanan</h2>
                    <p style="font-size: 0.75rem; color: rgba(245,233,211,0.4); font-family: 'Courier New', monospace; margin-top: 0.2rem;">
                        #{{ $order->order_number }}
                    </p>
                </div>

                <div style="padding: 1rem 1.375rem; border-bottom: 1px solid #EDE0CC;">
                    @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; padding: 0.625rem 0; border-bottom: 1px solid #F5ECE0;">
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 500; color: #1C0F0A; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $item->product->name ?? '—' }}
                            </p>
                            <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">× {{ $item->qty }}</p>
                        </div>
                        <span style="font-size: 0.875rem; font-weight: 600; color: #3D1F0F; white-space: nowrap; flex-shrink: 0;">
                            Rp {{ number_format($item->subtotal_price, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach
                </div>

                <div style="padding: 0.875rem 1.375rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #EDE0CC;">
                    <span style="font-size: 0.8125rem; color: #A08060;">Status</span>
                    @include('customer.layouts.status_badge', ['status' => $order->status])
                </div>

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
    var orderId       = {{ $order->id_orders }};
    var abandonUrl    = '{{ route("payment.abandon", $order->id_orders) }}';
    var csrfToken     = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var TIMEOUT_MS    = 120000; /* 2 menit */
    var expired       = false;
    var confirmed     = false;

    /* COUNTDOWN TIMER */
    var display  = document.getElementById('countdown-display');
    var timerCard = document.getElementById('payment-timer-card');
    var remaining = TIMEOUT_MS / 1000;

    function updateDisplay() {
        var m = Math.floor(remaining / 60);
        var s = remaining % 60;
        display.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;

        /* warna merah saat < 30 detik */
        if (remaining <= 30) {
            display.style.color = '#DC2626';
            timerCard.style.borderColor = '#FECACA';
        }
    }

    var countdownInterval = setInterval(function () {
        remaining--;
        updateDisplay();

        if (remaining <= 0) {
            clearInterval(countdownInterval);
            triggerAbandon('timeout');
        }
    }, 1000);

    /* FUNGSI ABANDON: kirim POST ke backend */
    function triggerAbandon(reason) {
        if (expired || confirmed) return;
        expired = true;

        fetch(abandonUrl, {
            method:  'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept':       'application/json',
            },
            /* keepalive: pastikan request tetap terkirim meski tab ditutup */
            keepalive: true,
        }).finally(function () {
            if (reason === 'timeout') {
                /* modal SweetAlert2 saat timeout */
                window.SwalModal.fire({
                    icon:              'warning',
                    title:             'Waktu Habis',
                    text:              'Batas waktu pembayaran telah habis. Pesanan otomatis dibatalkan.',
                    confirmButtonText: 'Lihat Riwayat',
                    allowOutsideClick: false,
                    allowEscapeKey:    false,
                }).then(function () {
                    window.location.href = '{{ route("history") }}';
                });
            } else {
                window.location.href = '{{ route("history") }}';
            }
        });
    }

    /* BLOK TOMBOL BROWSER KEMBALI via History API */
    history.pushState(null, '', window.location.href);
    window.addEventListener('popstate', function () {
        if (confirmed) return;
        /* dorong kembali state agar tombol back tidak berfungsi */
        history.pushState(null, '', window.location.href);
        /* tampilkan konfirmasi pembatalan */
        window.SwalModal.fire({
            title:             'Batalkan Pesanan?',
            text:              'Jika kamu keluar, pesanan ini akan otomatis dibatalkan.',
            icon:              'warning',
            showCancelButton:  true,
            confirmButtonText: 'Ya, Batalkan',
            confirmButtonColor:'#DC2626',
            cancelButtonText:  'Tetap di Sini',
            reverseButtons:    true,
        }).then(function (result) {
            if (result.isConfirmed) {
                clearInterval(countdownInterval);
                triggerAbandon('back');
            }
        });
    });

    /* TOMBOL "BATALKAN & KEMBALI" */
    var btnBack = document.getElementById('btn-go-back');
    if (btnBack) {
        btnBack.addEventListener('click', function () {
            window.SwalModal.fire({
                title:             'Batalkan Pesanan?',
                text:              'Pesanan ini akan dibatalkan dan kamu akan diarahkan ke riwayat.',
                icon:              'warning',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Batalkan',
                confirmButtonColor:'#DC2626',
                cancelButtonText:  'Tetap Bayar',
                reverseButtons:    true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    clearInterval(countdownInterval);
                    triggerAbandon('back');
                }
            });
        });
    }

    /* KONFIRMASI PEMBAYARAN */
    var btn  = document.getElementById('btn-confirm-payment');
    var form = document.getElementById('form-payment-confirm');
    if (btn && form) {
        btn.addEventListener('click', function () {
            window.SwalModal.fire({
                title:             'Konfirmasi Pembayaran',
                html:              'Pastikan pembayaran sebesar <strong>Rp {{ number_format($order->grand_total, 0, ",", ".") }}</strong> sudah berhasil.',
                icon:              'question',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Sudah Bayar',
                cancelButtonText:  'Belum',
                reverseButtons:    true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    confirmed = true;
                    clearInterval(countdownInterval);
                    btn.disabled         = true;
                    btn.textContent      = 'Memproses...';
                    btn.style.background = '#A08060';
                    form.submit();
                }
            });
        });
    }

    /* BEFOREUNLOAD: kirim abandon jika tab ditutup/refresh */
    window.addEventListener('beforeunload', function () {
        if (confirmed || expired) return;
        /* sendBeacon lebih andal dari fetch saat tab ditutup */
        var data = new FormData();
        data.append('_token', csrfToken);
        navigator.sendBeacon(abandonUrl, data);
    });
}());
</script>
@endpush