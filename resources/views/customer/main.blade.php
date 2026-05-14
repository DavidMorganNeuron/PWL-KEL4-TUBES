{{-- CUSTOMER MAIN: landing page — hero + featured menu + why us + cta final --}}
{{-- menggunakan master layout app.blade.php --}}
@extends('layout.app')

@section('title', "Main Page — Pod's")

@section('content')

{{-- ================================================================
     SECTION 1: HERO SECTION
================================================================ --}}
<section
    style="
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-top: -72px;
    "
    aria-label="Hero section Pod's Coffee"
>
    {{-- latar hero: gambar kopi estetis --}}
    <div style="position: absolute; inset: 0; z-index: 0;">
        <img
            src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1920&q=80"
            alt=""
            aria-hidden="true"
            style="width: 100%; height: 100%; object-fit: cover; object-position: center;"
            loading="eager"
            fetchpriority="high"
        >

        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(28,15,10,0.72) 0%, rgba(28,15,10,0.52) 50%, rgba(28,15,10,0.88) 100%);"></div>
        
        <div style="position: absolute; inset: 0; background: rgba(61,31,15,0.22); mix-blend-mode: multiply;"></div>
    </div>

    {{-- konten tengah hero --}}
    <div style="position: relative; z-index: 10; width: 1280px; margin: 0 auto; text-align: center; padding: 72px 2.5rem 0;">

        {{-- label est. --}}
        <p
            class="pods-hero-label"
            style="
                display: inline-block;
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.25em;
                text-transform: uppercase;
                color: #C8813B;
                margin-bottom: 1.5rem;
                opacity: 0;
                animation: fade-in-up 0.7s cubic-bezier(0.22,1,0.36,1) 0.1s both;
            "
        >
            Est. 2026 · Medan, Indonesia
        </p>

        {{-- headline utama: serif besar, bold, krem --}}
        <h1
            style="
                font-family: var(--font-serif);
                font-size: 5.5rem;
                font-weight: 900;
                line-height: 1.04;
                color: #F5E9D3;
                margin-bottom: 1.5rem;
                opacity: 0;
                animation: fade-in-up 0.7s cubic-bezier(0.22,1,0.36,1) 0.2s both;
            "
        >
            Where Every Cup<br>
            <span style="font-style: italic; color: #C8813B;">Tells a Story</span>
        </h1>

        {{-- subheadline --}}
        <p
            style="
                font-size: 1.0625rem;
                font-weight: 300;
                color: rgba(245,233,211,0.78);
                max-width: 480px;
                margin: 0 auto 2.5rem;
                line-height: 1.7;
                opacity: 0;
                animation: fade-in-up 0.7s cubic-bezier(0.22,1,0.36,1) 0.35s both;
            "
        >
            Freshly brewed coffee, warm atmosphere, and a place you'll always come back to.
        </p>

        {{-- dua tombol CTA side-by-side — STATIS, tidak ada kolom vertikal di layar kecil --}}
        <div
            style="
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 1rem;
                opacity: 0;
                animation: fade-in-up 0.7s cubic-bezier(0.22,1,0.36,1) 0.5s both;
            "
        >
            {{-- primer: solid karamel --}}
            <a
                href="{{ route('orders.branch') }}"
                id="cta-hero-order"
                style="
                    display: inline-flex;
                    align-items: center;
                    padding: 0.875rem 2.25rem;
                    border-radius: 9999px;
                    background: #C8813B;
                    color: #1C0F0A;
                    font-size: 0.9375rem;
                    font-weight: 600;
                    letter-spacing: 0.04em;
                    text-decoration: none;
                    box-shadow: 0 8px 24px rgba(200,129,59,0.35);
                    transition: background 0.2s, transform 0.1s;
                "
                onmouseover="this.style.background='#D99045'"
                onmouseout="this.style.background='#C8813B'"
                onmousedown="this.style.transform='scale(0.96)'"
                onmouseup="this.style.transform='scale(1)'"
            >
                Order Now
                <span id="cta-hero-arrow" style="display: inline-block; margin-left: 0.5rem; transition: transform 0.2s;">→</span>
            </a>

            {{-- sekunder: outline cream --}}
            <a
                href="#featured"
                style="
                    display: inline-flex;
                    align-items: center;
                    padding: 0.875rem 2.25rem;
                    border-radius: 9999px;
                    border: 1.5px solid rgba(245,233,211,0.55);
                    color: #F5E9D3;
                    font-size: 0.9375rem;
                    font-weight: 600;
                    letter-spacing: 0.04em;
                    text-decoration: none;
                    transition: border-color 0.2s, color 0.2s, transform 0.1s;
                "
                onmouseover="this.style.borderColor='#C8813B'; this.style.color='#C8813B';"
                onmouseout="this.style.borderColor='rgba(245,233,211,0.55)'; this.style.color='#F5E9D3';"
                onmousedown="this.style.transform='scale(0.96)'"
                onmouseup="this.style.transform='scale(1)'"
            >
                See Menu
            </a>
        </div>

    </div>
</section>

{{-- ================================================================
     SECTION 2: FEATURED MENU
================================================================ --}}
<section id="featured" style="background: #FBF6EE; padding: 6rem 0;" aria-label="Menu unggulan Pod's">
    <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">

        {{-- judul section --}}
        <div style="text-align: center; margin-bottom: 3.5rem;">
            <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: #C8813B; margin-bottom: 0.75rem;">
                Signature Menu
            </p>
            <h2 style="font-family: var(--font-serif); font-size: 2.75rem; font-weight: 900; color: #1C0F0A; line-height: 1.15; margin-bottom: 1rem;">
                Crafted with Passion
            </h2>
            <p style="color: #A08060; font-weight: 300; max-width: 380px; margin: 0 auto; line-height: 1.65;">
                Setiap cangkir adalah karya — biji kopi pilihan, tangan yang penuh cinta.
            </p>
        </div>

        {{-- grid 3 kolom statis --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
            @foreach([
                ['Signature Espresso', 'Coffee',     'Rp 35.000', 'Pekat, bold, dan tak terlupakan. Karakter kopi yang sesungguhnya.'],
                ['Iced Caramel Latte', 'Coffee',     'Rp 42.000', 'Karamel manis, susu dingin yang lembut di setiap tegukan.'],
                ['Matcha Oreo Shake',  'Non-Coffee', 'Rp 38.000', 'Matcha premium bertemu oreo renyah — perpaduan tak terduga.'],
            ] as [$name, $category, $price, $desc])
            <article
                style="
                    background: #FFFFFF;
                    border-radius: 1.125rem;
                    overflow: hidden;
                    border: 1px solid #EDE0CC;
                    box-shadow: 0 1px 4px rgba(28,15,10,0.06);
                    transition: box-shadow 0.25s, transform 0.2s;
                "
                onmouseover="this.style.boxShadow='0 8px 28px rgba(28,15,10,0.12)'; this.style.transform='translateY(-2px)';"
                onmouseout="this.style.boxShadow='0 1px 4px rgba(28,15,10,0.06)'; this.style.transform='translateY(0)';"
            >
                {{-- thumbnail placeholder estetis --}}
                <div style="height: 180px; background: linear-gradient(135deg, rgba(61,31,15,0.1) 0%, rgba(200,129,59,0.2) 100%); position: relative; overflow: hidden;">
                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 3.5rem; opacity: 0.28;" aria-hidden="true">☕</span>
                    </div>
                    <span style="
                        position: absolute; top: 0.75rem; left: 0.75rem;
                        font-size: 0.7rem; font-weight: 600; letter-spacing: 0.05em;
                        background: rgba(200,129,59,0.15); color: #C8813B;
                        padding: 0.2rem 0.65rem; border-radius: 9999px;
                    ">
                        {{ $category }}
                    </span>
                </div>
                <div style="padding: 1.25rem 1.375rem 1.375rem;">
                    <h3 style="font-family: var(--font-serif); font-size: 1.125rem; font-weight: 700; color: #1C0F0A; margin-bottom: 0.375rem;">
                        {{ $name }}
                    </h3>
                    <p style="font-size: 0.8125rem; color: #A08060; font-weight: 300; line-height: 1.55; margin-bottom: 1.125rem;">
                        {{ $desc }}
                    </p>
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 0.9375rem; font-weight: 600; color: #3D1F0F;">{{ $price }}</span>
                        <a
                            href="{{ route('orders.branch') }}"
                            style="font-size: 0.75rem; font-weight: 600; color: #C8813B; text-decoration: none; transition: text-decoration 0.15s;"
                            onmouseover="this.style.textDecoration='underline'"
                            onmouseout="this.style.textDecoration='none'"
                            aria-label="Pesan {{ $name }}"
                        >
                            Order →
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- CTA lihat semua --}}
        <div style="text-align: center; margin-top: 2.5rem;">
            <a
                href="{{ route('orders.branch') }}"
                style="
                    display: inline-block;
                    padding: 0.875rem 2.25rem;
                    border-radius: 9999px;
                    border: 2px solid #C8813B;
                    color: #C8813B;
                    font-size: 0.9375rem;
                    font-weight: 600;
                    letter-spacing: 0.04em;
                    text-decoration: none;
                    transition: background 0.2s, color 0.2s, transform 0.1s;
                "
                onmouseover="this.style.background='#C8813B'; this.style.color='#1C0F0A';"
                onmouseout="this.style.background='transparent'; this.style.color='#C8813B';"
                onmousedown="this.style.transform='scale(0.96)'"
                onmouseup="this.style.transform='scale(1)'"
            >
                Lihat Semua Menu
            </a>
        </div>

    </div>
</section>

{{-- ================================================================
     SECTION 3: WHY CHOOSE US
================================================================ --}}
<section style="background: #3D1F0F; padding: 6rem 0;" aria-label="Keunggulan Pod's">
    <div style="width: 1280px; margin: 0 auto; padding: 0 2.5rem;">

        <div style="text-align: center; margin-bottom: 3.5rem;">
            <p style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: #C8813B; margin-bottom: 0.75rem;">
                Why Pod's?
            </p>
            <h2 style="font-family: var(--font-serif); font-size: 2.75rem; font-weight: 900; color: #F5E9D3; line-height: 1.15;">
                More Than Just Coffee
            </h2>
        </div>

        {{-- grid 3 kolom statis --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; text-align: center;">
            @foreach([
                ['🫘', 'Premium Beans',  'Biji kopi pilihan dari perkebunan terbaik Nusantara, diroasting segar setiap hari untuk menjaga karakternya.'],
                ['👨‍🍳', 'Expert Barista', 'Tim barista bersertifikat dengan passion sama besarnya dengan cangkir terbaik yang pernah mereka buat.'],
                ['⚡', 'Fast Ordering',  'Sistem pemesanan digital memastikan kopimu siap sebelum kamu sempat mengedip — efisien & akurat.'],
            ] as [$icon, $title, $desc])
            <div style="padding: 2rem 1.5rem;">
                <div style="font-size: 2.75rem; margin-bottom: 1.125rem; line-height: 1;" aria-hidden="true">{{ $icon }}</div>
                <h3 style="font-family: var(--font-serif); font-size: 1.25rem; font-weight: 700; color: #F5E9D3; margin-bottom: 0.875rem;">
                    {{ $title }}
                </h3>
                <p style="font-size: 0.875rem; color: rgba(245,233,211,0.58); line-height: 1.7; font-weight: 300;">
                    {{ $desc }}
                </p>
            </div>
            @endforeach
        </div>

    </div>
</section>


@endsection

@push('head-scripts')
<style>
    /* animasi staggered fade-in-up: hanya untuk hero section */
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* hover arrow CTA: translate kanan saat mouse over tombol primer */
    #cta-hero-order:hover #cta-hero-arrow { transform: translateX(4px); }
</style>
@endpush