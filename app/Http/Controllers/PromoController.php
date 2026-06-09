<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;


// PromoController - CRUD promo nasional & lokal + assign produk
class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::with(['branch', 'products'])
            ->orderByDesc('id_promos')
            ->paginate(10);

        $promos->getCollection()->transform(function ($promo) {
            return [
                'id'             => $promo->id_promos,
                'name'           => $promo->name,
                'branch'         => $promo->branch?->name ?? null,
                'branch_id'      => $promo->branch_id,
                'discount_type'  => $promo->discount_type,
                'discount_value' => (float) $promo->discount_value,
                'start_date'     => $promo->start_date->format('Y-m-d'),
                'end_date'       => $promo->end_date->format('Y-m-d'),
                'is_active'      => $promo->is_active,
                'products'       => $promo->products->pluck('name')->toArray(),
            ];
        });

        return view('admin.promos.promo', compact('promos'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get()->map(fn($b) => [
            'id' => $b->id_branches, 'name' => $b->name,
        ])->toArray();
        $allProducts = Product::with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        $productsByCategory = [];
        foreach ($allProducts as $p) {
            $catName = $p->category?->name ?? 'Lainnya';
            $productsByCategory[$catName][] = [
                'id'   => $p->id_products,
                'name' => $p->name,
            ];
        }

        return view('admin.promos.form', compact('branches', 'allProducts', 'productsByCategory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'scope'          => ['required', 'in:national,local'],
            'branch_id'      => ['nullable', 'required_if:scope,local', 'integer', 'exists:branches,id_branches'],
            'discount_type'  => ['required', 'in:percentage,nominal'],
            'discount_value' => ['required', 'numeric', 'min:1'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after_or_equal:start_date'],
            'product_ids'    => ['required', 'array', 'min:1'],
            'product_ids.*'  => ['integer', 'exists:products,id_products'],
        ]);

        $promo = Promo::create([
            'branch_id'      => $validated['scope'] === 'local' ? $validated['branch_id'] : null,
            'name'           => $validated['name'],
            'discount_type'  => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date'     => $validated['start_date'],
            'end_date'       => $validated['end_date'],
            'is_active'      => true,
        ]);

        $promo->products()->attach($validated['product_ids']);

        return redirect()->route('admin.promos.index')
            ->with('toast', 'Promo berhasil dibuat.');
    }

    public function edit($id)
    {
        $promoModel = Promo::with('products')->findOrFail($id);

        $branches = Branch::orderBy('name')->get()->map(fn($b) => [
            'id' => $b->id_branches, 'name' => $b->name,
        ])->toArray();

        $allProducts = Product::with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        $productsByCategory = [];
        foreach ($allProducts as $p) {
            $catName = $p->category?->name ?? 'Lainnya';
            $productsByCategory[$catName][] = [
                'id'   => $p->id_products,
                'name' => $p->name,
            ];
        }

        $selectedProducts = $promoModel->products->pluck('id_products')->toArray();

        $promo = [
            'id'             => $promoModel->id_promos,
            'name'           => $promoModel->name,
            'branch_id'      => $promoModel->branch_id,
            'discount_type'  => $promoModel->discount_type,
            'discount_value' => (float) $promoModel->discount_value,
            'start_date'     => $promoModel->start_date->format('Y-m-d'),
            'end_date'       => $promoModel->end_date->format('Y-m-d'),
            'is_active'      => $promoModel->is_active,
        ];

        return view('admin.promos.form', compact(
            'promo', 'branches', 'allProducts', 'productsByCategory', 'selectedProducts'
        ));
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'scope'          => ['required', 'in:national,local'],
            'branch_id'      => ['nullable', 'required_if:scope,local', 'integer', 'exists:branches,id_branches'],
            'discount_type'  => ['required', 'in:percentage,nominal'],
            'discount_value' => ['required', 'numeric', 'min:1'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after_or_equal:start_date'],
            'product_ids'    => ['required', 'array', 'min:1'],
            'product_ids.*'  => ['integer', 'exists:products,id_products'],
        ]);

        $promo->update([
            'branch_id'      => $validated['scope'] === 'local' ? $validated['branch_id'] : null,
            'name'           => $validated['name'],
            'discount_type'  => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date'     => $validated['start_date'],
            'end_date'       => $validated['end_date'],
        ]);

        $promo->products()->sync($validated['product_ids']);

        return redirect()->route('admin.promos.index')
            ->with('toast', 'Promo berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);

        DB::transaction(function () use ($promo) {
            // lepaskan referensi promo dari pesanan yang masih menggunakannya
            Order::where('promo_id', $promo->id_promos)
                ->update(['promo_id' => null]);

            $promo->products()->detach();
            $promo->delete();
        });

        return redirect()->route('admin.promos.index')
            ->with('toast', 'Promo berhasil dihapus.');
    }
}
