<form method="POST" action="/order/checkout">
    @csrf

    <label>
        <input type="radio" name="payment_method" value="QRIS"> QRIS
    </label>

    <label>
        <input type="radio" name="payment_method" value="E_Wallet"> E-Wallet
    </label>

    <button type="submit">Bayar</button>
</form>