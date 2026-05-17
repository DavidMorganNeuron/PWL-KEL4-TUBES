{{-- MANAGER REQUEST FORM: formulir pengajuan restock ke admin pusat --}}
@extends('manager.layouts.app')

@section('title', "Request Form — Pod's Manager")
@section('page-title', 'Ajukan Restock')

@section('content')

@php
    $historyBadge = [
        'pending'  => ['bg' => '#FEF3C7', 'text' => '#92400E', 'dot' => '#D97706', 'label' => 'Menunggu',  'pulse' => true],
        'approved' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#059669', 'label' => 'Disetujui', 'pulse' => false],
        'rejected' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#DC2626', 'label' => 'Ditolak',   'pulse' => false],
    ];
@endphp

<div style="padding: 2rem; background: #F0E8DC; min-height: calc(100vh - 64px);">
<div style="display: grid; grid-template-columns: 1fr 380px; gap: 1.25rem; align-items: flex-start;">

    {{-- ================================================================
         FORMULIR PENGAJUAN
    ================================================================ --}}
    <div>

        <div class="mgr-card mgr-animate" style="overflow: hidden; margin-bottom: 1.25rem;">

            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F0E8DC; background: var(--pods-espresso);">
                <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(245,233,211,0.5); margin-bottom: 0.25rem;">Request ke Admin Pusat</p>
                <h2 class="font-serif" style="font-size: 1.0625rem; font-weight: 700; color: #F5E9D3;">Formulir Pengajuan Restock</h2>
            </div>

            <div style="padding: 1.75rem 1.5rem;">

                <form method="POST" action="{{ route('manager.request_form.store') }}" id="restock-form" novalidate>
                    @csrf

                    {{-- PILIH PRODUK --}}
                    <div style="margin-bottom: 1.5rem;">
                        <label for="product_id" style="display: block; font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); margin-bottom: 0.5rem;">
                            Produk
                            <span style="color: #DC2626;" aria-hidden="true">*</span>
                        </label>
                        <select
                            name="product_id"
                            id="product_id"
                            required
                            style="width: 100%; padding: 0.6875rem 2.5rem 0.6875rem 1rem; border: 1.5px solid {{ $errors->has('product_id') ? '#DC2626' : '#D4C4AE' }}; border-radius: 10px; background: #FFFDF9; color: var(--pods-espresso); font-family: var(--font-sans); font-size: 0.9375rem; transition: border-color 0.15s, box-shadow 0.15s; appearance: none; background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23A08060' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 1rem center; cursor: pointer; {{ $errors->has('product_id') ? 'box-shadow: 0 0 0 3px rgba(220,38,38,0.12);' : '' }}"
                            onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                            onblur="this.style.borderColor='{{ $errors->has('product_id') ? '#DC2626' : '#D4C4AE' }}'; this.style.boxShadow='none';"
                            onchange="updateStockPreview(this.value)"
                        >
                            <option value="" disabled {{ old('product_id', $preselectedId) ? '' : 'selected' }}>-- Pilih produk --</option>

                            @php $currentCat = null; @endphp
                            @foreach($products as $product)
                                {{-- grouping manual per kategori via optgroup --}}
                                @if($currentCat !== $product->category_name)
                                    @if($currentCat !== null) </optgroup> @endif
                                    <optgroup label="{{ $product->category_name }}">
                                    @php $currentCat = $product->category_name; @endphp
                                @endif
                                <option
                                    value="{{ $product->id_products }}"
                                    data-physical="{{ $product->physical_qty }}"
                                    {{ old('product_id', $preselectedId) == $product->id_products ? 'selected' : '' }}
                                >
                                    {{ $product->product_name }} (Stok: {{ $product->physical_qty }})
                                </option>
                            @endforeach
                            @if($currentCat !== null) </optgroup> @endif
                        </select>

                        {{-- inline error validasi server-side --}}
                        @error('product_id')
                        <p style="font-size: 0.8125rem; color: #DC2626; margin-top: 0.375rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PREVIEW STOK SAAT INI --}}
                    <div id="stock-preview" style="display: {{ old('product_id', $preselectedId) ? 'block' : 'none' }}; margin-bottom: 1.5rem; padding: 0.875rem 1rem; background: #FBF6EE; border: 1px solid #EDE0CC; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.8125rem; font-weight: 500; color: var(--pods-espresso);">Stok fisik saat ini:</span>
                            <span id="preview-qty" style="font-size: 1rem; font-weight: 700; color: var(--pods-espresso); font-variant-numeric: tabular-nums;"></span>
                        </div>
                    </div>

                    {{-- JUMLAH RESTOCK --}}
                    <div style="margin-bottom: 1.5rem;">
                        <label for="requested_qty" style="display: block; font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); margin-bottom: 0.5rem;">
                            Jumlah Restock yang Diminta
                            <span style="color: #DC2626;" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="number"
                            name="requested_qty"
                            id="requested_qty"
                            min="1"
                            max="9999"
                            placeholder="Masukkan jumlah..."
                            required
                            value="{{ old('requested_qty') }}"
                            style="width: 100%; padding: 0.6875rem 1rem; border: 1.5px solid {{ $errors->has('requested_qty') ? '#DC2626' : '#D4C4AE' }}; border-radius: 10px; background: #FFFDF9; color: var(--pods-espresso); font-family: var(--font-sans); font-size: 0.9375rem; transition: border-color 0.15s, box-shadow 0.15s; {{ $errors->has('requested_qty') ? 'box-shadow: 0 0 0 3px rgba(220,38,38,0.12);' : '' }}"
                            onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                            onblur="this.style.borderColor='{{ $errors->has('requested_qty') ? '#DC2626' : '#D4C4AE' }}'; this.style.boxShadow='none';"
                        >
                        @error('requested_qty')
                        <p style="font-size: 0.8125rem; color: #DC2626; margin-top: 0.375rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CATATAN (opsional) --}}
                    <div style="margin-bottom: 2rem;">
                        <label for="notes" style="display: block; font-size: 0.8125rem; font-weight: 600; color: var(--pods-espresso); margin-bottom: 0.5rem;">
                            Catatan Tambahan
                            <span style="font-size: 0.75rem; font-weight: 300; color: var(--pods-muted);">(opsional)</span>
                        </label>
                        <textarea
                            name="notes"
                            id="notes"
                            rows="3"
                            placeholder="Tambahkan alasan atau keterangan tambahan untuk admin..."
                            style="width: 100%; padding: 0.6875rem 1rem; border: 1.5px solid #D4C4AE; border-radius: 10px; background: #FFFDF9; color: var(--pods-espresso); font-family: var(--font-sans); font-size: 0.9375rem; resize: vertical; transition: border-color 0.15s, box-shadow 0.15s; line-height: 1.55;"
                            onfocus="this.style.borderColor='#C8813B'; this.style.boxShadow='0 0 0 3px rgba(200,129,59,0.18)';"
                            onblur="this.style.borderColor='#D4C4AE'; this.style.boxShadow='none';"
                        >{{ old('notes') }}</textarea>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding-top: 1.25rem; border-top: 1px solid #F0E8DC;">
                        <a href="{{ route('manager.stock') }}" class="pods-btn-ghost">Batal</a>
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

    </div>

    {{-- ================================================================
         RIWAYAT PENGAJUAN
    ================================================================ --}}
    <div class="mgr-card mgr-animate" style="animation-delay: 0.18s; overflow: hidden; position: sticky; top: calc(64px + 2rem);">

        <div style="padding: 1.125rem 1.375rem 0.875rem; border-bottom: 1px solid #F0E8DC; background: var(--pods-espresso);">
            <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(245,233,211,0.5); margin-bottom: 0.125rem;">Log Aktivitas</p>
            <h2 class="font-serif" style="font-size: 1rem; font-weight: 700; color: #F5E9D3;">Riwayat Pengajuan</h2>
        </div>

        <ul style="list-style: none; margin: 0; padding: 0;" role="list" aria-label="Riwayat pengajuan restock">
            @forelse($requestHistory as $req)
            @php $hb = $historyBadge[$req->status] ?? ['bg' => '#F3F4F6', 'text' => '#374151', 'dot' => '#9CA3AF', 'label' => $req->status, 'pulse' => false]; @endphp
            <li style="padding: 1rem 1.375rem; {{ !$loop->last ? 'border-bottom: 1px solid #F0E8DC;' : '' }}">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.375rem;">
                    <div style="min-width: 0;">
                        {{-- $req->product->name dari relasi Eloquent RequestLog->product() --}}
                        <p style="font-size: 0.875rem; font-weight: 600; color: var(--pods-espresso); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $req->product->name ?? '—' }}
                        </p>
                        <p style="font-size: 0.75rem; color: var(--pods-muted); font-weight: 300; font-variant-numeric: tabular-nums;">
                            #{{ $req->id_request_log }} · {{ $req->requested_qty }} unit · {{ $req->created_at->format('d M Y') }}
                        </p>
                    </div>
                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 9px 2px 7px; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: {{ $hb['bg'] }}; color: {{ $hb['text'] }}; white-space: nowrap; flex-shrink: 0;">
                        <span style="width: 5px; height: 5px; border-radius: 9999px; background: {{ $hb['dot'] }}; {{ $hb['pulse'] ? 'animation: req-pulse 1.5s cubic-bezier(0.4,0,0.6,1) infinite;' : '' }}" aria-hidden="true"></span>
                        {{ $hb['label'] }}
                    </span>
                </div>
                {{-- catatan admin dari kolom notes (ditambah via migration patch) --}}
                @if($req->notes && $req->status === 'rejected')
                <p style="font-size: 0.75rem; color: #DC2626; font-weight: 400; font-style: italic; margin-top: 0.25rem;">
                    Catatan admin: {{ $req->notes }}
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
    const select     = document.getElementById('product_id');
    const preview    = document.getElementById('stock-preview');
    const previewQty = document.getElementById('preview-qty');

    function updateStockPreview(productId) {
        if (!productId || !select) return;
        const option = select.querySelector('option[value="' + productId + '"]');
        if (!option) { preview.style.display = 'none'; return; }

        const physical = option.dataset.physical;
        previewQty.textContent = physical + ' unit';
        preview.style.display  = 'block';
        previewQty.style.color = parseInt(physical) < 10 ? '#DC2626' : 'var(--pods-espresso)';
    }

    if (select && select.value) updateStockPreview(select.value);

    if (select) {
        select.addEventListener('change', function () {
            updateStockPreview(this.value);
        });
    }

    const form   = document.getElementById('restock-form');
    const btnSub = document.getElementById('btn-submit-request');
    if (form && btnSub) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const productSelect = document.getElementById('product_id');
            const qtyInput      = document.getElementById('requested_qty');

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

            window.SwalModal.fire({
                title:             'Kirim Pengajuan?',
                html:              '<p style="font-size:0.875rem;color:#6B7280;">Ajukan restock <strong>' + productName + '</strong> sebanyak <strong>' + qty + ' unit</strong> ke Admin Pusat?</p>',
                icon:              'question',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText:  'Cek Lagi',
                reverseButtons:    true,
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    }
}());
</script>
@endpush

@endsection