{{-- Cabang Form: create & edit cabang --}}
@extends('admin.layouts.app')

@section('title', (isset($branch) ? 'Edit' : 'Tambah') . " Cabang — Pod's Admin")
@section('page-title', isset($branch) ? 'Edit Cabang' : 'Tambah Cabang Baru')

@section('content')

@php
$isEdit = isset($branch);
$branch = $branch ?? null;
@endphp

<div style="padding:2rem; background:#F0E8DC; min-height:calc(100vh - 64px);">

    <form method="POST" action="{{ $isEdit ? route('admin.branches.update', $branch->id_branches) : route('admin.branches.store') }}" style="max-width:900px;">
        @csrf
        @if($isEdit) @method('PUT') @endif

        {{-- INFORMASI CABANG --}}
        <div class="adm-card" style="padding:1.5rem; margin-bottom:1.25rem;">
            <h2 class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-espresso); margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #EDE0CC;">
                Informasi Cabang
            </h2>

            @if($isEdit)
            {{-- Nama cabang (read-only saat edit) --}}
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">Nama Cabang</label>
                <input type="text" value="{{ $branch->name }}" disabled
                    style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1.5px solid #EDE0CC; background:#F5F0E8; font-size:0.875rem; color:var(--pods-muted); cursor:not-allowed;">
                <p style="font-size:0.75rem; color:var(--pods-muted); margin-top:0.375rem;">Nama cabang tidak dapat diubah setelah dibuat.</p>
            </div>
            @else
            {{-- Nama cabang --}}
            <div style="margin-bottom:1rem;">
                <label for="name" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">Nama Cabang <span style="color:#DC2626;">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100"
                    style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1.5px solid {{ $errors->has('name') ? '#DC2626' : '#EDE0CC' }}; font-size:0.875rem; color:var(--pods-espresso); background:#FFFFFF; transition:border-color 0.15s;">
                @error('name')
                <p style="font-size:0.75rem; color:#DC2626; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- Alamat --}}
            <div style="margin-bottom:1rem;">
                <label for="address" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">Alamat <span style="color:#DC2626;">*</span></label>
                <textarea id="address" name="address" required maxlength="255" rows="2"
                    style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1.5px solid {{ $errors->has('address') ? '#DC2626' : '#EDE0CC' }}; font-size:0.875rem; color:var(--pods-espresso); background:#FFFFFF; font-family:var(--font-sans); resize:vertical; transition:border-color 0.15s;">{{ old('address', $isEdit ? $branch->address : '') }}</textarea>
                @error('address')
                <p style="font-size:0.75rem; color:#DC2626; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jam operasional --}}
            <div style="margin-bottom:0.75rem;">
                <label style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.5rem;">Jam Operasional</label>
                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                    <input type="hidden" name="is_always_open" value="0">
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                        <input type="checkbox" name="is_always_open" value="1" {{ old('is_always_open', $isEdit ? $branch->is_always_open : false) ? 'checked' : '' }} onchange="toggleJam(this.checked)"
                            style="width:16px; height:16px; accent-color:#C8813B;">
                        <span style="font-size:0.8125rem; color:var(--pods-espresso);">Buka 24 Jam</span>
                    </label>
                    <div id="jam-wrapper" style="display:flex; align-items:center; gap:0.75rem; {{ old('is_always_open', $isEdit ? $branch->is_always_open : false) ? 'opacity:0.4;pointer-events:none;' : '' }}">
                        <div>
                            <label for="open_time" style="font-size:0.75rem; color:var(--pods-muted); display:block; margin-bottom:0.25rem;">Buka</label>
                            <input type="time" id="open_time" name="open_time" value="{{ old('open_time', $isEdit && $branch->open_time ? \Carbon\Carbon::parse($branch->open_time)->format('H:i') : '') }}"
                                style="padding:0.5rem 0.75rem; border-radius:8px; border:1.5px solid {{ $errors->has('open_time') ? '#DC2626' : '#EDE0CC' }}; font-size:0.8125rem;">
                            @error('open_time')
                            <p style="font-size:0.7rem; color:#DC2626; margin-top:0.2rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <span style="color:var(--pods-muted); margin-top:1.25rem;">—</span>
                        <div>
                            <label for="close_time" style="font-size:0.75rem; color:var(--pods-muted); display:block; margin-bottom:0.25rem;">Tutup</label>
                            <input type="time" id="close_time" name="close_time" value="{{ old('close_time', $isEdit && $branch->close_time ? \Carbon\Carbon::parse($branch->close_time)->format('H:i') : '') }}"
                                style="padding:0.5rem 0.75rem; border-radius:8px; border:1.5px solid {{ $errors->has('close_time') ? '#DC2626' : '#EDE0CC' }}; font-size:0.8125rem;">
                            @error('close_time')
                            <p style="font-size:0.7rem; color:#DC2626; margin-top:0.2rem;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                @error('is_always_open')
                <p style="font-size:0.75rem; color:#DC2626; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            @if($isEdit)
            {{-- Status aktif (hanya edit mode) --}}
            <div style="margin-bottom:0.5rem;">
                <label style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">Status Cabang</label>
                <input type="hidden" name="is_active" value="0">
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}
                        style="width:16px; height:16px; accent-color:#C8813B;">
                    <span style="font-size:0.8125rem; color:var(--pods-espresso);">Cabang Aktif</span>
                </label>
                @error('is_active')
                <p style="font-size:0.75rem; color:#DC2626; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
            </div>
            @endif
        </div>

        @if(!$isEdit)
        {{-- INFORMASI MANAGER (CREATE ONLY) --}}
        <div class="adm-card" style="padding:1.5rem; margin-bottom:1.25rem;">
            <h2 class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-espresso); margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #EDE0CC;">
                Informasi Manager
            </h2>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label for="manager_name" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">Nama Manager <span style="color:#DC2626;">*</span></label>
                    <input type="text" id="manager_name" name="manager_name" value="{{ old('manager_name') }}" required maxlength="150"
                        style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1.5px solid {{ $errors->has('manager_name') ? '#DC2626' : '#EDE0CC' }}; font-size:0.875rem;">
                    @error('manager_name')
                    <p style="font-size:0.75rem; color:#DC2626; margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="manager_email" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">Email Manager <span style="color:#DC2626;">*</span></label>
                    <input type="email" id="manager_email" name="manager_email" value="{{ old('manager_email') }}" required
                        style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1.5px solid {{ $errors->has('manager_email') ? '#DC2626' : '#EDE0CC' }}; font-size:0.875rem;">
                    @error('manager_email')
                    <p style="font-size:0.75rem; color:#DC2626; margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="manager_password" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">Password <span style="color:#DC2626;">*</span></label>
                    <input type="password" id="manager_password" name="manager_password" required minlength="8"
                        style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1.5px solid {{ $errors->has('manager_password') ? '#DC2626' : '#EDE0CC' }}; font-size:0.875rem;">
                    @error('manager_password')
                    <p style="font-size:0.75rem; color:#DC2626; margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="manager_password_confirmation" style="display:block; font-size:0.8125rem; font-weight:600; color:var(--pods-espresso); margin-bottom:0.375rem;">Konfirmasi Password <span style="color:#DC2626;">*</span></label>
                    <input type="password" id="manager_password_confirmation" name="manager_password_confirmation" required
                        style="width:100%; padding:0.625rem 0.875rem; border-radius:8px; border:1.5px solid #EDE0CC; font-size:0.875rem;">
                </div>
            </div>
        </div>

        {{-- STOK AWAL (CREATE ONLY) --}}
        <div class="adm-card" style="padding:1.5rem; margin-bottom:1.25rem;">
            <h2 class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-espresso); margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #EDE0CC;">
                Stok Awal
            </h2>
            <p style="font-size:0.8125rem; color:var(--pods-muted); margin-bottom:1rem;">Atur jumlah stok awal untuk setiap produk di cabang baru.</p>

            <table style="width:100%; border-collapse:collapse; font-size:0.8125rem;">
                <thead>
                    <tr style="background:#F5F0E8; text-align:left;">
                        <th style="padding:0.625rem 0.75rem; font-weight:600; color:var(--pods-espresso);">Produk</th>
                        <th style="padding:0.625rem 0.75rem; font-weight:600; color:var(--pods-espresso);">Kategori</th>
                        <th style="padding:0.625rem 0.75rem; font-weight:600; color:var(--pods-espresso); text-align:center;">Qty Awal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr style="border-bottom:1px solid #EDE0CC;">
                        <td style="padding:0.5rem 0.75rem; font-weight:500; color:var(--pods-espresso);">{{ $p->name }}</td>
                        <td style="padding:0.5rem 0.75rem; color:var(--pods-muted); font-size:0.75rem;">{{ $p->category->name }}</td>
                        <td style="padding:0.5rem 0.75rem; text-align:center;">
                            <input type="number" name="initial_stocks[{{ $p->id_products }}]" value="{{ old('initial_stocks.' . $p->id_products, 1) }}" min="1"
                                style="width:80px; padding:0.375rem 0.5rem; border-radius:6px; border:1.5px solid {{ $errors->has('initial_stocks.' . $p->id_products) ? '#DC2626' : '#EDE0CC' }}; font-size:0.8125rem; text-align:center;">
                            @error('initial_stocks.' . $p->id_products)
                            <p style="font-size:0.7rem; color:#DC2626; margin-top:0.2rem;">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @error('initial_stocks')
            <p style="font-size:0.75rem; color:#DC2626; margin-top:0.5rem;">{{ $message }}</p>
            @enderror
        </div>
        @endif

        {{-- ERROR GLOBAL --}}
        @error('error')
        <div style="padding:0.875rem 1rem; background:#FEE2E2; border:1px solid #FECACA; border-radius:8px; color:#991B1B; font-size:0.8125rem; margin-bottom:1.25rem;">
            {{ $message }}
        </div>
        @enderror

        {{-- TOMBOL --}}
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <button type="submit" class="pods-btn-primary">
                {{ $isEdit ? 'Simpan Perubahan' : 'Buat Cabang' }}
            </button>
            <a href="{{ route('admin.branches.index') }}" class="pods-btn-ghost">Batal</a>
        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
function toggleJam(checked) {
    var wrapper = document.getElementById('jam-wrapper');
    var inputs = wrapper.querySelectorAll('input');
    if (checked) {
        wrapper.style.opacity = '0.4';
        wrapper.style.pointerEvents = 'none';
        inputs.forEach(function (el) { el.disabled = true; });
    } else {
        wrapper.style.opacity = '1';
        wrapper.style.pointerEvents = 'auto';
        inputs.forEach(function (el) { el.disabled = false; });
    }
}
</script>
@endpush
