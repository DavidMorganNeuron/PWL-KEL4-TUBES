<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;


// ProductController - CRUD produk + upload/ganti/hapus gambar dari Admin Pusat
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name')->paginate(10);
        $categories = Category::orderBy('name')->get();
        return view('admin.catalogs.product', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $branches   = Branch::orderBy('name')->get();
        return view('admin.catalogs.form', compact('categories', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id_categories'],
            'base_price'  => ['required', 'numeric', 'min:1000', 'max:999999'],
            'is_available'=> ['nullable', 'in:0,1'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        // sisip stok awal ke seluruh tabel stok cabang
        $branches = Branch::orderBy('name')->get();
        foreach ($branches as $branch) {
            $stockField = 'stock_' . $branch->id_branches;
            $qty = max(0, (int) $request->input($stockField, 0));

            DB::table($this->resolveStockTable($branch->name))->insert([
                'product_id'   => $product->id_products,
                'physical_qty' => $qty,
                'reserved_qty' => 0,
            ]);
        }

        return redirect()->route('admin.catalogs.index')
            ->with('toast', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product   = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $branches   = Branch::orderBy('name')->get();
        return view('admin.catalogs.form', compact('product', 'categories', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id_categories'],
            'base_price'  => ['required', 'numeric', 'min:1000', 'max:999999'],
            'is_available'=> ['nullable', 'in:0,1'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            if ($product->getRawOriginal('image_url')) {
                Storage::disk('public')->delete($product->getRawOriginal('image_url'));
            }

            $validated['image_url'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.catalogs.index')
            ->with('toast', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->getRawOriginal('image_url')) {
            Storage::disk('public')->delete($product->getRawOriginal('image_url'));
        }

        // nullify FK request_id di stock_log sebelum hapus request_log
        $requestIds = DB::table('request_log')
            ->where('product_id', $id)
            ->pluck('id_request_log');

        if ($requestIds->isNotEmpty()) {
            DB::table('stock_log')
                ->whereIn('request_id', $requestIds)
                ->update(['request_id' => null]);
        }

        // hapus relasi agar tidak terhalang FK constraint
        DB::table('order_items')->where('product_id', $id)->delete();
        DB::table('stock_log')->where('product_id', $id)->delete();
        DB::table('request_log')->where('product_id', $id)->delete();
        DB::table('promo_products')->where('product_id', $id)->delete();

        // hapus dari semua tabel stok cabang (cascadeOnDelete, tapi amankan manual)
        $branches = Branch::orderBy('name')->get();
        foreach ($branches as $branch) {
            $table = $this->resolveStockTable($branch->name);
            DB::table($table)->where('product_id', $id)->delete();
        }

        $product->delete();

        return redirect()->route('admin.catalogs.index')
            ->with('toast', 'Produk berhasil dihapus.');
    }

    public function toggleAvailability($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_available' => !$product->is_available]);

        $status = $product->is_available ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.catalogs.index')
            ->with('toast', "Produk {$product->name} berhasil {$status}.");
    }

    // menentukan nama tabel stok dari nama cabang
    private function resolveStockTable(string $branchName): string
    {
        $normalized = strtolower(preg_replace('/[\s.]+/', '_', trim($branchName)));
        $normalized = preg_replace('/[^a-z0-9_]/', '', $normalized);
        return 'stock_branch_' . $normalized;
    }
}
