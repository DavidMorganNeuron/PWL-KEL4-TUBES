@extends('customer.layouts.app')

@section('title', "Branch — Pod's")

@section('content')

<div style="min-height: 100vh; background: #FBF6EE;">

    {{-- ================================================================
         PAGE HEADER: background espresso + judul
    ================================================================ --}}
    <div style="background: #3D1F0F; padding: 2.25rem 0 2rem; border-bottom: 1px solid rgba(245,233,211,0.07);">
        <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">
            <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: #C8813B; margin: 1.75rem 0 0.375rem;">
                Langkah 1 dari 4
            </p>
            <h1 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 900; color: #F5E9D3; margin-bottom: 0.375rem;">
                Pilih Cabang
            </h1>
            <p style="font-size: 0.875rem; color: rgba(245,233,211,0.5); font-weight: 300;">
                Pilih lokasi Pod's yang ingin kamu kunjungi hari ini.
            </p>
        </div>
    </div>

    {{-- ================================================================
         FORM PILIH CABANG
    ================================================================ --}}
    <div style="width: 1280px; margin: 0 auto; padding: 2.5rem 2.5rem 4rem;">

        <form method="POST" action="/order/branch" id="form-branch">
            @csrf

            <div
                style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 2.5rem;"
                role="radiogroup"
                aria-labelledby="branch-group-label"
            >
                <p id="branch-group-label" style="display: none;">Pilih cabang Pod's</p>

                @forelse($branches as $branch)
                <label
                    for="branch_{{ $branch->id_branches }}"
                    id="branch-card-{{ $branch->id_branches }}"
                    style="
                        display: block;
                        background: #FFFFFF;
                        border-radius: 1.125rem;
                        border: 2px solid #EDE0CC;
                        padding: 1.625rem;
                        cursor: pointer;
                        position: relative;
                        overflow: hidden;
                        transition: border-color 0.2s, box-shadow 0.2s;
                    "
                    onmouseover="if (!document.getElementById('branch_{{ $branch->id_branches }}').checked) { this.style.borderColor='rgba(200,129,59,0.4)'; this.style.boxShadow='0 4px 18px rgba(200,129,59,0.08)'; }"
                    onmouseout="if (!document.getElementById('branch_{{ $branch->id_branches }}').checked) { this.style.borderColor='#EDE0CC'; this.style.boxShadow='none'; }"
                >

                    <input
                        type="radio"
                        name="branch_id"
                        id="branch_{{ $branch->id_branches }}"
                        value="{{ $branch->id_branches }}"
                        style="position: absolute; opacity: 0; width: 0; height: 0;"
                        required
                        onchange="updateBranchCard(this)"
                    >

                    {{-- checkmark pojok kanan atas: muncul saat terpilih --}}
                    <div
                        id="check-{{ $branch->id_branches }}"
                        style="display: none; position: absolute; top: 1rem; right: 1rem; width: 22px; height: 22px; border-radius: 9999px; background: #C8813B; align-items: center; justify-content: center;"
                        aria-hidden="true"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="#1C0F0A" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    {{-- ikon pin lokasi --}}
                    <div style="width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(200,129,59,0.11); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>

                    <h2 style="font-family: var(--font-serif); font-size: 1.125rem; font-weight: 700; color: #1C0F0A; margin-bottom: 0.375rem;">
                        {{ $branch->name }}
                    </h2>

                    @if($branch->address)
                    <p style="font-size: 0.8125rem; color: #A08060; font-weight: 300; line-height: 1.55; margin-bottom: 0.875rem;">
                        {{ $branch->address }}
                    </p>
                    @endif

                    {{-- jam operasional --}}
                    <div style="display: flex; align-items: center; gap: 0.375rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#A08060" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @if($branch->is_always_open)
                        <span style="font-size: 0.75rem; color: #059669; font-weight: 600;">Buka 24 Jam</span>
                        @else
                        <span style="font-size: 0.75rem; color: #A08060;">
                            {{ \Carbon\Carbon::parse($branch->open_time)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($branch->close_time)->format('H:i') }} WIB
                        </span>
                        @endif
                    </div>

                </label>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;" aria-hidden="true">🏪</div>
                    <p style="font-size: 1rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.5rem;">Belum Ada Cabang Aktif</p>
                    <p style="font-size: 0.875rem; color: #A08060; font-weight: 300;">Silakan hubungi admin untuk informasi lebih lanjut.</p>
                </div>
                @endforelse
            </div>

            {{-- tombol lanjut: disabled sampai cabang dipilih --}}
            @if($branches->isNotEmpty())
            <div style="display: flex; justify-content: flex-end;">
                <button
                    type="submit"
                    id="btn-branch-submit"
                    disabled
                    style="
                        display: inline-flex; align-items: center; gap: 0.5rem;
                        padding: 0.875rem 2.25rem;
                        border-radius: 9999px;
                        border: none;
                        background: #C2B09A;
                        color: rgba(255,255,255,0.55);
                        font-size: 0.9375rem; font-weight: 600; letter-spacing: 0.04em;
                        cursor: not-allowed;
                        transition: background 0.2s, color 0.2s, transform 0.1s;
                    "
                >
                    Lanjut Pilih Menu
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
            @endif

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* updateBranchCard: sinkronisasi visual card dengan state radio yang terpilih */
function updateBranchCard(radio) {
    /* reset semua card ke state default */
    document.querySelectorAll('[id^="branch-card-"]').forEach(function (card) {
        card.style.borderColor = '#EDE0CC';
        card.style.boxShadow   = 'none';
        card.style.background  = '#FFFFFF';
    });
    document.querySelectorAll('[id^="check-"]').forEach(function (el) {
        el.style.display = 'none';
    });

    /* terapkan state aktif pada card yang dipilih */
    var card  = document.getElementById('branch-card-' + radio.value);
    var check = document.getElementById('check-' + radio.value);
    if (card) {
        card.style.borderColor = '#C8813B';
        card.style.boxShadow   = '0 0 0 4px rgba(200,129,59,0.14)';
        card.style.background  = '#FFFCF8';
    }
    if (check) { check.style.display = 'flex'; }

    /* aktifkan tombol submit */
    var btn = document.getElementById('btn-branch-submit');
    if (btn && btn.disabled) {
        btn.disabled         = false;
        btn.style.background = '#C8813B';
        btn.style.color      = '#1C0F0A';
        btn.style.cursor     = 'pointer';
        btn.addEventListener('mouseover',  function () { btn.style.background = '#D99045'; });
        btn.addEventListener('mouseout',   function () { btn.style.background = '#C8813B'; });
        btn.addEventListener('mousedown',  function () { btn.style.transform  = 'scale(0.97)'; });
        btn.addEventListener('mouseup',    function () { btn.style.transform  = 'scale(1)'; });
    }
}
</script>
@endpush