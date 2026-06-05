<nav
    style="width:260px; height:100vh; background:var(--pods-espresso); display:flex; flex-direction:column; border-right:1px solid rgba(245,233,211,0.07); overflow-y:auto; overflow-x:hidden;"
    aria-label="Menu navigasi admin pusat"
>

    <div style="padding:1.75rem 1.5rem 1.25rem; border-bottom:1px solid rgba(245,233,211,0.07); flex-shrink:0;">
        <a
            href="{{ route('admin.dashboard') }}"
            class="font-serif"
            style="font-size:1.375rem; font-weight:900; color:#F5E9D3; text-decoration:none; letter-spacing:-0.02em; display:block; margin-bottom:0.875rem; transition:color 0.2s;"
            onmouseover="this.style.color='#C8813B'" onmouseout="this.style.color='#F5E9D3'"
        >
            Pod's
        </a>

        {{-- identitas admin pusat --}}
        <div style="background:rgba(200,129,59,0.12); border:1px solid rgba(200,129,59,0.2); border-radius:8px; padding:0.75rem 0.875rem;">
            <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:#C8813B; margin-bottom:0.25rem;">
                Admin Pusat
            </p>
            <p style="font-size:0.875rem; font-weight:600; color:#F5E9D3; margin-bottom:0.125rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ Auth::user()->name }}
            </p>
            <p style="font-size:0.75rem; font-weight:300; color:rgba(245,233,211,0.4);">
                Akses Global - Semua Cabang
            </p>
        </div>
    </div>

    {{-- menu utama --}}
    <div style="padding:1rem 0.75rem; flex:1;">

        @php
            /* definisi item navigasi admin dengan grup */
            $sidebarGroups = [
                [
                    'group' => 'Operasional',
                    'items' => [
                        [
                            'route'   => 'admin.dashboard',
                            'label'   => 'Dashboard',
                            'pattern' => 'admin.dashboard',
                            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                        ],
                        [
                            'route'   => 'admin.requests.index',
                            'label'   => 'Validasi Request',
                            'pattern' => 'admin.requests.*',
                            'badge'   => \App\Models\RequestLog::where('status', 'pending')->count(),
                            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                    ],
                ],
                [
                    'group' => 'Katalog',
                    'items' => [
                        [
                            'route'   => 'admin.catalogs.index',
                            'label'   => 'Manajemen Produk',
                            'pattern' => 'admin.catalogs.*',
                            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>',
                        ],
                        [
                            'route'   => 'admin.promos.index',
                            'label'   => 'Manajemen Promo',
                            'pattern' => 'admin.promos.*',
                            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',
                        ],
                    ],
                ],
                [
                    'group' => 'Laporan',
                    'items' => [
                        [
                            'route'   => 'admin.reports.sales',
                            'label'   => 'Laporan Penjualan',
                            'pattern' => 'admin.reports.sales',
                            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                        ],
                        [
                            'route'   => 'admin.reports.assets',
                            'label'   => 'Laporan Stok Produk',
                            'pattern' => 'admin.reports.assets',
                            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                        ],
                    ],
                ],
                [
                    'group' => 'SDM',
                    'items' => [
                        [
                            'route'   => 'admin.branches.index',
                            'label'   => 'Manajemen Cabang',
                            'pattern' => 'admin.branches.*',
                            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                        ],
                    ],
                ],
            ];
        @endphp

        @foreach($sidebarGroups as $group)
        <div style="margin-bottom:0.25rem;">
            <p style="font-size:0.625rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:rgba(245,233,211,0.25); padding:0.625rem 0.875rem 0.375rem; margin:0;">
                {{ $group['group'] }}
            </p>
            <ul style="list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:2px;" role="list">
                @foreach($group['items'] as $item)
                @php $isActive = request()->routeIs($item['pattern']); @endphp
                <li>
                    <a
                        href="{{ route($item['route']) }}"
                        style="display:flex; align-items:center; gap:0.75rem; padding:0.5625rem 0.875rem; border-radius:8px; font-size:0.875rem; font-weight:{{ $isActive ? '600' : '400' }}; text-decoration:none; transition:background 0.15s, color 0.15s; background:{{ $isActive ? 'rgba(200,129,59,0.15)' : 'transparent' }}; color:{{ $isActive ? '#C8813B' : 'rgba(245,233,211,0.65)' }}; border-left:2.5px solid {{ $isActive ? '#C8813B' : 'transparent' }}; margin-left:-0.75px; position:relative;"
                        {{ $isActive ? 'aria-current="page"' : '' }}
                        onmouseover="if(!{{ $isActive ? 'true' : 'false' }}){this.style.background='rgba(245,233,211,0.06)';this.style.color='#F5E9D3';}"
                        onmouseout="if(!{{ $isActive ? 'true' : 'false' }}){this.style.background='transparent';this.style.color='rgba(245,233,211,0.65)';}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="flex-shrink:0; opacity:{{ $isActive ? '1' : '0.7' }};">
                            {!! $item['icon'] !!}
                        </svg>
                        <span style="flex:1;">{{ $item['label'] }}</span>
                        {{-- badge pending count --}}
                        @isset($item['badge'])
                        <span style="display:inline-flex; align-items:center; justify-content:center; min-width:18px; height:18px; padding:0 5px; border-radius:9999px; background:#DC2626; color:#fff; font-size:0.625rem; font-weight:700; flex-shrink:0;">
                            {{ $item['badge'] }}
                        </span>
                        @endisset
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach

    </div>

    {{-- footer: logout --}}
    <div style="padding:1rem 0.75rem 1.25rem; border-top:1px solid rgba(245,233,211,0.07); flex-shrink:0;">
        <form id="adm-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
            @csrf
        </form>
        <button
            type="button"
            id="btn-adm-logout"
            style="display:flex; align-items:center; gap:0.75rem; width:100%; padding:0.5625rem 0.875rem; border-radius:8px; font-size:0.875rem; font-weight:400; color:rgba(245,233,211,0.4); background:transparent; border:none; cursor:pointer; transition:background 0.15s, color 0.15s; text-align:left;"
            aria-label="Keluar dari akun admin"
            onmouseover="this.style.background='rgba(239,68,68,0.1)';this.style.color='#f87171';"
            onmouseout="this.style.background='transparent';this.style.color='rgba(245,233,211,0.4)';"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/>
            </svg>
            Logout
        </button>
    </div>
</nav>

<script>
(function () {
    var btn  = document.getElementById('btn-adm-logout');
    var form = document.getElementById('adm-logout-form');
    if (!btn || !form) return;
    btn.addEventListener('click', function () {
        window.SwalModal.fire({
            title: 'Yakin ingin keluar?', text: 'Sesi admin pusat akan diakhiri.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Ya, Logout', cancelButtonText: 'Batal', reverseButtons: true,
        }).then(function (r) { if (r.isConfirmed) form.submit(); });
    });
}());
</script>