<h1>Order #{{ $order->id_orders }}</h1>

<form method="POST">
    @csrf
    <button type="submit">Saya Sudah Bayar</button>
</form>