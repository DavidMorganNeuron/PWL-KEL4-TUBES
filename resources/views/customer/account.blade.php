{{-- CUSTOMER ACCOUNT: halaman profil pelanggan --}}
@extends('customer.layouts.app')

@section('title', "Account — Pod's")

@section('content')

<div style="padding-top: 72px; min-height: 100vh; background: #FBF6EE;">

    {{-- ================================================================
         PAGE HEADER
    ================================================================ --}}
    <div style="background: #FBF6EE; padding: 2.5rem 0 0;">
        <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">
            <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: #C8813B; margin-bottom: 0.25rem;">
                Customer
            </p>
            <h1 style="font-family: var(--font-serif); font-size: 1.875rem; font-weight: 900; color: #1C0F0A;">
                My Account
            </h1>
        </div>
    </div>

    {{-- ================================================================
         MAIN CONTENT: grid 3 kolom statis
    ================================================================ --}}
    <div style="width: 1280px; margin: 0 auto; padding: 2rem 2.5rem 4rem;">
        <div style="display: grid; grid-template-columns: 340px 1fr; gap: 1.5rem; align-items: start;">

            {{-- ==========================================
                 KOLOM KIRI — Kartu Identitas User
            ========================================== --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">

                {{-- KARTU: Avatar + Nama --}}
                <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.06); overflow: hidden;">
                    {{-- header dekoratif kartu --}}
                    <div style="height: 80px; background: linear-gradient(135deg, #1C0F0A 0%, #3D1F0F 100%);"></div>

                    <div style="padding: 0 1.5rem 1.5rem;">
                        {{-- avatar inisial: overlap ke banner --}}
                        <div
                            style="
                                margin-top: -2rem;
                                margin-bottom: 1rem;
                                width: 64px; height: 64px;
                                border-radius: 9999px;
                                background: #C8813B;
                                display: flex; align-items: center; justify-content: center;
                                box-shadow: 0 4px 14px rgba(200,129,59,0.35);
                                border: 4px solid #FFFFFF;
                            "
                            aria-hidden="true"
                        >
                            {{-- inisial dari setiap kata display_name --}}
                            <span style="font-family: var(--font-serif); font-size: 1.25rem; font-weight: 900; color: #1C0F0A;">
                                {{ implode('', array_map(fn($w) => strtoupper($w[0]), explode(' ', Auth::user()->display_name))) }}
                            </span>
                        </div>

                        <h2 style="font-family: var(--font-serif); font-size: 1.25rem; font-weight: 700; color: #1C0F0A; line-height: 1.25; margin-bottom: 0.2rem;">
                            {{ Auth::user()->display_name }}
                        </h2>
                        <p style="font-size: 0.875rem; color: #A08060; font-weight: 300;">Pelanggan Pod's</p>

                        {{-- badge member aktif --}}
                        {{-- <div style="
                            margin-top: 1rem;
                            display: inline-flex; align-items: center; gap: 0.375rem;
                            background: rgba(200,129,59,0.12); color: #C8813B;
                            font-size: 0.75rem; font-weight: 600;
                            padding: 0.3rem 0.75rem; border-radius: 9999px;
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            Member Aktif
                        </div> --}}
                    </div>
                </div>

                {{-- KARTU: Informasi Akun --}}
                <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.06); padding: 1.5rem;">
                    <h3 style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #A08060; margin-bottom: 1.125rem;">
                        Informasi Akun
                    </h3>
                    <dl style="display: flex; flex-direction: column; gap: 0;">

                        <div style="padding-bottom: 1rem; margin-bottom: 1rem; border-bottom: 1px solid #EDE0CC;">
                            <dt style="font-size: 0.75rem; color: #A08060; font-weight: 500; margin-bottom: 0.2rem;">Bergabung Sejak</dt>
                            <dd style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A;">
                                {{ Auth::user()->created_at->translatedFormat('d F Y') }}
                            </dd>
                        </div>

                        <div>
                            <dt style="font-size: 0.75rem; color: #A08060; font-weight: 500; margin-bottom: 0.2rem;">Role</dt>
                            <dd style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; text-transform: capitalize;">
                                {{ Auth::user()->role->name ?? 'Customer' }}
                            </dd>
                        </div>

                    </dl>
                </div>

            </div>

            {{-- ==========================================
                 KOLOM KANAN — Detail & Aksi
            ========================================== --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">

                {{-- KARTU: Informasi Pribadi — grid 2 kolom statis --}}
                <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.06); padding: 1.5rem;">
                    <h3 style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #A08060; margin-bottom: 1.375rem;">
                        Informasi Pribadi
                    </h3>

                    {{-- grid 2 kolom statis untuk field info --}}
                    <dl style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.125rem;">

                        {{-- field: nama lengkap --}}
                        <div style="background: #FBF6EE; border-radius: 0.875rem; padding: 1rem 1.125rem;">
                            <dt style="font-size: 0.75rem; color: #A08060; font-weight: 500; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.375rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Nama Lengkap
                            </dt>
                            <dd style="font-size: 1rem; font-weight: 600; color: #1C0F0A;">
                                {{ Auth::user()->name }}
                            </dd>
                        </div>

                        {{-- field: email --}}
                        <div style="background: #FBF6EE; border-radius: 0.875rem; padding: 1rem 1.125rem;">
                            <dt style="font-size: 0.75rem; color: #A08060; font-weight: 500; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.375rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Email
                            </dt>
                            <dd style="font-size: 1rem; font-weight: 600; color: #1C0F0A; word-break: break-all;">
                                {{ Auth::user()->email }}
                            </dd>
                        </div>

                    </dl>
                </div>

                {{-- KARTU: Aksi Cepat — grid 2 kolom statis --}}
                <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.06); padding: 1.5rem;">
                    <h3 style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #A08060; margin-bottom: 1.375rem;">
                        Aksi Cepat
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.875rem;">

                        {{-- aksi: pesan sekarang --}}
                        <a
                            href="{{ route('orders.branch') }}"
                            style="
                                display: flex; align-items: center; gap: 0.875rem;
                                padding: 1rem 1.125rem;
                                border-radius: 0.875rem;
                                border: 1px solid #EDE0CC;
                                text-decoration: none;
                                transition: border-color 0.2s, background 0.2s;
                            "
                            onmouseover="this.style.borderColor='#C8813B'; this.style.background='#FBF6EE';"
                            onmouseout="this.style.borderColor='#EDE0CC'; this.style.background='transparent';"
                        >
                            <div style="
                                width: 40px; height: 40px;
                                border-radius: 9999px;
                                background: rgba(200,129,59,0.14);
                                display: flex; align-items: center; justify-content: center;
                                flex-shrink: 0;
                            ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.125rem;">Pesan Sekarang</p>
                                <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">Mulai order baru</p>
                            </div>
                        </a>

                        {{-- aksi: riwayat pesanan --}}
                        <a
                            href="{{ route('history') }}"
                            style="
                                display: flex; align-items: center; gap: 0.875rem;
                                padding: 1rem 1.125rem;
                                border-radius: 0.875rem;
                                border: 1px solid #EDE0CC;
                                text-decoration: none;
                                transition: border-color 0.2s, background 0.2s;
                            "
                            onmouseover="this.style.borderColor='#C8813B'; this.style.background='#FBF6EE';"
                            onmouseout="this.style.borderColor='#EDE0CC'; this.style.background='transparent';"
                        >
                            <div style="
                                width: 40px; height: 40px;
                                border-radius: 9999px;
                                background: rgba(200,129,59,0.14);
                                display: flex; align-items: center; justify-content: center;
                                flex-shrink: 0;
                            ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.125rem;">Riwayat Pesanan</p>
                                <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">Lihat order sebelumnya</p>
                            </div>
                        </a>

                    </div>
                </div>

                {{-- KARTU: Danger Zone — Sesi Aktif & Logout --}}
                <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 1px 4px rgba(28,15,10,0.06); padding: 1.5rem;">
                    <h3 style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #A08060; margin-bottom: 1.125rem;">
                        Sesi Aktif
                    </h3>
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;">
                        <div>
                            <p style="font-size: 0.9375rem; font-weight: 500; color: #1C0F0A; margin-bottom: 0.2rem;">Keluar dari akun ini</p>
                            <p style="font-size: 0.8125rem; color: #A08060; font-weight: 300;">Semua data keranjang akan direset.</p>
                        </div>

                        {{-- form logout --}}
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                            @csrf
                        </form>

                        <button
                            type="button"
                            id="btn-logout"
                            style="
                                flex-shrink: 0;
                                padding: 0.625rem 1.375rem;
                                border-radius: 9999px;
                                border: 1.5px solid rgba(239,68,68,0.3);
                                background: transparent;
                                color: #DC2626;
                                font-size: 0.875rem;
                                font-weight: 600;
                                cursor: pointer;
                                transition: background 0.2s, border-color 0.2s, transform 0.1s;
                            "
                            onmouseover="this.style.background='rgba(239,68,68,0.06)'; this.style.borderColor='rgba(239,68,68,0.5)';"
                            onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(239,68,68,0.3)';"
                            onmousedown="this.style.transform='scale(0.96)'"
                            onmouseup="this.style.transform='scale(1)'"
                        >
                            Logout
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
{{-- SwalModal konfirmasi logout — aksi destruktif: sesi & cart hilang --}}
(function () {
    const btn  = document.getElementById('btn-logout');
    const form = document.getElementById('logout-form');
    if (!btn || !form) return;

    btn.addEventListener('click', function () {
        /* swal modal — logout adalah aksi destruktif */
        window.SwalModal.fire({
            title:             'Yakin ingin keluar?',
            text:              'Semua data keranjang akan hilang.',
            icon:              'warning',
            showCancelButton:  true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText:  'Batal',
            reverseButtons:    true,
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
}());
</script>
@endpush