{{-- MANAGER REQUEST FORM: formulir pengajuan restock ke admin pusat --}}
@extends('manager.layouts.app')

@section('title', "Ajukan Restock — Pod's Manager")
@section('page-title', 'Ajukan Restock')

@section('content')

@php
    /* data dummy: daftar produk dengan stok saat ini */
    $products = [
        ['id' => 1,  'category' => 'Kopi',      'name' => 'Biji Kopi Arabika',         'physical_qty' => 5,   'unit' => 'kg'],
        ['id' => 2,  'category' => 'Kopi',      'name' => 'Biji Kopi Robusta',          'physical_qty' => 18,  'unit' => 'kg'],
        ['id' => 3,  'category' => 'Susu',      'name' => 'Susu Sapi Full Cream (1L)',  'physical_qty' => 24,  'unit' => 'pcs'],
        ['id' => 4,  'category' => 'Susu',      'name' => 'Susu Oat (1L)',              'physical_qty' => 2,   'unit' => 'pcs'],
        ['id' => 5,  'category' => 'Susu',      'name' => 'Susu Almond (1L)',           'physical_qty' => 11,  'unit' => 'pcs'],
        ['id' => 6,  'category' => 'Sirup',     'name' => 'Sirup Karamel (500ml)',      'physical_qty' => 3,   'unit' => 'botol'],
        ['id' => 7,  'category' => 'Sirup',     'name' => 'Sirup Hazelnut (500ml)',     'physical_qty' => 7,   'unit' => 'botol'],
        ['id' => 8,  'category' => 'Sirup',     'name' => 'Sirup Vanilla (500ml)',      'physical_qty' => 12,  'unit' => 'botol'],
        ['id' => 9,  'category' => 'Makanan',   'name' => 'Croissant Plain',            'physical_qty' => 30,  'unit' => 'pcs'],
        ['id' => 10, 'category' => 'Makanan',   'name' => 'Croissant Almond',           'physical_qty' => 22,  'unit' => 'pcs'],
        ['id' => 11, 'category' => 'Packaging', 'name' => 'Cup Hot 12oz',               'physical_qty' => 150, 'unit' => 'pcs'],
        ['id' => 12, 'category' => 'Packaging', 'name' => 'Cup Cold 16oz',              'physical_qty' => 42,  'unit' => 'pcs'],
    ];

    /* riwayat pengajuan restock dari request_log */
    $requestHistory = [
        ['id' => 'REQ-042', 'product' => 'Susu Oat (1L)',          'requested_qty' => 20, 'unit' => 'pcs',   'status' => 'approved', 'note' => null,              'date' => '14 Mei 2026'],
        ['id' => 'REQ-041', 'product' => 'Sirup Karamel (500ml)',  'requested_qty' => 10, 'unit' => 'botol', 'status' => 'rejected', 'note' => 'Stok pusat habis','date' => '12 Mei 2026'],
        ['id' => 'REQ-040', 'product' => 'Biji Kopi Arabika',      'requested_qty' => 15, 'unit' => 'kg',    'status' => 'pending',  'note' => null,              'date' => '16 Mei 2026'],
        ['id' => 'REQ-039', 'product' => 'Croissant Almond',       'requested_qty' => 50, 'unit' => 'pcs',   'status' => 'approved', 'note' => null,              'date' => '10 Mei 2026'],
    ];

    $historyBadge = [
        'pending'  => ['bg' => '#FEF3C7', 'text' => '#92400E', 'dot' => '#D97706', 'label' => 'Menunggu',   'pulse' => true],
        'approved' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#059669', 'label' => 'Disetujui',  'pulse' => false],
        'rejected' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#DC2626', 'label' => 'Ditolak',    'pulse' => false],
    ];

    $preselectedId = request()->query('product_id');
@endphp

<div style="padding: 2rem; background: #F0E8DC; min-height: calc(100vh - 64px);">
<div style="display: grid; grid-template-columns: 1fr 380px; gap: 1.25rem; align-items: flex-start;">

    {{-- ================================================================
         KOLOM KIRI: FORMULIR PENGAJUAN
    ================================================================ --}}
    <div>

        {{-- FORM CARD --}}
        <div class="mgr-card mgr-animate" style="overflow: hidden; margin-bottom: 1.25rem;">

            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F0E8DC; background: var(--pods-espresso);">
                <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(245,233,211,0.5); margin-bottom: 0.25rem;">Request ke Admin Pusat</p>
                <h2 class="font-serif" style="font-size: 1.0625rem; font-weight: 700; color: #F5E9D3;">Formulir Pengajuan Restock</h2>
            </div>

            <div style="padding: 1.75rem 1.5rem;">

                <form method="POST" action="{{ route('manager.request_form.store') }}" id="restock-form" novalidate>
                    @csrf

                    {{-- FIELD: PILIH PRODUK --}}
                    <div style="margin-bottom: 1.5rem;">
                        <label
                            for="product_id"
                            style="display: block; font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); margin-bottom: 0.5rem;"
                        >
                            Produk / Bahan
                            <span style="color: #DC2626;" aria-hidden="true">*</span>
                        </label>
                        <select
                            name="product_id"
                            id="product_id"
                            required
                            style="
                                width: 100%;
                                padding: 0.6875rem 1rem;
                                border: 1.5px solid #D4C4AE;
                                border-radius: 10px;
                                background: #FFFDF9;
                                color: var(--pods-espresso);
                                font-family: var(--font-sans);
                                font-size: 0.9375rem;
                                transition: border-color 0.15s, box-shadow 0.15s;
                                appearance: none;
                                background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23A08060' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");
                                background-repeat: no-repeat;
                                background-position: right 1rem center;
                                padding-right: 2.5rem;
                                cursor: pointer;
                            "
                            onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                            onblur="this.style.borderColor='#D4C4AE'; this.style.boxShadow='none';"
                            onchange="updateStockPreview(this.value)"
                        >
                            <option value="" disabled {{ $preselectedId ? '' : 'selected' }}>-- Pilih produk atau bahan --</option>
                            @php $currentCat = null; @endphp
                            @foreach($products as $product)
                                @if($currentCat !== $product['category'])
                                    @if($currentCat !== null) </optgroup> @endif
                                    <optgroup label="{{ $product['category'] }}">
                                    @php $currentCat = $product['category']; @endphp
                                @endif
                                <option
                                    value="{{ $product['id'] }}"
                                    data-physical="{{ $product['physical_qty'] }}"
                                    data-unit="{{ $product['unit'] }}"
                                    {{ (string)$preselectedId === (string)$product['id'] ? 'selected' : '' }}
                                >
                                    {{ $product['name'] }} (Stok: {{ $product['physical_qty'] }} {{ $product['unit'] }})
                                </option>
                            @endforeach
                            </optgroup>
                        </select>
                        <p id="err-product_id" class="form-error" style="display: none; font-size: 0.8125rem; color: #DC2626; margin-top: 0.375rem;"></p>
                        @error('product_id')
                        <p style="font-size: 0.8125rem; color: #DC2626; margin-top: 0.375rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PREVIEW STOK SAAT INI: muncul setelah produk dipilih --}}
                    <div id="stock-preview" style="display: none; margin-bottom: 1.5rem; padding: 0.875rem 1rem; background: #FBF6EE; border: 1px solid #EDE0CC; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.8125rem; font-weight: 500; color: var(--pods-espresso);">Stok fisik saat ini:</span>
                            <span id="preview-qty" style="font-size: 1rem; font-weight: 700; color: var(--pods-espresso); font-variant-numeric: tabular-nums;"></span>
                        </div>
                    </div>

                    {{-- JUMLAH RESTOCK --}}
                    <div style="margin-bottom: 1.5rem;">
                        <label
                            for="requested_qty"
                            style="display: block; font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); margin-bottom: 0.5rem;"
                        >
                            Jumlah Restock yang Diminta
                            <span style="color: #DC2626;" aria-hidden="true">*</span>
                        </label>
                        <div style="position: relative;">
                            <input
                                type="number"
                                name="requested_qty"
                                id="requested_qty"
                                min="1"
                                max="9999"
                                placeholder="Masukkan jumlah..."
                                required
                                value="{{ old('requested_qty') }}"
                                style="
                                    width: 100%;
                                    padding: 0.6875rem 3.5rem 0.6875rem 1rem;
                                    border: 1.5px solid {{ $errors->has('requested_qty') ? '#DC2626' : '#D4C4AE' }};
                                    border-radius: 10px;
                                    background: #FFFDF9;
                                    color: var(--pods-espresso);
                                    font-family: var(--font-sans);
                                    font-size: 0.9375rem;
                                    transition: border-color 0.15s, box-shadow 0.15s;
                                    {{ $errors->has('requested_qty') ? 'box-shadow: 0 0 0 3px rgba(220,38,38,0.12);' : '' }}
                                "
                                onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                                onblur="this.style.borderColor='{{ $errors->has('requested_qty') ? '#DC2626' : '#D4C4AE' }}'; this.style.boxShadow='none';"
                            >
                            {{-- label satuan dinamis --}}
                            <span id="unit-label" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); font-size: 0.8125rem; color: var(--pods-muted); font-weight: 500; pointer-events: none;">pcs</span>
                        </div>
                        @error('requested_qty')
                        <p style="font-size: 0.8125rem; color: #DC2626; margin-top: 0.375rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CATATAN TAMBAHAN --}}
                    <div style="margin-bottom: 2rem;">
                        <label
                            for="notes"
                            style="display: block; font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); margin-bottom: 0.5rem;"
                        >
                            Catatan Tambahan
                            <span style="font-size: 0.75rem; font-weight: 300; color: var(--pods-muted);">(opsional)</span>
                        </label>
                        <textarea
                            name="notes"
                            id="notes"
                            rows="3"
                            placeholder="Tambahkan alasan atau keterangan tambahan untuk admin..."
                            style="
                                width: 100%;
                                padding: 0.6875rem 1rem;
                                border: 1.5px solid #D4C4AE;
                                border-radius: 10px;
                                background: #FFFDF9;
                                color: var(--pods-espresso);
                                font-family: var(--font-sans);
                                font-size: 0.9375rem;
                                resize: vertical;
                                transition: border-color 0.15s, box-shadow 0.15s;
                                line-height: 1.55;
                            "
                            onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                            onblur="this.style.borderColor='#D4C4AE'; this.style.boxShadow='none';"
                        >{{ old('notes') }}</textarea>
                    </div>

                    {{-- ── TOMBOL SUBMIT --}}
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding-top: 1.25rem; border-top: 1px solid #F0E8DC;">
                        <a href="{{ route('manager.stock') }}" class="pods-btn-ghost">
                            Batal
                        </a>
                        <button type="submit" class="pods-btn-primary" id="btn-submit-request" style="min-width: 140px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Kirim Pengajuan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- INFORMASI ALUR PENGAJUAN --}}
        <div class="mgr-card mgr-animate" style="padding: 1.125rem 1.375rem; animation-delay: 0.12s;">
            <h3 style="font-size: 0.8125rem; font-weight: 700; color: var(--pods-espresso); margin-bottom: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="color: var(--pods-caramel);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Alur Pengajuan Restock
            </h3>
            <ol style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.75rem;" role="list">
                @foreach([
                    ['num' => '1', 'text' => 'Manager mengisi dan mengirim formulir pengajuan ini.'],
                    ['num' => '2', 'text' => 'Admin Pusat menerima notifikasi pengajuan masuk.'],
                    ['num' => '3', 'text' => 'Admin memilih Approve atau Reject disertai keterangan.'],
                    ['num' => '4', 'text' => 'Jika Approve, stok cabang otomatis bertambah dan tercatat di stock_log.'],
                ] as $step)
                <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
                    <span style="width: 22px; height: 22px; border-radius: 50%; background: rgba(200,129,59,0.15); border: 1.5px solid rgba(200,129,59,0.3); color: var(--pods-caramel); font-size: 0.6875rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">{{ $step['num'] }}</span>
                    <p style="font-size: 0.8125rem; color: var(--pods-espresso); font-weight: 400; line-height: 1.55;">{{ $step['text'] }}</p>
                </li>
                @endforeach
            </ol>
        </div>

    </div>

    {{-- ================================================================
         KOLOM KANAN: RIWAYAT PENGAJUAN
    ================================================================ --}}
    <div class="mgr-card mgr-animate" style="animation-delay: 0.18s; overflow: hidden; position: sticky; top: calc(64px + 2rem);">

        <div style="padding: 1.125rem 1.375rem 0.875rem; border-bottom: 1px solid #F0E8DC; background: var(--pods-espresso);">
            <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(245,233,211,0.5); margin-bottom: 0.125rem;">Log Aktivitas</p>
            <h2 class="font-serif" style="font-size: 1rem; font-weight: 700; color: #F5E9D3;">Riwayat Pengajuan</h2>
        </div>

        <ul style="list-style: none; margin: 0; padding: 0;" role="list" aria-label="Riwayat pengajuan restock">
            @forelse($requestHistory as $req)
            @php $hb = $historyBadge[$req['status']] ?? ['bg' => '#F3F4F6', 'text' => '#374151', 'dot' => '#9CA3AF', 'label' => $req['status'], 'pulse' => false]; @endphp
            <li style="padding: 1rem 1.375rem; {{ !$loop->last ? 'border-bottom: 1px solid #F0E8DC;' : '' }}">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.375rem;">
                    <div style="min-width: 0;">
                        <p style="font-size: 0.875rem; font-weight: 600; color: var(--pods-espresso); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $req['product'] }}</p>
                        <p style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300; font-variant-numeric: tabular-nums;">
                            {{ $req['id'] }} · {{ $req['requested_qty'] }} {{ $req['unit'] }} · {{ $req['date'] }}
                        </p>
                    </div>
                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 9px 2px 7px; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: {{ $hb['bg'] }}; color: {{ $hb['text'] }}; white-space: nowrap; flex-shrink: 0;">
                        <span style="width: 5px; height: 5px; border-radius: 9999px; background: {{ $hb['dot'] }}; {{ $hb['pulse'] ? 'animation: req-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;' : '' }}" aria-hidden="true"></span>
                        {{ $hb['label'] }}
                    </span>
                </div>
                @if($req['note'])
                <p style="font-size: 0.75rem; color: #DC2626; font-weight: 400; font-style: italic; margin-top: 0.25rem;">
                    Catatan admin: {{ $req['note'] }}
                </p>
                @endif
            </li>
            @empty
            <li style="padding: 2.5rem 1.375rem; text-align: center;">
                <p style="font-size: 0.875rem; color: var(--pods-muted); font-weight: 300;">Belum ada riwayat pengajuan.</p>
            </li>
            @endforelse
        </ul>

    </div>

</div>
</div>

@push('head-scripts')
<style>
    @keyframes req-pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.35; }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    /* build map produk dari option element untuk preview stok */
    const select     = document.getElementById('product_id');
    const preview    = document.getElementById('stock-preview');
    const previewQty = document.getElementById('preview-qty');
    const unitLabel  = document.getElementById('unit-label');

    function updateStockPreview(productId) {
        if (!productId || !select) return;

        const option = select.querySelector('option[value="' + productId + '"]');
        if (!option) { preview.style.display = 'none'; return; }

        const physical = option.dataset.physical;
        const unit     = option.dataset.unit || 'pcs';

        /* tampilkan preview stok fisik saat ini */
        previewQty.textContent    = physical + ' ' + unit;
        unitLabel.textContent     = unit;
        preview.style.display     = 'block';

        /* beri warna merah jika stok kritis (< 6) */
        previewQty.style.color = parseInt(physical) < 6 ? '#DC2626' : 'var(--pods-espresso)';
    }

    /* jalankan saat halaman load jika ada pre-selected product */
    if (select && select.value) {
        updateStockPreview(select.value);
    }

    /* jalankan saat select berubah */
    if (select) {
        select.addEventListener('change', function () {
            updateStockPreview(this.value);
        });
    }

    /* konfirmasi submit form */
    const form   = document.getElementById('restock-form');
    const btnSub = document.getElementById('btn-submit-request');
    if (form && btnSub) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const productSelect = document.getElementById('product_id');
            const qtyInput      = document.getElementById('requested_qty');

            /* validasi sederhana sisi klien sebelum konfirmasi modal */
            if (!productSelect || !productSelect.value) {
                productSelect && (productSelect.style.borderColor = '#DC2626');
                return;
            }
            if (!qtyInput || !qtyInput.value || parseInt(qtyInput.value) < 1) {
                qtyInput && (qtyInput.style.borderColor = '#DC2626');
                return;
            }

            const productName = productSelect.options[productSelect.selectedIndex].text.split(' (')[0];
            const qty         = qtyInput.value;
            const unit        = unitLabel ? unitLabel.textContent : 'pcs';

            /* swal modal: konfirmasi pengajuan sebelum submit (aksi dengan konsekuensi sistem) */
            window.SwalModal.fire({
                title:             'Kirim Pengajuan?',
                html:              '<p style="font-size:0.875rem; color:#6B7280;">Ajukan restock <strong>' + productName + '</strong> sebanyak <strong>' + qty + ' ' + unit + '</strong> ke Admin Pusat?</p>',
                icon:              'question',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText:  'Cek Lagi',
                reverseButtons:    true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
}());
</script>
@endpush

@endsection