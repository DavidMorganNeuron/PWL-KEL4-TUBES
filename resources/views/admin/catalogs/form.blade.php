{{-- ADMIN CATALOGS FORM: form create/edit produk + upload gambar --}}
@extends('admin.layouts.app')

@section('title', isset($product) ? "Edit Produk — Pod's Admin" : "Tambah Produk — Pod's Admin")
@section('page-title', isset($product) ? 'Edit Produk' : 'Tambah Produk Baru')

@section('content')

@php
    /* deteksi mode edit vs create */
    $isEdit   = isset($product);
    $formAction = $isEdit
        ? route('admin.catalogs.update', $product->id_products)
        : route('admin.catalogs.store');
    $branches = $branches ?? collect();
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">
<div style="max-width:680px;">

    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem; font-size:0.8125rem; color:var(--pods-muted);">
        <a href="{{ route('admin.catalogs.index') }}" style="color:var(--pods-caramel); text-decoration:none; font-weight:500;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
            Manajemen Produk
        </a>
        <span>›</span>
        <span style="color:var(--pods-espresso); font-weight:500;">{{ $isEdit ? 'Edit Produk' : 'Tambah Produk' }}</span>
    </div>

    <div class="adm-card adm-animate" style="overflow:hidden;">

        {{-- card header --}}
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F0E8DC; background:var(--pods-espresso);">
            <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.2em; text-transform:uppercase; color:rgba(245,233,211,0.45); margin-bottom:0.25rem;">
                Katalog Menu
            </p>
            <h2 class="font-serif" style="font-size:1.0625rem; font-weight:700; color:#F5E9D3;">
                {{ $isEdit ? 'Edit: ' . ($product->name ?? '') : 'Tambah Produk Baru' }}
            </h2>
        </div>

        <div style="padding:1.75rem 1.5rem;">
            <form
                method="POST"
                action="{{ $formAction }}"
                enctype="multipart/form-data"
                id="product-form"
                novalidate
            >
                @csrf
                @if($isEdit)
                @method('PUT')
                @endif

                    {{-- upload gambar --}}
                    <div style="margin-bottom:1.75rem;">
                        <label style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">
                            Gambar Produk
                            <span style="font-size:0.75rem; font-weight:300; color:var(--pods-muted);">(JPG/PNG, maks. 2MB)</span>
                        </label>

                        {{-- preview gambar --}}
                        <div
                            id="image-drop-zone"
                            style="border:2px dashed #D4C4AE; border-radius:12px; background:#FFFBF4; padding:2rem; text-align:center; cursor:pointer; transition:border-color 0.2s, background 0.2s; position:relative; overflow:hidden;"
                            onclick="document.getElementById('image-input').click()"
                            onmouseover="this.style.borderColor='#C8813B'; this.style.background='rgba(200,129,59,0.04)';"
                            onmouseout="this.style.borderColor='#D4C4AE'; this.style.background='#FFFBF4';"
                        >
                            {{-- placeholder --}}
                            <div id="image-placeholder" {{ $isEdit && $product->getRawOriginal('image_url') ? 'style=display:none' : '' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#D4C4AE" stroke-width="1.5" style="margin:0 auto 0.75rem;" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p style="font-size:0.875rem; font-weight:500; color:var(--pods-muted); margin-bottom:0.25rem;">
                                    Klik untuk pilih gambar
                                </p>
                                <p style="font-size:0.75rem; color:#C4A882; font-weight:300;">atau seret file ke sini</p>
                            </div>

                            {{-- preview gambar setelah dipilih --}}
                            <div id="image-preview-wrap" {{ $isEdit && $product->getRawOriginal('image_url') ? '' : 'style=display:none' }}>
                                <img id="image-preview" src="{{ $isEdit && $product->getRawOriginal('image_url') ? $product->image_url : '' }}" alt="Preview gambar produk" style="max-height:180px; max-width:100%; object-fit:contain; border-radius:8px;">
                                <button
                                    type="button"
                                    id="btn-remove-image"
                                    style="position:absolute; top:0.5rem; right:0.5rem; background:#1C0F0A; border:none; border-radius:6px; padding:0.375rem 0.625rem; font-size:0.75rem; font-weight:600; color:#F5E9D3; cursor:pointer;"
                                    onclick="event.stopPropagation(); removeImage();"
                                >
                                    × Hapus
                                </button>
                            </div>
                        </div>

                        <input
                            type="file"
                            id="image-input"
                            name="image"
                            accept=".jpg,.jpeg,.png"
                            style="display:none;"
                        >

                        @error('image')
                        <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p>
                        @enderror

                        {{-- jika edit: tampilkan info gambar yang ada --}}
                        @if($isEdit && $product->getRawOriginal('image_url'))
                        <p id="existing-image-note" style="font-size:0.75rem; color:var(--pods-muted); margin-top:0.375rem;">
                            ℹ Gambar saat ini: <code style="font-size:0.75rem;">{{ $product->getRawOriginal('image_url') }}</code>. Upload baru untuk mengganti.
                        </p>
                        @endif
                    </div>

                {{-- nama produk --}}
                <div style="margin-bottom:1.375rem;">
                    <label for="name" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">
                        Nama Produk <span style="color:#DC2626;" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $isEdit ? $product->name : '') }}"
                        placeholder="Contoh: Caramel Macchiato"
                        required
                        style="width:100%; padding:0.6875rem 1rem; border:1.5px solid {{ $errors->has('name') ? '#DC2626' : '#D4C4AE' }}; border-radius:10px; background:#FFFDF9; color:var(--pods-espresso); font-family:var(--font-sans); font-size:0.9375rem; transition:border-color 0.15s, box-shadow 0.15s; {{ $errors->has('name') ? 'box-shadow:0 0 0 3px rgba(220,38,38,0.12);' : '' }}"
                        onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                        onblur="this.style.borderColor='{{ $errors->has('name') ? '#DC2626' : '#D4C4AE' }}'; this.style.boxShadow='none';"
                    >
                    @error('name')
                    <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- kategori --}}
                <div style="margin-bottom:1.375rem;">
                    <label for="category_id" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">
                        Kategori <span style="color:#DC2626;" aria-hidden="true">*</span>
                    </label>
                    <select
                        id="category_id"
                        name="category_id"
                        required
                        style="width:100%; padding:0.6875rem 2.5rem 0.6875rem 1rem; border:1.5px solid {{ $errors->has('category_id') ? '#DC2626' : '#D4C4AE' }}; border-radius:10px; background:#FFFDF9; color:var(--pods-espresso); font-family:var(--font-sans); font-size:0.9375rem; appearance:none; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23A08060' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; cursor:pointer; transition:border-color 0.15s, box-shadow 0.15s;"
                        onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                        onblur="this.style.borderColor='{{ $errors->has('category_id') ? '#DC2626' : '#D4C4AE' }}'; this.style.boxShadow='none';"
                    >
                        <option value="" disabled {{ !old('category_id', $isEdit ? $product->category_id : null) ? 'selected' : '' }}>-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id_categories }}" {{ old('category_id', $isEdit ? $product->category_id : null) == $cat->id_categories ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- harga --}}
                <div style="margin-bottom:1.375rem;">
                    <label for="base_price" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">
                        Harga Dasar (Rp) <span style="color:#DC2626;" aria-hidden="true">*</span>
                    </label>
                    <div style="position:relative;">
                        <span style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); font-size:0.9375rem; font-weight:500; color:var(--pods-muted); pointer-events:none;">Rp</span>
                        <input
                            type="number"
                            id="base_price"
                            name="base_price"
                            value="{{ old('base_price', $isEdit ? $product->base_price : '') }}"
                            placeholder="25000"
                            min="1000"
                            max="999999"
                            required
                            style="width:100%; padding:0.6875rem 1rem 0.6875rem 3rem; border:1.5px solid {{ $errors->has('base_price') ? '#DC2626' : '#D4C4AE' }}; border-radius:10px; background:#FFFDF9; color:var(--pods-espresso); font-family:var(--font-sans); font-size:0.9375rem; transition:border-color 0.15s, box-shadow 0.15s; font-variant-numeric:tabular-nums; {{ $errors->has('base_price') ? 'box-shadow:0 0 0 3px rgba(220,38,38,0.12);' : '' }}"
                            onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                            onblur="this.style.borderColor='{{ $errors->has('base_price') ? '#DC2626' : '#D4C4AE' }}'; this.style.boxShadow='none';"
                        >
                    </div>
                    @error('base_price')
                    <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- status ketersediaan --}}
                <div style="margin-bottom:2rem; padding:1rem; background:#FBF6EE; border-radius:10px; border:1px solid #EDE0CC; display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <p style="font-size:0.875rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.125rem;">Ketersediaan Menu</p>
                        <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">Nonaktif = produk disembunyikan dari semua cabang (Kill-Switch)</p>
                    </div>
                    <label style="display:flex; align-items:center; gap:0.625rem; cursor:pointer;">
                        <input
                            type="checkbox"
                            id="is_available"
                            name="is_available"
                            value="1"
                            {{ old('is_available', $isEdit ? $product->is_available : true) ? 'checked' : '' }}
                            style="width:1rem; height:1rem; accent-color:#C8813B; cursor:pointer;"
                        >
                        <span style="font-size:0.875rem; font-weight:500; color:var(--pods-espresso);">Aktif (tersedia di menu)</span>
                    </label>
                </div>

                {{-- stok awal per cabang (hanya saat create) --}}
                @if(!$isEdit)
                <div style="margin-bottom:2rem; padding:1.125rem 1.25rem; background:#FFFBF4; border-radius:10px; border:1px solid #D4C4AE;">
                    <p style="font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.75rem;">
                        Stok Awal per Cabang
                        <span style="font-size:0.75rem; font-weight:300; color:var(--pods-muted);">(isi 0 jika belum tersedia)</span>
                    </p>
                    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.875rem;">
                        @foreach($branches as $branch)
                        <div>
                            <label for="stock_{{ $branch->id_branches }}" style="display:block; font-size:0.6875rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">{{ $branch->name }}</label>
                            <div style="position:relative;">
                                <input type="number" id="stock_{{ $branch->id_branches }}" name="stock_{{ $branch->id_branches }}" value="0" min="0"
                                    style="width:100%; padding:0.5rem 0.75rem; border:1.5px solid #D4C4AE; border-radius:8px; background:#FFFDF9; color:var(--pods-espresso); font-size:0.875rem; font-variant-numeric:tabular-nums; transition:border-color 0.15s;"
                                    onfocus="this.style.borderColor='#C8813B'" onblur="this.style.borderColor='#D4C4AE'">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- tombol aksi --}}
                <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:1.25rem; border-top:1px solid #F0E8DC;">
                    <a href="{{ route('admin.catalogs.index') }}" class="pods-btn-ghost">
                        Batal
                    </a>
                    <button type="submit" class="pods-btn-primary" style="min-width:140px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Produk' }}
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
(function () {
    var input       = document.getElementById('image-input');
    var dropZone    = document.getElementById('image-drop-zone');
    var placeholder = document.getElementById('image-placeholder');
    var previewWrap = document.getElementById('image-preview-wrap');
    var previewImg  = document.getElementById('image-preview');
    var MAX_BYTES   = 2 * 1024 * 1024; /* 2MB */

    function showPreview(file) {
        if (!file) return;

        /* validasi tipe MIME */
        if (!['image/jpeg', 'image/png'].includes(file.type)) {
            window.SwalModal.fire({ icon: 'error', title: 'Format Tidak Didukung', text: 'Hanya file JPG dan PNG yang diizinkan.' });
            input.value = '';
            return;
        }

        /* validasi ukuran */
        if (file.size > MAX_BYTES) {
            window.SwalModal.fire({ icon: 'error', title: 'Ukuran Terlalu Besar', text: 'Ukuran file maksimum adalah 2MB.' });
            input.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src           = e.target.result;
            placeholder.style.display = 'none';
            previewWrap.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    input.addEventListener('change', function () {
        if (this.files && this.files[0]) showPreview(this.files[0]);
    });

    /* drag-and-drop */
    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        this.style.borderColor = '#C8813B';
        this.style.background  = 'rgba(200,129,59,0.06)';
    });
    dropZone.addEventListener('dragleave', function () {
        this.style.borderColor = '#D4C4AE';
        this.style.background  = '#FFFBF4';
    });
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        this.style.borderColor = '#D4C4AE';
        this.style.background  = '#FFFBF4';
        var file = e.dataTransfer.files[0];
        if (file) {
            /* inject file ke input agar ikut ter-submit bersama form */
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            showPreview(file);
        }
    });

    window.removeImage = function () {
        input.value           = '';
        previewImg.src        = '';
        previewWrap.style.display = 'none';
        placeholder.style.display = 'block';
        var note = document.getElementById('existing-image-note');
        if (note) note.style.display = 'none';
    };
}());
</script>
@endpush

@endsection