@extends('layout.app')

@section('content')
    <div style="padding: 20px; font-family: sans-serif;">
        <h1>Riwayat Pesanan</h1>
        <hr>

        {{-- mengecek pelanggan sudah pernah memesan atau belum --}}
        @if($orders->isEmpty())
            <p>Anda belum memiliki riwayat pesanan. Yuk, pesan kopi sekarang!</p>
            <a href="{{ route('orders.branch') }}">Mulai Pesan</a>
        @else
            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
                <tr style="background-color: #f2f2f2;">
                    <th>No. Pesanan</th>
                    <th>Tanggal</th>
                    <th>Total Belanja</th>
                    <th>Status</th>
                </tr>
                
                {{-- menampilkan seluruh data pesanan --}}
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d M Y - H:i') }} WIB</td>
                    <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    <td>
                        {{-- warna visual berdasarkan status pesanan --}}
                        @if($order->status === 'pending_payment')
                            <span style="color: orange;">Menunggu Pembayaran</span>
                        @elseif($order->status === 'paid')
                            <span style="color: blue;">Lunas (Siap Dimasak)</span>
                        @elseif($order->status === 'cooking')
                            <span style="color: purple;">Sedang Disiapkan</span>
                        @elseif($order->status === 'completed')
                            <span style="color: green;">Selesai</span>
                        @else
                            <span style="color: red;">Dibatalkan</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        @endif
    </div>
@endsection