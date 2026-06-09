@extends('customer.layouts.app')

@section('title', "Pilih Menu — Pod's")

@section('content')

<div style="min-height: 100vh; background: #FBF6EE;">

    {{-- ================================================================
         PAGE HEADER
    ================================================================ --}}
    <div style="background: #3D1F0F; padding: 2.25rem 0 2rem; border-bottom: 1px solid rgba(245,233,211,0.07);">
        <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">
            <h1 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 900; color: #F5E9D3; margin-bottom: 0.375rem;">
                Pilih Menu
            </h1>
            <p style="font-size: 0.875rem; color: rgba(245,233,211,0.5); font-weight: 300;">
                Tambahkan item ke keranjang, lalu lanjut ke checkout.
            </p>

            {{-- PEMILIHAN CABANG --}}
            <div style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.875rem; flex-wrap: nowrap;">

                <form method="POST" action="/order/branch" id="form-branch-switch" style="flex-shrink: 0;">
                    @csrf
                    <div style="position: relative;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="rgba(245,233,211,0.5)" stroke-width="2" aria-hidden="true" style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <select
                            name="branch_id"
                            onchange="document.getElementById('form-branch-switch').submit()"
                            style="padding:0.6rem 2rem 0.6rem 2.25rem; border-radius:9999px; border:1.5px solid rgba(245,233,211,0.15); background:rgba(255,255,255,0.07); color:#F5E9D3; font-family:var(--font-sans); font-size:0.875rem; font-weight:500; outline:none; cursor:pointer; appearance:none; transition:border-color 0.15s; min-width:180px;"
                            onfocus="this.style.borderColor='rgba(200,129,59,0.6)'"
                            onblur="this.style.borderColor='rgba(245,233,211,0.15)'"
                            aria-label="Pilih cabang"
                        >
                            <option value="" {{ !$branch ? 'selected' : '' }} disabled style="color:#A08060; background:#FFFFFF;">Pilih Cabang</option>
                            @foreach($branches as $b)
                            @php $isOpen = $b->isOpen(); @endphp
                            <option value="{{ $b->id_branches }}" {{ $branch && $b->id_branches == $branch->id_branches ? 'selected' : '' }} {{ !$isOpen ? 'disabled' : '' }} style="color:#1C0F0A; background:#FFFFFF;">
                                {{ $b->name }} @if(!$isOpen)(Tutup)@endif
                            </option>
                            @endforeach
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="rgba(245,233,211,0.5)" stroke-width="2.5" aria-hidden="true" style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </div>
                </form>

                @if($branch)
                {{-- search bar --}}
                <div style="position: relative; flex: 1; max-width: 380px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="rgba(245,233,211,0.4)" stroke-width="2" aria-hidden="true" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="search"
                        id="menu-search"
                        placeholder="Cari menu..."
                        autocomplete="off"
                        style="width:100%; padding:0.6rem 1rem 0.6rem 2.5rem; border-radius:9999px; border:1.5px solid rgba(245,233,211,0.15); background:rgba(255,255,255,0.07); color:#F5E9D3; font-family:var(--font-sans); font-size:0.875rem; outline:none; transition:border-color 0.15s;"
                        onfocus="this.style.borderColor='rgba(200,129,59,0.6)'"
                        onblur="this.style.borderColor='rgba(245,233,211,0.15)'"
                        aria-label="Cari menu"
                    >
                </div>

                {{-- filter tab kategori --}}
                <div style="display:flex; gap:0.5rem; flex-wrap: nowrap;">
                    <button type="button" class="menu-cat-tab menu-cat-active" data-cat="all"
                        style="padding:0.45rem 1rem; border-radius:9999px; border:1.5px solid #C8813B; background:#C8813B; color:#1C0F0A; font-size:0.8125rem; font-weight:600; cursor:pointer; white-space:nowrap; transition:all 0.15s;">
                        Semua
                    </button>
                    @foreach($products->pluck('category.name')->unique()->filter()->sort() as $catName)
                    <button type="button" class="menu-cat-tab" data-cat="{{ $catName }}"
                        style="padding:0.45rem 1rem; border-radius:9999px; border:1.5px solid rgba(245,233,211,0.2); background:transparent; color:rgba(245,233,211,0.7); font-size:0.8125rem; font-weight:500; cursor:pointer; white-space:nowrap; transition:all 0.15s;"
                        onmouseover="if(!this.classList.contains('menu-cat-active')){this.style.borderColor='rgba(200,129,59,0.5)';this.style.color='#F5E9D3';}"
                        onmouseout="if(!this.classList.contains('menu-cat-active')){this.style.borderColor='rgba(245,233,211,0.2)';this.style.color='rgba(245,233,211,0.7)';}">
                        {{ $catName }}
                    </button>
                    @endforeach
                </div>
                @endif

            </div>

            @if($branch)
            {{-- notifikasi hasil search --}}
            <p id="menu-search-result" style="font-size:0.8125rem; color:rgba(245,233,211,0.5); margin-top:0.75rem; display:none;" aria-live="polite"></p>
            @endif

        </div>
    </div>

    {{-- ================================================================
         MAIN LAYOUT: katalog kiri + cart kanan
    ================================================================ --}}

    @if(!$branch)
    {{-- ==========================================
         PILIH CABANG: belum pilih cabang
    ========================================== --}}
    <div style="max-width: 800px; margin: 0 auto; padding: 3rem 2.5rem 5rem;">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.75rem;" aria-hidden="true">📍</div>
            <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 900; color: #1C0F0A; margin-bottom: 0.5rem;">
                Pilih Cabang
            </h2>
            <p style="font-size: 0.875rem; color: #A08060; font-weight: 300;">
                Pilih cabang Pod's terdekat untuk mulai memesan.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
            @foreach($branches as $b)
            @php $isOpen = $b->isOpen(); @endphp
            <form method="POST" action="/order/branch" style="display: contents;">
                @csrf
                <input type="hidden" name="branch_id" value="{{ $b->id_branches }}">
                <button type="submit"
                    style="
                        text-align: left; cursor: {{ $isOpen ? 'pointer' : 'not-allowed' }};
                        background: #FFFFFF; border-radius: 1rem;
                        border: 1.5px solid {{ $isOpen ? '#EDE0CC' : '#FECACA' }};
                        padding: 1.5rem;
                        box-shadow: 0 1px 4px rgba(28,15,10,0.06);
                        transition: all 0.2s;
                        opacity: {{ $isOpen ? '1' : '0.6' }};
                        font-family: var(--font-sans); width: 100%;
                    "
                    {{ !$isOpen ? 'disabled' : '' }}
                    onmouseover="if({{ $isOpen ? 'true' : 'false' }}){this.style.borderColor='#C8813B';this.style.boxShadow='0 4px 16px rgba(200,129,59,0.15)';}"
                    onmouseout="if({{ $isOpen ? 'true' : 'false' }}){this.style.borderColor='#EDE0CC';this.style.boxShadow='0 1px 4px rgba(28,15,10,0.06)';}"
                >
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;">
                        <div>
                            <p style="font-size: 1rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.25rem;">{{ $b->name }}</p>
                            <p style="font-size: 0.75rem; color: #A08060; font-weight: 300;">
                                @if($b->is_always_open)
                                Buka 24 Jam
                                @else
                                {{ \Carbon\Carbon::parse($b->open_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($b->close_time)->format('H:i') }} WIB
                                @endif
                            </p>
                        </div>
                        <span style="
                            display: inline-flex; align-items: center; gap: 4px;
                            padding: 3px 9px; border-radius: 9999px;
                            font-size: 0.6875rem; font-weight: 600; white-space: nowrap; flex-shrink: 0;
                            background: {{ $isOpen ? '#D1FAE5' : '#FEE2E2' }};
                            color: {{ $isOpen ? '#065F46' : '#991B1B' }};
                        ">
                            <span style="width:5px;height:5px;border-radius:9999px;background:{{ $isOpen ? '#059669' : '#DC2626' }};" aria-hidden="true"></span>
                            {{ $isOpen ? 'Buka' : 'Tutup' }}
                        </span>
                    </div>
                </button>
            </form>
            @endforeach
        </div>
    </div>

    @else
    {{-- ==========================================
         MENU: sudah pilih cabang
    ========================================== --}}
    <div style="max-width: 1600px;width: 100%;margin: 0 auto;padding: 2.5rem 2.5rem 4rem;display: flex;gap: 1.75rem;align-items: flex-start;">

        {{-- KOLOM KIRI: Katalog Produk --}}
        <div style="flex: 1; min-width: 0;">

            @php
                $grouped = $products->groupBy(fn($p) => $p->category->name ?? 'Lainnya');
            @endphp

            @forelse($grouped as $categoryName => $items)
            <div class="menu-category-group" data-group="{{ $categoryName }}" style="margin-bottom: 2.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.125rem;">
                    <h2 style="font-family: var(--font-serif); font-size: 1.125rem; font-weight: 700; color: #1C0F0A; white-space: nowrap;">
                        {{ $categoryName }}
                    </h2>
                    <div style="flex: 1; height: 1px; background: #EDE0CC;"></div>
                    <span style="font-size: 0.75rem; color: #A08060; font-weight: 400; white-space: nowrap;">
                        {{ $items->count() }} item
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                    @foreach($items as $product)
                    @php
                        $qtyInCart = $cart[(string)$product->id_products] ?? 0;
                        $availQty = $stocks[$product->id_products]->available_qty ?? 0;
                        $outOfStock = $availQty <= 0;
                        $discount = $promoDiscounts[$product->id_products] ?? 0;
                        $hasDiscount = $discount > 0;
                        $discountedPrice = $product->base_price - $discount;
                    @endphp
                    <article
                        class="menu-product-card"
                        data-name="{{ strtolower($product->name) }}"
                        data-cat="{{ $product->category->name ?? '' }}"
                        style="
                            background: #FFFFFF;
                            border-radius: 1rem;
                            border: 1px solid {{ $outOfStock ? '#FECACA' : '#EDE0CC' }};
                            overflow: hidden;
                            box-shadow: 0 1px 3px rgba(28,15,10,0.05);
                            transition: box-shadow 0.2s, transform 0.2s;
                            position: relative;
                            opacity: {{ $outOfStock ? '0.6' : '1' }};
                        "
                        onmouseover="if(!this.classList.contains('out-of-stock')){this.style.boxShadow='0 6px 20px rgba(28,15,10,0.1)'; this.style.transform='translateY(-2px)';}"
                        onmouseout="if(!this.classList.contains('out-of-stock')){this.style.boxShadow='0 1px 3px rgba(28,15,10,0.05)'; this.style.transform='translateY(0)';}"
                    >
                        @if($qtyInCart > 0)
                        <div style="position: absolute; top: 0.625rem; right: 0.625rem; z-index: 2; width: 22px; height: 22px; border-radius: 9999px; background: #C8813B; display: flex; align-items: center; justify-content: center; font-size: 0.6875rem; font-weight: 700; color: #1C0F0A;" aria-label="{{ $qtyInCart }} item di keranjang">
                            {{ $qtyInCart }}
                        </div>
                        @endif

                        @if($hasDiscount)
                        <div style="position: absolute; top: 0.625rem; left: 0.625rem; z-index: 2; padding: 0.3rem 0.6rem; border-radius: 9999px; background: #DC2626; font-size: 0.625rem; font-weight: 700; color: #FFFFFF; letter-spacing: 0.02em; box-shadow: 0 2px 6px rgba(220,38,38,0.35);">
                            {{ $promoDescriptions[$product->id_products] ?? ('-'.number_format($discount, 0, ',', '.')) }}
                        </div>
                        @endif

                        <div style="aspect-ratio: 1/1; background: linear-gradient(135deg, rgba(61,31,15,0.08) 0%, rgba(200,129,59,0.16) 100%); display: flex; align-items: center; justify-content: center; overflow: hidden;" aria-hidden="true">
                            @if($product->getRawOriginal('image_url'))
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <span style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-size: 2.5rem; opacity: 0.3;">☕</span>
                            @else
                            <span style="font-size: 2.5rem; opacity: 0.3;">☕</span>
                            @endif
                        </div>

                        <div style="padding: 0.875rem 1rem 1rem;">
                            <h3 style="font-size: 0.9375rem; font-weight: 600; color: #1C0F0A; margin-bottom: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $product->name }}
                            </h3>

                            @if($hasDiscount)
                            <p style="font-size: 0.75rem; color: #A08060; text-decoration: line-through; margin-bottom: 0.1rem;">
                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                            </p>
                            <p style="font-size: 0.875rem; font-weight: 700; color: #DC2626; margin-bottom: 0.875rem;">
                                Rp {{ number_format($discountedPrice, 0, ',', '.') }}
                            </p>
                            @else
                            <p style="font-size: 0.875rem; font-weight: 600; color: #3D1F0F; margin-bottom: 0.875rem;">
                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                            </p>
                            @endif

                            @if($outOfStock)
                            <div style="width: 100%; padding: 0.5rem 0; border-radius: 0.625rem; border: 1.5px solid #FECACA; background: #FEF2F2; color: #DC2626; font-size: 0.75rem; font-weight: 600; text-align: center;">
                                Stok Habis
                            </div>
                            @else
                            <form method="POST" action="/order/cart/add">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id_products }}">
                                <button type="submit" style="width: 100%; padding: 0.5rem 0; border-radius: 0.625rem; border: 1.5px solid {{ $qtyInCart > 0 ? '#C8813B' : '#EDE0CC' }}; background: {{ $qtyInCart > 0 ? 'rgba(200,129,59,0.1)' : 'transparent' }}; color: {{ $qtyInCart > 0 ? '#C8813B' : '#A08060' }}; font-size: 0.8125rem; font-weight: 600; cursor: pointer; transition: all 0.15s; display: flex; align-items: center; justify-content: center; gap: 0.375rem;"
                                    onmouseover="this.style.borderColor='#C8813B'; this.style.background='rgba(200,129,59,0.1)'; this.style.color='#C8813B';"
                                    onmouseout="this.style.borderColor='{{ $qtyInCart > 0 ? '#C8813B' : '#EDE0CC' }}'; this.style.background='{{ $qtyInCart > 0 ? 'rgba(200,129,59,0.1)' : 'transparent' }}'; this.style.color='{{ $qtyInCart > 0 ? '#C8813B' : '#A08060' }}';"
                                    aria-label="Tambahkan {{ $product->name }} ke keranjang">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    Tambah
                                </button>
                            </form>
                            @endif
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

        {{-- KOLOM KANAN: Cart --}}
        <aside style="width: 320px; flex-shrink: 0; position: sticky; top: calc(72px + 1.5rem); display: flex; flex-direction: column; gap: 1rem;" aria-label="Ringkasan keranjang belanja">
            <div style="background: #FFFFFF; border-radius: 1.125rem; border: 1px solid #EDE0CC; box-shadow: 0 2px 12px rgba(28,15,10,0.07); overflow: hidden;">

                <div style="background: #1C0F0A; padding: 1rem 1.375rem; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <h2 style="font-size: 0.9375rem; font-weight: 600; color: #F5E9D3;">Keranjang</h2>
                    </div>
                    @php $totalItems = array_sum($cart); @endphp
                    @if($totalItems > 0)
                    <span style="font-size: 0.75rem; font-weight: 600; background: #C8813B; color: #1C0F0A; padding: 0.15rem 0.6rem; border-radius: 9999px;">{{ $totalItems }} item</span>
                    @endif
                </div>

                @if(empty($cart))
                <div style="padding: 2.5rem 1.375rem; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.3;" aria-hidden="true">🛒</div>
                    <p style="font-size: 0.875rem; color: #A08060; font-weight: 300;">Keranjang masih kosong.<br>Tambahkan menu favoritmu!</p>
                </div>
                @else
                <div style="padding: 0.875rem 1.375rem; max-height: 340px; overflow-y: auto;">
                    @php $grandTotal = 0; @endphp
                    @foreach($cart as $productId => $qty)
                    @php
                        $p = $products->firstWhere('id_products', $productId);
                        if (!$p) continue;
                        $discount = $promoDiscounts[$productId] ?? 0;
                        $effectivePrice = $p->base_price - $discount;
                        $lineTotal  = $effectivePrice * $qty;
                        $grandTotal += $lineTotal;
                    @endphp
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #EDE0CC;">
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 500; color: #1C0F0A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.2rem;">{{ $p->name }}</p>
                            <p style="font-size: 0.75rem; color: #A08060;">Rp {{ number_format($effectivePrice, 0, ',', '.') }} × {{ $qty }}
                                @if($discount > 0)<span style="color: #DC2626; font-weight: 600;"> (diskon Rp {{ number_format($discount, 0, ',', '.') }})</span>@endif
                            </p>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0;">
                            <span style="font-size: 0.875rem; font-weight: 600; color: #3D1F0F;">Rp {{ number_format($lineTotal, 0, ',', '.') }}</span>
                            <form method="POST" action="/order/cart/remove" style="display: inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productId }}">
                                <button type="submit" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 9999px; border: 1px solid rgba(239,68,68,0.25); background: transparent; color: rgba(220,38,38,0.6); cursor: pointer; font-size: 0.9rem; line-height: 1; transition: all 0.15s;" onmouseover="this.style.background='rgba(239,68,68,0.08)'; this.style.borderColor='rgba(239,68,68,0.45)';" onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(239,68,68,0.25)';" aria-label="Kurangi {{ $p->name }}">−</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div style="padding: 1rem 1.375rem; border-top: 1px solid #EDE0CC; background: #FBF6EE;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span style="font-size: 0.875rem; color: #A08060;">Total</span>
                        <span style="font-family: var(--font-serif); font-size: 1.125rem; font-weight: 900; color: #1C0F0A;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('orders.checkout') }}" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem; border-radius: 0.75rem; background: #C8813B; color: #1C0F0A; font-size: 0.9375rem; font-weight: 600; letter-spacing: 0.04em; text-decoration: none; box-shadow: 0 4px 14px rgba(200,129,59,0.3); transition: background 0.2s, transform 0.1s;" onmouseover="this.style.background='#D99045'" onmouseout="this.style.background='#C8813B'" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                        Lanjut ke Checkout
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                @endif

            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; border-radius: 0.75rem; border: 1px solid #EDE0CC; background: #FFFFFF;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#C8813B" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <div style="flex: 1;">
                    <p style="font-size: 0.75rem; font-weight: 500; color: #1C0F0A; display: flex; align-items: center; gap: 0.375rem;">
                        {{ $branch->name }}
                        @if($branch->isOpen())
                        <span style="display:inline-flex;align-items:center;gap:3px;padding:1px 6px;border-radius:9999px;font-size:0.625rem;font-weight:600;background:#D1FAE5;color:#065F46;"><span style="width:4px;height:4px;border-radius:9999px;background:#059669;" aria-hidden="true"></span> Buka</span>
                        @else
                        <span style="display:inline-flex;align-items:center;gap:3px;padding:1px 6px;border-radius:9999px;font-size:0.625rem;font-weight:600;background:#FEE2E2;color:#991B1B;"><span style="width:4px;height:4px;border-radius:9999px;background:#DC2626;" aria-hidden="true"></span> Tutup</span>
                        @endif
                    </p>
                    <p style="font-size: 0.6875rem; color: #A08060;">
                        @if($branch->is_always_open)
                        Buka 24 Jam
                        @else
                        {{ \Carbon\Carbon::parse($branch->open_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($branch->close_time)->format('H:i') }} WIB
                        @endif
                    </p>
                </div>
            </div>
        </aside>

    </div>
    @endif

</div>

@endsection

@push('scripts')
<script>
(function () {
    var searchInput  = document.getElementById('menu-search');
    if (!searchInput) return; // tidak ada produk, tidak perlu filter

    var resultNotice = document.getElementById('menu-search-result');
    var catTabs      = document.querySelectorAll('.menu-cat-tab');
    var cards        = document.querySelectorAll('.menu-product-card');
    var groups       = document.querySelectorAll('.menu-category-group');

    var activeCat = 'all';
    var searchVal = '';

    function applyFilter() {
        var visible = 0;

        cards.forEach(function (card) {
            var nameMatch = card.dataset.name.includes(searchVal);
            var catMatch  = activeCat === 'all' || card.dataset.cat === activeCat;
            var show      = nameMatch && catMatch;

            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        groups.forEach(function (group) {
            var groupCat     = group.dataset.group;
            var hasVisible   = false;

            group.querySelectorAll('.menu-product-card').forEach(function (c) {
                if (c.style.display !== 'none') hasVisible = true;
            });

            group.style.display = hasVisible ? '' : 'none';
        });

        if (resultNotice) {
            if (searchInput.value.trim() && visible === 0) {
                resultNotice.textContent = 'Tidak ada menu yang cocok dengan "' + searchInput.value.trim() + '".';
                resultNotice.style.display = 'block';
            } else {
                resultNotice.style.display = 'none';
            }
        }
    }

    var debounceTimer;
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            searchVal = searchInput.value.trim().toLowerCase();
            applyFilter();
        }, 250);
    });

    catTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activeCat = tab.dataset.cat;

            catTabs.forEach(function (t) {
                t.classList.remove('menu-cat-active');
                t.style.background   = 'transparent';
                t.style.borderColor  = 'rgba(245,233,211,0.2)';
                t.style.color        = 'rgba(245,233,211,0.7)';
                t.style.fontWeight   = '500';
            });

            tab.classList.add('menu-cat-active');
            tab.style.background   = '#C8813B';
            tab.style.borderColor  = '#C8813B';
            tab.style.color        = '#1C0F0A';
            tab.style.fontWeight   = '600';

            applyFilter();
        });
    });
}());
</script>
@endpush
