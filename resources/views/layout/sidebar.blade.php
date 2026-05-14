{{-- SIDEBAR --}}
<aside
    id="pods-sidebar"
    style="
        width: 220px;
        flex-shrink: 0;
        background: #1C0F0A;
        min-height: calc(100vh - 72px); /* 72px = tinggi navbar */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 2rem 0;
        border-right: 1px solid rgba(245,233,211,0.07);
    "
    aria-label="Navigasi sidebar"
>
    {{-- BAGIAN ATAS: link navigasi --}}
    <nav aria-label="Menu utama sidebar">
        <ul style="list-style: none; margin: 0; padding: 0;" role="list">

            {{-- item nav: macro pengulangan struktur yang sama --}}
            @php
                /* definisi menu sidebar: [route_name, label, icon_path] */
                $sidebarMenu = [
                    ['main',           'Home',     'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['orders.branch',  'Order',    'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['history',        'History',  'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['account',        'Account',     'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ];
            @endphp

            @foreach($sidebarMenu as [$routeName, $label, $iconPath])
            @php
                /* cek apakah route ini sedang aktif */
                $isActive = request()->routeIs($routeName === 'main' ? 'main' : $routeName . '*');
            @endphp
            <li>
                <a
                    href="{{ route($routeName) }}"
                    style="
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        padding: 0.75rem 1.5rem;
                        font-size: 0.875rem;
                        font-weight: {{ $isActive ? '600' : '400' }};
                        text-decoration: none;
                        color: {{ $isActive ? '#C8813B' : 'rgba(245,233,211,0.65)' }};
                        background: {{ $isActive ? 'rgba(200,129,59,0.1)' : 'transparent' }};
                        border-left: 3px solid {{ $isActive ? '#C8813B' : 'transparent' }};
                        transition: all 0.15s;
                    "
                    {{ $isActive ? 'aria-current="page"' : '' }}
                    onmouseover="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.color='rgba(245,233,211,0.9)'; this.style.background='rgba(245,233,211,0.05)'; }"
                    onmouseout="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.color='rgba(245,233,211,0.65)'; this.style.background='transparent'; }"
                >
                    {{-- ikon svg inline: setiap menu punya ikon unik --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $isActive ? '2' : '1.5' }}" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                    </svg>
                    {{ $label }}
                </a>
            </li>
            @endforeach

        </ul>
    </nav>

    {{-- BAGIAN BAWAH: info user + tombol logout --}}
    <div style="padding: 0 1rem;">
        {{-- divider --}}
        <div style="height: 1px; background: rgba(245,233,211,0.08); margin-bottom: 1rem;"></div>

        @auth
        {{-- chip info user --}}
        <div style="display: flex; align-items: center; gap: 0.625rem; padding: 0.75rem; border-radius: 10px; background: rgba(245,233,211,0.05); margin-bottom: 0.75rem;">
            {{-- avatar inisial --}}
            <div style="width: 32px; height: 32px; border-radius: 9999px; background: #C8813B; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" aria-hidden="true">
                <span class="font-serif" style="font-size: 0.75rem; font-weight: 900; color: #1C0F0A;">
                    {{ strtoupper(substr(Auth::user()->display_name, 0, 1)) }}
                </span>
            </div>
            <div style="overflow: hidden;">
                <p style="font-size: 0.8125rem; font-weight: 500; color: #F5E9D3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ Auth::user()->display_name }}
                </p>
                <p style="font-size: 0.7rem; color: rgba(245,233,211,0.4); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ Auth::user()->email }}
                </p>
            </div>
        </div>

        {{-- form logout --}}
        <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
            @csrf
        </form>

        {{-- tombol logout: full-width, merah subtle --}}
        <button
            type="button"
            id="btn-sidebar-logout"
            style="
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                padding: 0.625rem;
                border-radius: 8px;
                border: 1px solid rgba(248,113,113,0.2);
                background: transparent;
                color: rgba(248,113,113,0.7);
                font-size: 0.8125rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.15s;
            "
            aria-label="Keluar dari akun Pod's"
            onmouseover="this.style.background='rgba(248,113,113,0.08)'; this.style.color='#f87171'; this.style.borderColor='rgba(248,113,113,0.4)';"
            onmouseout="this.style.background='transparent'; this.style.color='rgba(248,113,113,0.7)'; this.style.borderColor='rgba(248,113,113,0.2)';"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/>
            </svg>
            Logout
        </button>
        @endauth

    </div>
</aside>

{{-- SCRIPT SIDEBAR: SwalModal konfirmasi logout --}}
@once
<script>
(function () {
    const btnSidebar = document.getElementById('btn-sidebar-logout');
    const formSidebar = document.getElementById('sidebar-logout-form');
    if (!btnSidebar || !formSidebar) return;

    btnSidebar.addEventListener('click', function () {
        /* level a: swal modal — logout adalah aksi destruktif */
        window.SwalModal.fire({
            title:             'Yakin ingin keluar?',
            text:              'Sesi aktif akan diakhiri.',
            icon:              'warning',
            showCancelButton:  true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText:  'Batal',
            reverseButtons:    true,
        }).then(function (result) {
            if (result.isConfirmed) {
                formSidebar.submit();
            }
        });
    });
}());
</script>
@endonce