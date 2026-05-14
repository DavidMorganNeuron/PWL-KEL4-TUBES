@extends('layouts.admin')
@section('page')
<h2 class="text-xl font-bold mb-4">{{ isset($promo) ? 'Edit' : 'Create' }} Promo</h2>

<form action="{{ isset($promo) ? route('admin.promos.update', $promo->id_promos) : route('admin.promos.store') }}" 
      method="POST" class="max-w-lg bg-white p-4 border rounded space-y-4">
    
    @csrf
    @if(isset($promo)) @method('PUT') @endif

    <div>
        <label class="block text-sm mb-1">Promo Name</label>
        <input type="text" name="name" value="{{ $promo->name ?? '' }}" class="w-full border p-2 rounded text-sm" required>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm mb-1">Discount Type</label>
            <select name="discount_type" class="w-full border p-2 rounded text-sm" required>
                <option value="percentage" {{ isset($promo) && $promo->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                <option value="nominal" {{ isset($promo) && $promo->discount_type == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm mb-1">Discount Value</label>
            <input type="number" name="discount_value" value="{{ $promo->discount_value ?? '' }}" class="w-full border p-2 rounded text-sm" required>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm mb-1">Start Date</label>
            <input type="datetime-local" name="start_date" value="{{ $promo->start_date ?? '' }}" class="w-full border p-2 rounded text-sm" required>
        </div>
        <div>
            <label class="block text-sm mb-1">End Date</label>
            <input type="datetime-local" name="end_date" value="{{ $promo->end_date ?? '' }}" class="w-full border p-2 rounded text-sm" required>
        </div>
    </div>

    <!-- National vs Local Logic -->
    <div>
        <label class="block text-sm mb-1">Scope</label>
        <select name="is_national" id="scopeSelect" class="w-full border p-2 rounded text-sm" onchange="toggleBranch()">
            <option value="1" {{ isset($promo) && is_null($promo->branch_id) ? 'selected' : '' }}>National (All Branches)</option>
            <option value="0" {{ isset($promo) && !is_null($promo->branch_id) ? 'selected' : '' }}>Local (Specific Branch)</option>
        </select>
    </div>

    <div id="branchSelect" class="hidden">
        <label class="block text-sm mb-1">Select Branch</label>
        <select name="id_branches" class="w-full border p-2 rounded text-sm">
            @foreach($branches as $branch)
                <option value="{{ $branch->id_branches }}" {{ isset($promo) && $promo->branch_id == $branch->id_branches ? 'selected' : '' }}>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Pivot Table: promo_products -->
    <div>
        <label class="block text-sm mb-1">Apply to Products</label>
        <div class="border p-2 rounded max-h-40 overflow-y-auto space-y-1">
            @foreach($products as $product)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="products[]" value="{{ $product->id_products }}" 
                           @if(isset($promo) && in_array($product->id_products, $promo->products->pluck('id_products')->toArray()) checked @endif>
                    {{ $product->name }} (Rp {{ number_format($product->base_price) }})
                </label>
            @endforeach
        </div>
    </div>

    <button type="submit" class="bg-black text-white px-4 py-2 rounded text-sm">Save Promo</button>
</form>

<script>
function toggleBranch() {
    const scope = document.getElementById('scopeSelect').value;
    document.getElementById('branchSelect').classList.toggle('hidden', scope === '1');
}
// Trigger on load if editing local promo
window.onload = () => toggleBranch();
</script>
@endsection