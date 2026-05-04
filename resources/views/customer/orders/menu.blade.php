@foreach($products as $product)
    <p>{{ $product->name }}</p>

    <form method="POST" action="/order/cart/add">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id_products }}">
        <button type="submit">Tambah</button>
    </form>
@endforeach

<a href="/order/checkout">Checkout</a>