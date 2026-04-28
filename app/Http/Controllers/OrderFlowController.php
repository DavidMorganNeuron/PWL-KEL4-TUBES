<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class OrderFlowController extends Controller
{
    /* =======================
       STEP 1: PILIH CABANG
    ======================= */
    public function branch() {
        $branches = Branch::all();
        return view('order.branch', compact('branches'));
    }

    public function setBranch(Request $request) {
        session(['branch_id' => $request->branch_id]);
        session()->forget('cart');

        return redirect('/order/menu');
    }

    /* =======================
       STEP 2: MENU + CART
    ======================= */
    public function menu() {
        if (!session('branch_id')) {
            return redirect('/order/branch');
        }

        $products = Product::where('is_available', true)->get();
        $cart = session('cart', []);

        return view('order.menu', compact('products', 'cart'));
    }

    public function addToCart(Request $request) {
        $cart = session('cart', []);

        $id = $request->product_id;
        $cart[$id] = ($cart[$id] ?? 0) + 1;

        session(['cart' => $cart]);

        return back();
    }

    /* =======================
       STEP 3: CHECKOUT
    ======================= */
    public function checkout() {
        if (!session('cart')) {
            return redirect('/order/menu');
        }

        return view('order.checkout');
    }

    /* =======================
       STEP 4: CREATE ORDER
    ======================= */
    public function storeOrder(Request $request) {

        $cart = session('cart');

        if (!$cart) {
            return redirect('/order/menu');
        }

        $subtotal = 0;

        foreach ($cart as $product_id => $qty) {
            $product = Product::find($product_id);

            if (!$product) continue;

            $subtotal += $product->base_price * $qty;
        }

        $order = Order::create([
            'branch_id' => session('branch_id'),
            'user_id' => 1,
            'order_number' => 'ORD-' . time(),
            'subtotal' => $subtotal,
            'total_discount' => 0,
            'grand_total' => $subtotal,
            'status' => 'pending_payment'
        ]);

        foreach ($cart as $product_id => $qty) {
            $product = Product::find($product_id);

            if (!$product) continue;

            OrderItem::create([
                'order_id' => $order->id_orders,
                'product_id' => $product_id,
                'qty' => $qty,
                'base_price' => $product->base_price,
                'discount_amount' => 0,
                'subtotal_price' => $product->base_price * $qty
            ]);
        }

        Payment::create([
            'order_id' => $order->id_orders,
            'method' => $request->payment_method,
            'status' => 'pending'
        ]);

        session()->forget('cart');

        return redirect('/payment/' . $order->id_orders);
    }
}
