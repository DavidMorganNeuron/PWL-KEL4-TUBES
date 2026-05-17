{{-- SIDEBAR --}}
<nav
    style="
        width: 256px;
        height: 100vh;
        background: var(--pods-espresso);
        display: flex;
        flex-direction: column;
        border-right: 1px solid rgba(245,233,211,0.07);
        overflow-y: auto;
        overflow-x: hidden;
    "
    aria-label="Menu navigasi manager"
>

    {{-- BRAND: logo + identitas cabang --}}
    <div style="padding: 1.75rem 1.5rem 1.25rem; border-bottom: 1px solid rgba(245,233,211,0.07); flex-shrink: 0;">
        <a
            href="{{ route('manager.dashboard') }}"
            class="font-serif"
            style="font-size: 1.375rem; font-weight: 900; color: #F5E9D3; text-decoration: none; letter-spacing: -0.02em; display: block; margin-bottom: 0.875rem; transition: color 0.2s;"
            aria-label="Pod's — Kembali ke dashboard"
            onmouseover="this.style.color='#C8813B'"
            onmouseout="this.style.color='#F5E9D3'"
        >
            Pod's
        </a>

        {{-- identitas cabang & manager --}}
        <div style="background: rgba(200,129,59,0.12); border: 1px solid rgba(200,129,59,0.2); border-radius: 8px; padding: 0.75rem 0.875rem;">
            <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: #C8813B; margin-bottom: 0.25rem;">
                Cabang Aktif
            </p>
            <p style="font-size: 0.875rem; font-weight: 600; color: #F5E9D3; margin-bottom: 0.125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ Auth::user()->branch->name ?? 'Tidak Diketahui' }}
            </p>
            <p style="font-size: 0.75rem; font-weight: 300; color: rgba(245,233,211,0.5); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ Auth::user()->name }}
            </p>
        </div>
    </div>

    {{-- MENU UTAMA --}}
    <div style="padding: 1rem 0.75rem; flex: 1;">

        @php
            $sidebarItems = [
                [
                    'route'   => 'manager.dashboard',
                    'label'   => 'Dashboard',
                    'pattern' => 'manager.dashboard',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                ],
                [
                    'route'   => 'manager.kds',
                    'label'   => 'Kitchen Display',
                    'pattern' => 'manager.kds',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
                ],
                [
                    'route'   => 'manager.stock',
                    'label'   => 'Stok Lokal',
                    'pattern' => 'manager.stock',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                ],
                [
                    'route'   => 'manager.report',
                    'label'   => 'Laporan Penjualan',
                    'pattern' => 'manager.report',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                ],
                [
                    'route'   => 'manager.request_form',
                    'label'   => 'Ajukan Restock',
                    'pattern' => 'manager.request_form',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>',
                ],
            ];
        @endphp

        <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 2px;" role="list">
            @foreach($sidebarItems as $item)
            @php $isActive = request()->routeIs($item['pattern']); @endphp
            <li>
                <a
                    href="{{ route($item['route']) }}"
                    style="
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        padding: 0.625rem 0.875rem;
                        border-radius: 8px;
                        font-size: 0.875rem;
                        font-weight: {{ $isActive ? '600' : '400' }};
                        text-decoration: none;
                        transition: background 0.15s, color 0.15s;
                        background: {{ $isActive ? 'rgba(200,129,59,0.15)' : 'transparent' }};
                        color: {{ $isActive ? '#C8813B' : 'rgba(245,233,211,0.65)' }};
                        border-left: 2.5px solid {{ $isActive ? '#C8813B' : 'transparent' }};
                        margin-left: -0.75px;
                    "
                    {{ $isActive ? 'aria-current="page"' : '' }}
                    onmouseover="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.background='rgba(245,233,211,0.06)'; this.style.color='#F5E9D3'; }"
                    onmouseout="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.background='transparent'; this.style.color='rgba(245,233,211,0.65)'; }"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="flex-shrink: 0; opacity: {{ $isActive ? '1' : '0.7' }};">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['label'] }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    {{-- FOOTER SIDEBAR: tombol logout --}}
    <div style="padding: 1rem 0.75rem 1.25rem; border-top: 1px solid rgba(245,233,211,0.07); flex-shrink: 0;">

        <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
            @csrf
        </form>

        <button
            type="button"
            id="btn-sidebar-logout"
            style="
                display: flex;
                align-items: center;
                gap: 0.75rem;
                width: 100%;
                padding: 0.625rem 0.875rem;
                border-radius: 8px;
                font-size: 0.875rem;
                font-weight: 400;
                color: rgba(245,233,211,0.4);
                background: transparent;
                border: none;
                cursor: pointer;
                transition: background 0.15s, color 0.15s;
                text-align: left;
            "
            aria-label="Keluar dari akun manager"
            onmouseover="this.style.background='rgba(239,68,68,0.1)'; this.style.color='#f87171';"
            onmouseout="this.style.background='transparent'; this.style.color='rgba(245,233,211,0.4)';"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/>
            </svg>
            Logout
        </button>
    </div>

</nav>

<script>
(function () {
    const btn  = document.getElementById('btn-sidebar-logout');
    const form = document.getElementById('sidebar-logout-form');
    if (!btn || !form) return;

    btn.addEventListener('click', function () {
        window.SwalModal.fire({
            title:             'Yakin ingin keluar?',
            text:              'Sesi manager akan diakhiri.',
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