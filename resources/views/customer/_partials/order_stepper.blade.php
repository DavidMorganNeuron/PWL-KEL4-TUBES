{{-- PARTIAL: ORDER STEPPER —  navigasi progress langkah pemesanan --}}
{{-- variabel wajib: $currentStep (int 1–4) --}}
@php
    $steps = [
        1 => 'Pilih Cabang',
        2 => 'Pilih Menu',
        3 => 'Checkout',
        4 => 'Pembayaran',
    ];
@endphp

<nav aria-label="Langkah pemesanan" style="display: flex; align-items: center; gap: 0;">
    @foreach($steps as $step => $label)

    {{-- item langkah --}}
    <div style="display: flex; align-items: center; gap: 0;">
        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.25rem;">

            {{-- lingkaran nomor --}}
            <div style="
                width: 30px; height: 30px;
                border-radius: 9999px;
                display: flex; align-items: center; justify-content: center;
                font-size: 0.75rem; font-weight: 700;
                {{ $step < $currentStep  ? 'background: #C8813B; color: #1C0F0A;' : '' }}
                {{ $step === $currentStep ? 'background: #F5E9D3; color: #1C0F0A;' : '' }}
                {{ $step > $currentStep  ? 'background: rgba(245,233,211,0.12); color: rgba(245,233,211,0.35);' : '' }}
                border: 2px solid {{ $step <= $currentStep ? '#C8813B' : 'rgba(245,233,211,0.15)' }};
                transition: all 0.2s;
            "
            aria-current="{{ $step === $currentStep ? 'step' : 'false' }}"
            >
                @if($step < $currentStep)
                {{-- ikon centang untuk langkah yang sudah selesai --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                @else
                {{ $step }}
                @endif
            </div>

            {{-- label langkah --}}
            <span style="
                font-size: 0.7rem; font-weight: {{ $step === $currentStep ? '600' : '400' }};
                color: {{ $step === $currentStep ? '#F5E9D3' : ($step < $currentStep ? '#C8813B' : 'rgba(245,233,211,0.3)') }};
                white-space: nowrap; letter-spacing: 0.02em;
            ">
                {{ $label }}
            </span>
        </div>

        {{-- garis penghubung antar langkah (tidak muncul setelah langkah terakhir) --}}
        @if($step < count($steps))
        <div style="
            width: 80px; height: 1.5px;
            margin: 0 0.5rem; margin-bottom: 1.25rem;
            background: {{ $step < $currentStep ? '#C8813B' : 'rgba(245,233,211,0.12)' }};
            transition: background 0.3s;
        " aria-hidden="true"></div>
        @endif
    </div>

    @endforeach
</nav>