{{-- ADMIN PROMOS FORM: form create/edit promo --}}
@extends('admin.layouts.app')

@section('title', isset($promo) ? "Edit Promo — Pod's Admin" : "Buat Promo — Pod's Admin")
@section('page-title', isset($promo) ? 'Edit Promo' : 'Buat Promo Baru')

@section('content')

@php
    $isEdit     = isset($promo);
    $formAction = $isEdit ? route('admin.promos.update', $promo['id']) : route('admin.promos.store');
    $initialSelected = isset($selectedProducts) ? $selectedProducts : [];
    $selectedProducts = old('product_ids', $initialSelected);
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">
<div style="max-width:720px;">

    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem; font-size:0.8125rem; color:var(--pods-muted);">
        <a href="{{ route('admin.promos.index') }}" style="color:var(--pods-caramel); text-decoration:none; font-weight:500;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Manajemen Promo</a>
        <span>›</span>
        <span style="color:var(--pods-espresso); font-weight:500;">{{ $isEdit ? 'Edit Promo' : 'Buat Promo Baru' }}</span>
    </div>

    <form method="POST" action="{{ $formAction }}" id="promo-form" novalidate>
        @csrf
        @if($isEdit) @method('PUT') @endif

    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        {{-- informasi Dasar ── --}}
        <div class="adm-card adm-animate" style="overflow:hidden;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #F0E8DC; background:var(--pods-espresso);">
                <h2 class="font-serif" style="font-size:0.9375rem; font-weight:700; color:#F5E9D3;">Informasi Dasar</h2>
            </div>
            <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1.25rem;">

                {{-- nama promo --}}
                <div>
                    <label for="name" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">
                        Nama Promo <span style="color:#DC2626;" aria-hidden="true">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $promo['name'] ?? '') }}" placeholder="Contoh: Happy Hour, Promo Lebaran" required
                        style="width:100%; padding:0.6875rem 1rem; border:1.5px solid {{ $errors->has('name') ? '#DC2626' : '#D4C4AE' }}; border-radius:10px; background:#FFFDF9; color:var(--pods-espresso); font-family:var(--font-sans); font-size:0.9375rem; transition:border-color 0.15s, box-shadow 0.15s;"
                        onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                        onblur="this.style.borderColor='{{ $errors->has('name') ? '#DC2626' : '#D4C4AE' }}'; this.style.boxShadow='none';"
                    >
                    @error('name') <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p> @enderror
                </div>

                {{-- cakupan: nasional atau lokal --}}
                <div>
                    <label style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">
                        Cakupan Promo <span style="color:#DC2626;" aria-hidden="true">*</span>
                    </label>
                    <div style="display:flex; gap:0.75rem; margin-bottom:0.75rem;">
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; padding:0.625rem 1rem; border-radius:8px; border:1.5px solid #D4C4AE; background:#FFFDF9; flex:1; transition:border-color 0.15s;" id="label-nasional">
                            <input type="radio" name="scope" value="national" id="scope-national"
                                {{ old('scope', ($promo['branch_id'] ?? null) === null ? 'national' : 'local') === 'national' ? 'checked' : '' }}
                                style="accent-color:#C8813B; cursor:pointer;" onchange="toggleBranchSelect()">
                            <div>
                                <p style="font-size:0.875rem; font-weight:600; color:var(--pods-espresso);">🌐 Nasional</p>
                                <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">Berlaku di semua cabang</p>
                            </div>
                        </label>
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; padding:0.625rem 1rem; border-radius:8px; border:1.5px solid #D4C4AE; background:#FFFDF9; flex:1; transition:border-color 0.15s;" id="label-lokal">
                            <input type="radio" name="scope" value="local" id="scope-local"
                                {{ old('scope', ($promo['branch_id'] ?? null) !== null ? 'local' : '') === 'local' ? 'checked' : '' }}
                                style="accent-color:#C8813B; cursor:pointer;" onchange="toggleBranchSelect()">
                            <div>
                                <p style="font-size:0.875rem; font-weight:600; color:var(--pods-espresso);">📍 Lokal</p>
                                <p style="font-size:0.75rem; color:var(--pods-muted); font-weight:300;">Hanya satu cabang tertentu</p>
                            </div>
                        </label>
                    </div>

                    {{-- select cabang: muncul jika lokal dipilih --}}
                    <div id="branch-select-wrap" style="display:{{ old('scope', ($promo['branch_id'] ?? null) !== null ? 'local' : 'national') === 'local' ? 'block' : 'none' }};">
                        <select name="branch_id" id="branch_id"
                            style="width:100%; padding:0.6875rem 2.5rem 0.6875rem 1rem; border:1.5px solid {{ $errors->has('branch_id') ? '#DC2626' : '#D4C4AE' }}; border-radius:10px; background:#FFFDF9; color:var(--pods-espresso); font-family:var(--font-sans); font-size:0.9375rem; appearance:none; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23A08060' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; cursor:pointer;"
                            onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                            onblur="this.style.borderColor='#D4C4AE'; this.style.boxShadow='none';"
                        >
                            <option value="" disabled selected>-- Pilih Cabang --</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch['id'] }}" {{ old('branch_id', $promo['branch_id'] ?? null) == $branch['id'] ? 'selected' : '' }}>{{ $branch['name'] }}</option>
                            @endforeach
                        </select>
                        @error('branch_id') <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- konfigurasi diskon --}}
        <div class="adm-card adm-animate" style="overflow:hidden; animation-delay:0.06s;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #F0E8DC; background:var(--pods-espresso);">
                <h2 class="font-serif" style="font-size:0.9375rem; font-weight:700; color:#F5E9D3;">Konfigurasi Diskon</h2>
            </div>
            <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1.25rem;">

                {{-- tipe diskon: persentase vs nominal --}}
                <div>
                    <label style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.625rem;">
                        Tipe Diskon <span style="color:#DC2626;" aria-hidden="true">*</span>
                    </label>
                    <div style="display:flex; border:1.5px solid #D4C4AE; border-radius:10px; overflow:hidden; background:#FFFDF9;">
                        <label style="flex:1; display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.75rem; cursor:pointer; transition:background 0.15s;" id="label-pct">
                            <input type="radio" name="discount_type" value="percentage" id="type-pct"
                                {{ old('discount_type', $promo['discount_type'] ?? 'percentage') === 'percentage' ? 'checked' : '' }}
                                style="accent-color:#C8813B;" onchange="updateDiscountUnit()">
                            <span style="font-size:0.875rem; font-weight:600; color:var(--pods-espresso);">% Persentase</span>
                        </label>
                        <div style="width:1.5px; background:#EDE0CC;"></div>
                        <label style="flex:1; display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.75rem; cursor:pointer; transition:background 0.15s;" id="label-nom">
                            <input type="radio" name="discount_type" value="nominal" id="type-nom"
                                {{ old('discount_type', $promo['discount_type'] ?? '') === 'nominal' ? 'checked' : '' }}
                                style="accent-color:#C8813B;" onchange="updateDiscountUnit()">
                            <span style="font-size:0.875rem; font-weight:600; color:var(--pods-espresso);">Rp Nominal</span>
                        </label>
                    </div>
                </div>

                {{-- nilai diskon --}}
                <div>
                    <label for="discount_value" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">
                        Nilai Diskon <span style="color:#DC2626;" aria-hidden="true">*</span>
                    </label>
                    <div style="position:relative;">
                        <span id="discount-unit-prefix" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); font-size:0.9375rem; font-weight:500; color:var(--pods-muted); pointer-events:none; display:{{ old('discount_type', $promo['discount_type'] ?? 'percentage') === 'nominal' ? 'block' : 'none' }};">Rp</span>
                        <input type="number" id="discount_value" name="discount_value"
                            value="{{ old('discount_value', $promo['discount_value'] ?? '') }}"
                            placeholder="{{ old('discount_type', $promo['discount_type'] ?? 'percentage') === 'percentage' ? 'Contoh: 15 (15%)' : 'Contoh: 5000' }}"
                            min="1" required
                            style="width:100%; padding:0.6875rem 3.5rem 0.6875rem 1rem; border:1.5px solid {{ $errors->has('discount_value') ? '#DC2626' : '#D4C4AE' }}; border-radius:10px; background:#FFFDF9; color:var(--pods-espresso); font-family:var(--font-sans); font-size:0.9375rem; transition:border-color 0.15s, box-shadow 0.15s; font-variant-numeric:tabular-nums;"
                            onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                            onblur="this.style.borderColor='{{ $errors->has('discount_value') ? '#DC2626' : '#D4C4AE' }}'; this.style.boxShadow='none';"
                        >
                        <span id="discount-unit-suffix" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); font-size:0.9375rem; font-weight:500; color:var(--pods-muted); pointer-events:none;">%</span>
                    </div>
                    @error('discount_value') <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p> @enderror
                </div>

                {{-- masa berlaku --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div>
                        <label for="start_date" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">
                            Tanggal Mulai <span style="color:#DC2626;" aria-hidden="true">*</span>
                        </label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $promo['start_date'] ?? '') }}" required
                            style="width:100%; padding:0.6875rem 1rem; border:1.5px solid {{ $errors->has('start_date') ? '#DC2626' : '#D4C4AE' }}; border-radius:10px; background:#FFFDF9; color:var(--pods-espresso); font-family:var(--font-sans); font-size:0.9375rem; cursor:pointer;"
                            onfocus="this.style.borderColor='#C8813B';" onblur="this.style.borderColor='#D4C4AE';">
                        @error('start_date') <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_date" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">
                            Tanggal Selesai <span style="color:#DC2626;" aria-hidden="true">*</span>
                        </label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $promo['end_date'] ?? '') }}" required
                            style="width:100%; padding:0.6875rem 1rem; border:1.5px solid {{ $errors->has('end_date') ? '#DC2626' : '#D4C4AE' }}; border-radius:10px; background:#FFFDF9; color:var(--pods-espresso); font-family:var(--font-sans); font-size:0.9375rem; cursor:pointer;"
                            onfocus="this.style.borderColor='#C8813B';" onblur="this.style.borderColor='#D4C4AE';">
                        @error('end_date') <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- produk --}}
        <div class="adm-card adm-animate" style="overflow:hidden; animation-delay:0.12s;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #F0E8DC; background:var(--pods-espresso); display:flex; align-items:center; justify-content:space-between;">
                <h2 class="font-serif" style="font-size:0.9375rem; font-weight:700; color:#F5E9D3;">Produk yang Mendapat Diskon</h2>
                <span style="font-size:0.75rem; color:rgba(245,233,211,0.5);">
                    <span id="selected-count">{{ count($selectedProducts) }}</span> dipilih
                </span>
            </div>
            <div style="padding:1.25rem 1.5rem;">

                {{-- tombol pilih semua / batal semua --}}
                <div style="display:flex; gap:0.5rem; margin-bottom:1rem;">
                    <button type="button" onclick="selectAllProducts(true)"
                        style="padding:0.375rem 0.875rem; border-radius:9999px; font-size:0.75rem; font-weight:500; border:1.5px solid #D4C4AE; background:transparent; color:var(--pods-espresso); cursor:pointer; transition:all 0.15s;"
                        onmouseover="this.style.borderColor='#C8813B'" onmouseout="this.style.borderColor='#D4C4AE'">
                        Pilih Semua
                    </button>
                    <button type="button" onclick="selectAllProducts(false)"
                        style="padding:0.375rem 0.875rem; border-radius:9999px; font-size:0.75rem; font-weight:500; border:1.5px solid #D4C4AE; background:transparent; color:var(--pods-espresso); cursor:pointer; transition:all 0.15s;"
                        onmouseover="this.style.borderColor='#C8813B'" onmouseout="this.style.borderColor='#D4C4AE'">
                        Batal Semua
                    </button>
                </div>

                {{-- grid checkbox produk dikelompokkan per kategori --}}
                @foreach($productsByCategory as $catName => $catProducts)
                <div style="margin-bottom:1rem;">
                    <p style="font-size:0.6875rem; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:var(--pods-caramel); margin-bottom:0.5rem;">{{ $catName }}</p>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:0.5rem;">
                        @foreach($catProducts as $prod)
                        <label
                            style="display:flex; align-items:center; gap:0.625rem; padding:0.5rem 0.75rem; border-radius:8px; border:1.5px solid #EDE0CC; background:#FFFDF9; cursor:pointer; transition:border-color 0.15s, background 0.15s;"
                            onmouseover="this.style.borderColor='#C8813B'; this.style.background='rgba(200,129,59,0.04)';"
                            onmouseout="if(!this.querySelector('input').checked){this.style.borderColor='#EDE0CC'; this.style.background='#FFFDF9';}"
                        >
                            <input
                                type="checkbox"
                                name="product_ids[]"
                                value="{{ $prod['id'] }}"
                                class="product-checkbox"
                                {{ in_array($prod['id'], (array)$selectedProducts) ? 'checked' : '' }}
                                style="accent-color:#C8813B; cursor:pointer; flex-shrink:0;"
                                onchange="updateSelectedCount(); updateLabelStyle(this);"
                            >
                            <span style="font-size:0.8125rem; font-weight:500; color:var(--pods-espresso);">{{ $prod['name'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @error('product_ids') <p style="font-size:0.8125rem; color:#DC2626; margin-top:0.375rem;">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- tombol submit --}}
        <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.75rem;">
            <a href="{{ route('admin.promos.index') }}" class="pods-btn-ghost">Batal</a>
            <button type="submit" class="pods-btn-primary" style="min-width:160px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Buat Promo' }}
            </button>
        </div>

    </div>
    </form>

</div>
</div>

@push('scripts')
<script>
(function () {
    function toggleBranchSelect() {
        var isLocal = document.getElementById('scope-local').checked;
        document.getElementById('branch-select-wrap').style.display = isLocal ? 'block' : 'none';
    }

    function updateDiscountUnit() {
        var isPct    = document.getElementById('type-pct').checked;
        var prefix   = document.getElementById('discount-unit-prefix');
        var suffix   = document.getElementById('discount-unit-suffix');
        var input    = document.getElementById('discount_value');
        prefix.style.display = isPct ? 'none' : 'block';
        suffix.style.display = isPct ? 'block' : 'none';
        input.placeholder    = isPct ? 'Contoh: 15 (15%)' : 'Contoh: 5000';
        if (isPct) { input.max = 100; } else { input.removeAttribute('max'); }
    }

    function updateSelectedCount() {
        var count = document.querySelectorAll('.product-checkbox:checked').length;
        var el    = document.getElementById('selected-count');
        if (el) el.textContent = count;
    }

    function updateLabelStyle(checkbox) {
        var label = checkbox.closest('label');
        if (!label) return;
        label.style.borderColor = checkbox.checked ? '#C8813B' : '#EDE0CC';
        label.style.background  = checkbox.checked ? 'rgba(200,129,59,0.06)' : '#FFFDF9';
    }

    function selectAllProducts(check) {
        document.querySelectorAll('.product-checkbox').forEach(function (cb) {
            cb.checked = check;
            updateLabelStyle(cb);
        });
        updateSelectedCount();
    }

    document.querySelectorAll('.product-checkbox').forEach(function (cb) {
        updateLabelStyle(cb);
    });

    window.toggleBranchSelect  = toggleBranchSelect;
    window.updateDiscountUnit  = updateDiscountUnit;
    window.updateSelectedCount = updateSelectedCount;
    window.updateLabelStyle    = updateLabelStyle;
    window.selectAllProducts   = selectAllProducts;
}());
</script>
@endpush

@endsection