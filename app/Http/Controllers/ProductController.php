<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;


// ProductController - CRUD produk + upload/ganti/hapus gambar dari Admin Pusat
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name')->get();
        return view('admin.catalogs.product', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.catalogs.form', compact('categories'));
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

        Product::create($validated);

        return redirect()->route('admin.catalogs.index')
            ->with('toast', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product   = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.catalogs.form', compact('product', 'categories'));
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
}
