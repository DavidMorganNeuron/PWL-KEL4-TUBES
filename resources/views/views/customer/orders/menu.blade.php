@extends('customer.layouts.app')

@section('title', "Menu — Pod's")

@section('content')

<div style="min-height: 100vh; background: #FBF6EE;">

    {{-- ================================================================
         PAGE HEADER
    ================================================================ --}}
    <div style="background: #3D1F0F; padding: 2.25rem 0 2rem; border-bottom: 1px solid rgba(245,233,211,0.07);">
        <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">
            <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: #C8813B; margin: 1.75rem 0 0.375rem;">
                Langkah 2 dari 4
            </p>
            <h1 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 900; color: #F5E9D3; margin-bottom: 0.375rem;">
                Pilih Menu
            </h1>
            <p style="font-size: 0.875rem; color: rgba(245,233,211,0.5); font-weight: 300;">
                Tambahkan item ke keranjang, lalu lanjut ke checkout.
            </p>
        </div>
    </div>

    {{-- ================================================================
         MAIN LAYOUT: 2 kolom statis (katalog kiri + cart kanan)
    ================================================================ --}}
    <div style="width: 1280px; margin: 0 auto; padding: 2.5rem 2.5rem 4rem; display: flex; gap: 1.75rem; align-items: flex-start;">

        {{-- ==========================================
             KOLOM KIRI: Katalog Produk
        ========================================== --}}
        <div style="flex: 1; min-width: 0;">

            @php
                /* kelompokkan produk berdasarkan nama kategori untuk navigasi visual */
                $grouped = $products->groupBy(fn($p) => $p->category->name ?? 'Lainnya');
            @endphp

            @forelse($grouped as $categoryName => $items)
            <div style="margin-bottom: 2.5rem;">

                {{-- judul kategori --}}
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.125rem;">
                    <h2 style="font-family: var(--font-serif); font-size: 1.125rem; font-weight: 700; color: #1C0F0A; white-space: nowrap;">
                        {{ $categoryName }}
                    </h2>
                    <div style="flex: 1; height: 1px; background: #EDE0CC;"></div>
                    <span style="font-size: 0.75rem; color: #A08060; font-weight: 400; white-space: nowrap;">
                        {{ $items->count() }} item
                    </span>
                </div>

                {{-- grid produk --}}
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                    @foreach($items as $product)
                    @php
                        $qtyInCart = $cart[(string)$product->id_products] ?? 0;
                    @endphp
                    <article
                        style="
                            background: #FFFFFF;
                            border-radius: 1rem;
                            border: 1px solid #EDE0CC;
                            overflow: hidden;
                            box-shadow: 0 1px 3px rgba(28,15,10,0.05);
                            transition: box-shadow 0.2s, transform 0.2s;
                            position: relative;
                        "
                        onmouseover="this.style.boxShadow='0 6px 20px rgba(28,15,10,0.1)'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.boxShadow='0 1px 3px rgba(28,15,10,0.05)'; this.style.transform='translateY(0)';"
                    >
                        {{-- badge qty di keranjang --}}
                        @if($qtyInCart > 0)
                        <div style="
                            position: absolute; top: 0.625rem; right: 0.625rem; z-index: 2;
                            width: 22px; height: 22px;
                            border-radius: 9999px;
                            background: #C8813B;
                            display: flex; align-items: center; justify-content: center;
                            font-size: 0.6875rem; font-weight: 700; color: #1C0F0A;
                        " aria-label="{{ $qtyInCart }} item di keranjang">
                            {{ $qtyInCart }}
                        </div>
                        @endif

                        {{-- thumbnail --}}
                        <div style="height: 130px; background: linear-gradient(135deg, rgba(61,31,15,0.08) 0%, rgba(200,129,59,0.16) 100%); display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                            @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                            @else
                            <span style="font-size: 2.5rem; opacity: 0.3;">☕</span>
                            @endif
                        </div>

                        <div style="padding: 0.875rem 1rem 1rem;">
                            <h3 style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $product->name }}
                            </h3>
                            <p style="font-size: 0.875rem; font-weight: 600; color: #3D1F0F; margin-bottom: 0.875rem;">
                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                            </p>

                            {{-- tombol tambah ke cart --}}
                            <form method="POST" action="/order/cart/add">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id_products }}">
                                <button
                                    type="submit"
                                    style="
                                        width: 100%;
                                        padding: 0.5rem 0;
                                        border-radius: 0.625rem;
                                        border: 1.5px solid {{ $qtyInCart > 0 ? '#C8813B' : '#EDE0CC' }};
                                        background: {{ $qtyInCart > 0 ? 'rgba(200,129,59,0.1)' : 'transparent' }};
                                        color: {{ $qtyInCart > 0 ? '#C8813B' : '#A08060' }};
                                        font-size: 0.8125rem; font-weight: 600;
                                        cursor: pointer;
                                        transition: all 0.15s;
                                        display: flex; align-items: center; justify-content: center; gap: 0.375rem;
                                    "
                                    onmouseover="this.style.borderColor='#C8813B'; this.style.background='rgba(200,129,59,0.1)'; this.style.color='#C8813B';"
                                    onmouseout="this.style.borderColor='{{ $qtyInCart > 0 ? '#C8813B' : '#EDE0CC' }}'; this.style.background='{{ $qtyInCart > 0 ? 'rgba(200,129,59,0.1)' : 'transparent' }}'; this.style.color='{{ $qtyInCart > 0 ? '#C8813B' : '#A08060' }}';"
                                    aria-label="Tambahkan {{ $product->name }} ke keranjang"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah
                                </button>
                            </form>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 5rem 2rem; background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC;">
                <div style="font-size: 3rem; margin-bottom: 1rem;" aria-hidden="true">☕</div>
                <p style="font-size: 1rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.5rem;">Menu Belum Tersedia</p>
                <p style="font-size: 0.875rem; color: #A08060; font-weight: 300;">Silakan coba beberapa saat lagi.</p>
            </div>
            @endforelse

        </div>

        {{-- ==========================================
             KOLOM KANAN: Cart Panel
        ========================================== --}}
        <aside
            style="
                width: 320px;
                flex-shrink: 0;
                position: sticky;
                top: calc(72px + 1.5rem);
                display: flex;
                flex-direction: column;
                gap: 1rem;
            "
            aria-label="Ringkasan keranjang belanja"
        >
            <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 2px 12px rgba(28,15,10,0.07); overflow: hidden;">

                {{-- header cart --}}
                <div style="background: #1C0F0A; padding: 1rem 1.375rem; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h2 style="font-size: 0.9375rem; font-weight: 600; color: #F5E9D3;">Keranjang</h2>
                    </div>
                    @php $totalItems = array_sum($cart); @endphp
                    @if($totalItems > 0)
                    <span style="font-size: 0.75rem; font-weight: 600; background: #C8813B; color: #1C0F0A; padding: 0.15rem 0.6rem; border-radius: 9999px;">
                        {{ $totalItems }} item
                    </span>
                    @endif
                </div>

                @if(empty($cart))
                {{-- cart kosong --}}
                <div style="padding: 2.5rem 1.375rem; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.3;" aria-hidden="true">🛒</div>
                    <p style="font-size: 0.875rem; color: #A08060; font-weight: 300;">Keranjang masih kosong.<br>Tambahkan menu favoritmu!</p>
                </div>
                @else
                {{-- daftar item dalam cart --}}
                <div style="padding: 0.875rem 1.375rem; max-height: 340px; overflow-y: auto;">
                    @php $grandTotal = 0; @endphp
                    @foreach($cart as $productId => $qty)
                    @php
                        $p = $products->firstWhere('id_products', $productId);
                        if (!$p) continue;
                        $lineTotal  = $p->base_price * $qty;
                        $grandTotal += $lineTotal;
                    @endphp
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #EDE0CC;">
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 500; color: #1C0F0A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.2rem;">
                                {{ $p->name }}
                            </p>
                            <p style="font-size: 0.75rem; color: #A08060;">
                                Rp {{ number_format($p->base_price, 0, ',', '.') }} × {{ $qty }}
                            </p>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0;">
                            <span style="font-size: 0.875rem; font-weight: 600; color: #3D1F0F;">
                                Rp {{ number_format($lineTotal, 0, ',', '.') }}
                            </span>
                            {{-- tombol kurangi qty --}}
                            <form method="POST" action="/order/cart/remove" style="display: inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productId }}">
                                <button
                                    type="submit"
                                    style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 9999px; border: 1px solid rgba(239,68,68,0.25); background: transparent; color: rgba(220,38,38,0.6); cursor: pointer; font-size: 0.9rem; line-height: 1; transition: all 0.15s;"
                                    onmouseover="this.style.background='rgba(239,68,68,0.08)'; this.style.borderColor='rgba(239,68,68,0.45)';"
                                    onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(239,68,68,0.25)';"
                                    aria-label="Kurangi {{ $p->name }}"
                                >−</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- subtotal + tombol checkout --}}
                <div style="padding: 1rem 1.375rem; border-top: 1px solid #EDE0CC; background: #FBF6EE;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span style="font-size: 0.875rem; color: #A08060;">Total</span>
                        <span style="font-family: var(--font-serif); font-size: 1.125rem; font-weight: 900; color: #1C0F0A;">
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </span>
                    </div>
                    <a
                        href="{{ route('orders.checkout') }}"
                        style="
                            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
                            padding: 0.75rem;
                            border-radius: 0.75rem;
                            background: #C8813B;
                            color: #1C0F0A;
                            font-size: 0.9375rem; font-weight: 600; letter-spacing: 0.04em;
                            text-decoration: none;
                            box-shadow: 0 4px 14px rgba(200,129,59,0.3);
                            transition: background 0.2s, transform 0.1s;
                        "
                        onmouseover="this.style.background='#D99045'"
                        onmouseout="this.style.background='#C8813B'"
                        onmousedown="this.style.transform='scale(0.98)'"
                        onmouseup="this.style.transform='scale(1)'"
                    >
                        Lanjut ke Checkout
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                @endif

            </div>

            {{-- navigasi: kembali ke pilih cabang --}}
            <a
                href="{{ route('orders.branch') }}"
                style="display: flex; align-items: center; justify-content: center; gap: 0.375rem; padding: 0.75rem; border-radius: 0.75rem; border: 1px solid #EDE0CC; background: #FFFFFF; color: #A08060; font-size: 0.8125rem; font-weight: 500; text-decoration: none; transition: all 0.2s;"
                onmouseover="this.style.borderColor='#C8813B'; this.style.color='#C8813B';"
                onmouseout="this.style.borderColor='#EDE0CC'; this.style.color='#A08060';"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Ganti Cabang
            </a>
        </aside>

    </div>
</div>

@endsection