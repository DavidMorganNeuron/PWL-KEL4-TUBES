{{-- ADMIN CATALOGS PRODUCT: tabel manajemen produk + kill-switch + filter --}}
@extends('admin.layouts.app')

@section('title', "Manajemen Produk — Pod's Admin")
@section('page-title', 'Manajemen Produk')

@section('content')

@php
    $products = [
        ['id' => 1,  'category' => 'Kopi',     'name' => 'Caramel Macchiato', 'base_price' => 28000, 'is_available' => true,  'image' => true],
        ['id' => 2,  'category' => 'Kopi',     'name' => 'Iced Americano',    'base_price' => 22000, 'is_available' => true,  'image' => true],
        ['id' => 3,  'category' => 'Kopi',     'name' => 'Brown Sugar Latte', 'base_price' => 29000, 'is_available' => true,  'image' => false],
        ['id' => 4,  'category' => 'Kopi',     'name' => 'Cold Brew',         'base_price' => 26000, 'is_available' => false, 'image' => false],
        ['id' => 5,  'category' => 'Kopi',     'name' => 'Cappuccino',        'base_price' => 25000, 'is_available' => true,  'image' => true],
        ['id' => 6,  'category' => 'Non-Kopi', 'name' => 'Matcha Latte',      'base_price' => 29000, 'is_available' => true,  'image' => true],
        ['id' => 7,  'category' => 'Non-Kopi', 'name' => 'Taro Latte',        'base_price' => 28000, 'is_available' => true,  'image' => false],
        ['id' => 8,  'category' => 'Non-Kopi', 'name' => 'Chocolate Frappe',  'base_price' => 30000, 'is_available' => false, 'image' => true],
        ['id' => 9,  'category' => 'Makanan',  'name' => 'Croissant Plain',   'base_price' => 22000, 'is_available' => true,  'image' => false],
        ['id' => 10, 'category' => 'Makanan',  'name' => 'Croissant Almond',  'base_price' => 24000, 'is_available' => true,  'image' => true],
        ['id' => 11, 'category' => 'Makanan',  'name' => 'Banana Cake',       'base_price' => 20000, 'is_available' => true,  'image' => false],
        ['id' => 12, 'category' => 'Non-Kopi', 'name' => 'Oreo Shake',        'base_price' => 26000, 'is_available' => false, 'image' => false],
    ];
    $categories = array_unique(array_column($products, 'category'));
    sort($categories);
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

    {{-- ================================================================
         HEADER: stat ringkasan + aksi tambah produk
    ================================================================ --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
            @php
                $totalActive   = count(array_filter($products, fn($p) => $p['is_available']));
                $totalInactive = count($products) - $totalActive;
            @endphp
            <div style="display:flex; align-items:center; gap:0.5rem; background:#D1FAE5; border-radius:9999px; padding:0.375rem 0.875rem 0.375rem 0.625rem;">
                <span style="width:7px; height:7px; border-radius:9999px; background:#059669;" aria-hidden="true"></span>
                <span style="font-size:0.75rem; font-weight:600; color:#065F46;">{{ $totalActive }} Aktif</span>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem; background:#FEE2E2; border-radius:9999px; padding:0.375rem 0.875rem 0.375rem 0.625rem;">
                <span style="width:7px; height:7px; border-radius:9999px; background:#DC2626;" aria-hidden="true"></span>
                <span style="font-size:0.75rem; font-weight:600; color:#991B1B;">{{ $totalInactive }} Nonaktif</span>
            </div>
        </div>
        <a href="{{ route('admin.catalogs.create') }}" class="pods-btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </a>
    </div>

    {{-- ================================================================
         FILTER KATEGORI
    ================================================================ --}}
    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem;">
        <button type="button" class="cat-filter-btn cat-filter-active" data-cat="all"
            style="padding:0.4375rem 1rem; border-radius:9999px; font-size:0.8125rem; font-weight:600; border:1.5px solid #C8813B; background:#C8813B; color:#1C0F0A; cursor:pointer; transition:all 0.15s;">
            Semua
        </button>
        @foreach($categories as $cat)
        <button type="button" class="cat-filter-btn" data-cat="{{ $cat }}"
            style="padding:0.4375rem 1rem; border-radius:9999px; font-size:0.8125rem; font-weight:500; border:1.5px solid #D4C4AE; background:#FFFDF9; color:var(--pods-espresso); cursor:pointer; transition:all 0.15s;"
            onmouseover="if(!this.classList.contains('cat-filter-active'))this.style.borderColor='#C8813B';"
            onmouseout="if(!this.classList.contains('cat-filter-active'))this.style.borderColor='#D4C4AE';">
            {{ $cat }}
        </button>
        @endforeach
    </div>

    {{-- ================================================================
         TABEL PRODUK
    ================================================================ --}}
    <div class="adm-card adm-animate" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;" role="table" aria-label="Tabel manajemen produk">
                <thead>
                    <tr style="background:var(--pods-espresso);">
                        <th style="padding:0.75rem 1.5rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">#</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Gambar</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Nama Produk</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Kategori</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6); text-align:right;">Harga Dasar</th>
                        <th style="padding:0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6); text-align:center;">Status</th>
                        <th style="padding:0.75rem 1.5rem 0.75rem 1rem; font-size:0.6875rem; font-weight:600; letter-spacing:0.16em; text-transform:uppercase; color:rgba(245,233,211,0.6);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $idx => $product)
                    <tr
                        class="product-row"
                        data-category="{{ $product['category'] }}"
                        style="border-top:1px solid #F0E8DC; transition:background 0.15s;"
                        onmouseover="this.style.background='#FFFBF4'"
                        onmouseout="this.style.background='transparent'"
                    >
                        <td style="padding:1rem 1.5rem; font-size:0.8125rem; color:var(--pods-muted); font-weight:300; font-variant-numeric:tabular-nums;">{{ $idx + 1 }}</td>

                        {{-- thumbnail gambar produk --}}
                        <td style="padding:0.75rem 1rem;">
                            @if($product['image'])
                            <div style="width:44px; height:44px; border-radius:8px; background:#EDE0CC; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                                <span style="font-size:1.25rem;" aria-hidden="true">☕</span>
                            </div>
                            @else
                            <div style="width:44px; height:44px; border-radius:8px; background:#F5ECE0; border:1.5px dashed #D4C4AE; display:flex; align-items:center; justify-content:center;" title="Belum ada gambar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#D4C4AE" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                        </td>

                        <td style="padding:1rem; font-size:0.9375rem; font-weight:500; color:var(--pods-espresso); white-space:nowrap;">{{ $product['name'] }}</td>

                        <td style="padding:1rem;">
                            <span style="display:inline-block; padding:2px 10px; border-radius:9999px; background:rgba(200,129,59,0.1); border:1px solid rgba(200,129,59,0.2); font-size:0.6875rem; font-weight:600; color:#92400E; white-space:nowrap;">
                                {{ $product['category'] }}
                            </span>
                        </td>

                        <td style="padding:1rem; font-size:0.9375rem; font-weight:600; color:var(--pods-espresso); text-align:right; font-variant-numeric:tabular-nums; white-space:nowrap;">
                            Rp {{ number_format($product['base_price'], 0, ',', '.') }}
                        </td>

                        {{-- fitur kill is_available --}}
                        <td style="padding:1rem; text-align:center;">
                            <form method="POST" action="{{ route('admin.catalogs.toggle', $product['id']) }}" class="toggle-form" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="button"
                                    class="kill-switch-btn"
                                    data-product-id="{{ $product['id'] }}"
                                    data-product-name="{{ $product['name'] }}"
                                    data-active="{{ $product['is_available'] ? '1' : '0' }}"
                                    role="switch"
                                    aria-checked="{{ $product['is_available'] ? 'true' : 'false' }}"
                                    aria-label="{{ $product['is_available'] ? 'Nonaktifkan' : 'Aktifkan' }} {{ $product['name'] }}"
                                    style="
                                        display: inline-flex; align-items: center; gap: 0.5rem;
                                        padding: 0.3125rem 0.75rem; border-radius: 9999px; border: none; cursor: pointer;
                                        font-size: 0.75rem; font-weight: 600; transition: all 0.2s;
                                        background: {{ $product['is_available'] ? '#D1FAE5' : '#FEE2E2' }};
                                        color: {{ $product['is_available'] ? '#065F46' : '#991B1B' }};
                                    "
                                >
                                    <span style="width:8px; height:8px; border-radius:9999px; background:{{ $product['is_available'] ? '#059669' : '#DC2626' }}; flex-shrink:0;" aria-hidden="true"></span>
                                    {{ $product['is_available'] ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>

                        <td style="padding:1rem 1.5rem 1rem 1rem;">
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <a href="{{ route('admin.catalogs.edit', $product['id']) }}" class="pods-btn-ghost" style="font-size:0.8125rem; padding:0.375rem 0.875rem;" aria-label="Edit {{ $product['name'] }}">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.catalogs.destroy', $product['id']) }}" class="delete-form" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        class="pods-btn-danger delete-btn"
                                        data-product-name="{{ $product['name'] }}"
                                        style="font-size:0.8125rem; padding:0.375rem 0.875rem;"
                                        aria-label="Hapus {{ $product['name'] }}"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:0.75rem 1.5rem; background:#FBF6EE; border-top:1px solid #EDE0CC; display:flex; justify-content:space-between; align-items:center;">
            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">
                Menampilkan <span id="product-count">{{ count($products) }}</span> dari {{ count($products) }} produk
            </p>
            <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">
                Data diperbarui: {{ now()->format('d M Y, H:i') }} WIB
            </p>
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    /* filter kategori */
    var filterBtns   = document.querySelectorAll('.cat-filter-btn');
    var productRows  = document.querySelectorAll('.product-row');
    var productCount = document.getElementById('product-count');

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cat = btn.dataset.cat;

            filterBtns.forEach(function (b) {
                b.classList.remove('cat-filter-active');
                b.style.background = '#FFFDF9'; b.style.borderColor = '#D4C4AE'; b.style.color = 'var(--pods-espresso)'; b.style.fontWeight = '500';
            });
            btn.classList.add('cat-filter-active');
            btn.style.background = '#C8813B'; btn.style.borderColor = '#C8813B'; btn.style.color = '#1C0F0A'; btn.style.fontWeight = '600';

            var visible = 0;
            productRows.forEach(function (row) {
                var show = cat === 'all' || row.dataset.category === cat;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (productCount) productCount.textContent = visible;
        });
    });

    /* kill alert */
    document.querySelectorAll('.kill-switch-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var isActive = btn.dataset.active === '1';
            var name     = btn.dataset.productName;
            var form     = btn.closest('form');

            window.SwalModal.fire({
                title:             isActive ? 'Nonaktifkan Produk?' : 'Aktifkan Produk?',
                html:              '<p style="font-size:0.875rem;color:#6B7280;">Produk <strong>' + name + '</strong> akan ' + (isActive ? 'disembunyikan dari menu customer di semua cabang.' : 'kembali tampil di menu customer.') + '</p>',
                icon:              'warning',
                showCancelButton:  true,
                confirmButtonText: isActive ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan',
                confirmButtonColor: isActive ? '#DC2626' : '#059669',
                cancelButtonText:  'Batal',
                reverseButtons:    true,
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    /* hapus produk */
    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var name = btn.dataset.productName;
            var form = btn.closest('form');

            window.SwalModal.fire({
                title:             'Hapus Produk?',
                html:              '<p style="font-size:0.875rem;color:#6B7280;">Produk <strong>' + name + '</strong> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>',
                icon:              'warning',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Hapus',
                confirmButtonColor:'#DC2626',
                cancelButtonText:  'Batal',
                reverseButtons:    true,
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });
}());
</script>
@endpush

@endsection