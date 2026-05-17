{{-- NAVBAR --}}
<nav
    id="main-navbar"
    style="position: fixed; top: 0; left: 0; right: 0; z-index: 50; background: #1C0F0A; box-shadow: 0 4px 24px rgba(0,0,0,0.45);"
    aria-label="Navigasi utama Pod's"
>
    <div style="width: 1280px; margin: 0 auto; height: 72px; display: flex; align-items: center; justify-content: space-between; padding: 0 2.5rem;">

        {{-- LOGO --}}
        <a
            href="{{ route('main') }}"
            class="font-serif"
            style="font-size: 1.5rem; font-weight: 900; letter-spacing: -0.02em; color: #F5E9D3; text-decoration: none; transition: color 0.2s; display: flex; align-items: center; gap: 0.5rem;"
            aria-label="Pod's — Kembali ke halaman utama"
            onmouseover="this.style.color='#C8813B'"
            onmouseout="this.style.color='#F5E9D3'"
        >
            Pod's
        </a>

        {{-- NAVIGASI TENGAH: link statis, selalu tampil di desktop --}}
        <ul style="display: flex; align-items: center; gap: 2.5rem; list-style: none; margin: 0; padding: 0;" role="list">

            @php
                /* definisi item nav: [route_name, label, route_pattern_untuk_active_check] */
                $navItems = [
                    ['main',          'Home',    'main'],
                    ['orders.branch', 'Order',   'orders*'],
                    ['history',       'History', 'history*'],
                    ['account',       'Account', 'account*'],
                ];
            @endphp

            @foreach($navItems as [$routeName, $label, $pattern])
            @php $isActive = request()->routeIs($pattern); @endphp
            <li>
                <a
                    href="{{ route($routeName) }}"
                    style="font-size: 0.875rem; font-weight: 500; letter-spacing: 0.02em; text-decoration: none; transition: color 0.2s;
                           color: {{ $isActive ? '#C8813B' : 'rgba(245,233,211,0.75)' }};
                           {{ $isActive ? 'border-bottom: 1.5px solid #C8813B; padding-bottom: 2px;' : '' }}"
                    {{ $isActive ? 'aria-current="page"' : '' }}
                    onmouseover="this.style.color='#C8813B'"
                    onmouseout="this.style.color='{{ $isActive ? '#C8813B' : 'rgba(245,233,211,0.75)' }}'"
                >
                    {{ $label }}
                </a>
            </li>
            @endforeach

        </ul>

        {{-- KANAN: sapaan + Call To Action + logout --}}
        <div style="display: flex; align-items: center; gap: 1rem;">

            @auth
                {{-- sapaan user: selalu tampil, tidak collapse --}}
                <span style="font-size: 0.8125rem; color: rgba(245,233,211,0.5); font-weight: 300; white-space: nowrap;">
                    Hello, <span style="font-weight: 500; color: #F5E9D3;">{{ Auth::user()->display_name }}</span>
                </span>

                {{-- CTA order: pill karamel --}}
                <a
                    href="{{ route('orders.branch') }}"
                    style="display: inline-flex; align-items: center; padding: 0.5rem 1.25rem; border-radius: 9999px; background: #C8813B; color: #1C0F0A; font-size: 0.875rem; font-weight: 600; letter-spacing: 0.04em; text-decoration: none; transition: background 0.2s, transform 0.1s; white-space: nowrap;"
                    onmouseover="this.style.background='#D99045'"
                    onmouseout="this.style.background='#C8813B'"
                    onmousedown="this.style.transform='scale(0.96)'"
                    onmouseup="this.style.transform='scale(1)'"
                >
                    Order Now
                </a>

                {{-- form logout  --}}
                <form id="navbar-logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>

                <button
                    type="button"
                    id="btn-navbar-logout"
                    style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.8125rem; font-weight: 300; color: rgba(245,233,211,0.35); background: none; border: none; cursor: pointer; transition: color 0.2s; padding: 0.25rem;"
                    aria-label="Keluar dari akun"
                    onmouseover="this.style.color='#f87171'"
                    onmouseout="this.style.color='rgba(245,233,211,0.35)'"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/>
                    </svg>
                    Logout
                </button>

            @else
                <a
                    href="{{ route('login') }}"
                    style="font-size: 0.875rem; font-weight: 500; color: rgba(245,233,211,0.8); text-decoration: none; transition: color 0.2s;"
                    onmouseover="this.style.color='#F5E9D3'"
                    onmouseout="this.style.color='rgba(245,233,211,0.8)'"
                >
                    Login
                </a>
                <a
                    href="{{ route('register') }}"
                    style="display: inline-flex; align-items: center; padding: 0.5rem 1.25rem; border-radius: 9999px; border: 1.5px solid #C8813B; color: #C8813B; font-size: 0.875rem; font-weight: 600; letter-spacing: 0.03em; text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.background='#C8813B'; this.style.color='#1C0F0A';"
                    onmouseout="this.style.background='transparent'; this.style.color='#C8813B';"
                >
                    Daftar
                </a>
            @endauth

        </div>
    </div>
</nav>

{{-- SCRIPT NAVBAR: konfirmasi logout via SwalModal --}}
<script>
(function () {
    const btn  = document.getElementById('btn-navbar-logout');
    const form = document.getElementById('navbar-logout-form');
    if (!btn || !form) return;

    btn.addEventListener('click', function () {
        /* swal modal: logout adalah aksi destruktif (sesi diakhiri) */
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