{{-- ── HALAMAN LOGIN: Split screen kaku 55% gambar, 45% form --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pod's</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --font-serif:    'Playfair Display', Georgia, serif;
            --font-sans:     'DM Sans', system-ui, sans-serif;
            --pods-espresso: #1C0F0A;
            --pods-brown:    #3D1F0F;
            --pods-caramel:  #C8813B;
            --pods-cream:    #F5E9D3;
            --pods-offwhite: #FBF6EE;
            --pods-muted:    #A08060;
        }
        body          { font-family: var(--font-sans); }
        .font-serif, h1, h2 { font-family: var(--font-serif); }
        *:focus-visible { outline: 2px solid #C8813B; outline-offset: 3px; border-radius: 6px; }

        /* komponen input terpusat */
        .pods-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid #D4C4AE;
            border-radius: 10px;
            background-color: #FFFDF9;
            color: #1C0F0A;
            font-family: var(--font-sans);
            font-size: 0.9375rem;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .pods-input:focus {
            outline: none;
            border-color: #C8813B;
            box-shadow: 0 0 0 3px rgba(200, 129, 59, 0.18);
        }
        .pods-input::placeholder { color: #B8A090; }

        /* state error: border merah saat ada error dari laravel */
        .pods-input.is-error {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
        }
        .pods-input.is-error:focus {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.18);
        }

        @keyframes slide-in-right {
            from { opacity: 0; transform: translateX(28px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in { animation: slide-in-right 0.5s cubic-bezier(0.22, 1, 0.36, 1) both; }
    </style>
</head>

<body
    class="h-full antialiased"
    style="background: #FBF6EE; min-width: 1280px; overflow-x: auto; margin: 0; padding: 0;"
>

<div style="width: 100%; min-width: 1280px; min-height: 100vh; display: flex;">

    {{-- ==========================================
         KOLOM KIRI — Hero Image + Branding
    ========================================== --}}
    <div
        style="width: 55%; flex-shrink: 0; position: relative; display: flex; flex-direction: column; justify-content: space-between; padding: 3rem; overflow: hidden;"
        aria-hidden="true"
    >
        {{-- gambar hero kopi --}}
        <img
            src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=1400&q=80"
            alt=""
            style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;"
            loading="eager"
        >
        {{-- dark overlay berlapis --}}
        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(28,15,10,0.85) 0%, rgba(61,31,15,0.55) 50%, rgba(28,15,10,0.90) 100%);"></div>

        {{-- logo --}}
        <div style="position: relative; z-index: 10;">
            <a
                href="{{ route('main') }}"
                class="font-serif"
                style="font-size: 1.75rem; font-weight: 900; color: #F5E9D3; text-decoration: none; transition: color 0.2s;"
                onmouseover="this.style.color='#C8813B'"
                onmouseout="this.style.color='#F5E9D3'"
            >
                Pod's
            </a>
        </div>

        {{-- headline utama --}}
        <div style="position: relative; z-index: 10;">
            <p style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.3em; text-transform: uppercase; color: #C8813B; margin-bottom: 1.25rem;">
                Est. 2026 · Medan, Indonesia
            </p>
            <h1 class="font-serif" style="font-size: 3.5rem; font-weight: 900; line-height: 1.1; color: #F5E9D3; margin-bottom: 1.25rem;">
                Where Every<br>
                <em style="color: #C8813B; font-style: italic;">Cup Tells</em><br>
                a Story
            </h1>
            <p style="font-size: 0.9rem; font-weight: 300; line-height: 1.7; color: rgba(245,233,211,0.6); max-width: 280px;">
                Freshly brewed coffee, warm atmosphere, and a place you'll always come back to.
            </p>
        </div>

        {{-- quote footer --}}
        <div style="position: relative; z-index: 10;">
            <div style="width: 32px; height: 2px; background: #C8813B; margin-bottom: 0.75rem;"></div>
            <p style="font-size: 0.72rem; font-weight: 300; font-style: italic; color: rgba(245,233,211,0.35);">
                "Coffee is a language in itself." — Jackie Chan
            </p>
        </div>
    </div>

    {{-- ==========================================
         KOLOM KANAN — Form Login
    ========================================== --}}
    <div
        style="width: 45%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; padding: 3rem 3.5rem; background: #FBF6EE;"
    >
        <div style="width: 100%; max-width: 420px;" class="animate-slide-in">

            {{-- judul form --}}
            <div style="margin-bottom: 2rem;">
                <h2 class="font-serif" style="font-size: 2rem; font-weight: 900; line-height: 1.2; color: #1C0F0A;">
                    Selamat Datang!
                </h2>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; font-weight: 300; color: #A08060;">
                    Silakan login untuk melakukan pemesanan.
                </p>
            </div>

            {{-- form login: novalidate — semua validasi diserahkan ke laravel server-side --}}
            <form
                method="POST"
                action="{{ route('login') }}"
                novalidate
                aria-label="Form masuk akun Pod's"
            >
                @csrf

                {{-- email --}}
                <div style="margin-bottom: 1.25rem;">
                    <label
                        for="email"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #3D1F0F; margin-bottom: 0.375rem;"
                    >
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="pods-input {{ $errors->has('email') ? 'is-error' : '' }}"
                        placeholder="yourname@gmail.com"
                        autocomplete="email"
                        autofocus
                        aria-required="true"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                    >
                    {{-- inline validation: pesan merah kontekstual di bawah field --}}
                    @error('email')
                        <p id="email-error" class="text-red-600" style="margin-top: 0.375rem; font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- field: password --}}
                <div style="margin-bottom: 1.75rem;">
                    <label
                        for="password"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #3D1F0F; margin-bottom: 0.375rem;"
                    >
                        Password
                    </label>
                    <div style="position: relative;">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="pods-input {{ $errors->has('password') ? 'is-error' : '' }}"
                            style="padding-right: 3rem;"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            aria-required="true"
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
                        >
                        {{-- toggle visibility: aria-pressed menandakan state sr --}}
                        <button
                            type="button"
                            id="toggle-pwd"
                            style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); padding: 0.25rem; color: #A08060; background: none; border: none; cursor: pointer; transition: color 0.15s;"
                            aria-label="Tampilkan atau sembunyikan password"
                            aria-pressed="false"
                            aria-controls="password"
                            onmouseover="this.style.color='#C8813B'"
                            onmouseout="this.style.color='#A08060'"
                        >
                            <svg id="icon-eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="icon-eye-off" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p id="password-error" class="text-red-600" style="margin-top: 0.375rem; font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- cta: tombol submit espresso solid --}}
                <button
                    type="submit"
                    style="width: 100%; padding: 0.875rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; letter-spacing: 0.05em; background: #1C0F0A; color: #F5E9D3; border: none; cursor: pointer; transition: background 0.2s, transform 0.1s;"
                    onmouseover="this.style.background='#3D1F0F'"
                    onmouseout="this.style.background='#1C0F0A'"
                    onmousedown="this.style.transform='scale(0.98)'"
                    onmouseup="this.style.transform='scale(1)'"
                >
                    LOGIN
                </button>

            </form>

            {{-- divider --}}
            <div style="display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0;">
                <div style="flex: 1; height: 1px; background: #D4C4AE;"></div>
                <span style="font-size: 0.75rem; font-weight: 500; color: #A08060;">atau</span>
                <div style="flex: 1; height: 1px; background: #D4C4AE;"></div>
            </div>

            {{-- link register --}}
            <p style="text-align: center; font-size: 0.875rem; color: #A08060;">
                Belum punya akun?
                <a
                    href="{{ route('register') }}"
                    style="font-weight: 600; color: #C8813B; text-decoration: underline; text-underline-offset: 2px; transition: color 0.2s;"
                    onmouseover="this.style.color='#3D1F0F'"
                    onmouseout="this.style.color='#C8813B'"
                >
                    Daftar Akun
                </a>
            </p>

        </div>
    </div>

</div>

{{-- ── LEVEL B: Toast — session('success') dari redirect setelah register berhasil --}}
{{-- menggunakan json_encode() untuk mencegah xss pada nilai session --}}
@if (session('success'))
<script>
(function () {
    const _toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false,
        timer: 3500, timerProgressBar: true,
        background: '#FBF6EE', color: '#1C0F0A',
    });
    _toast.fire({ icon: 'success', title: {!! json_encode(session('success')) !!} });
}());
</script>
@endif

{{-- ── toggle visibility password: swap dua ikon, update aria-pressed --}}
<script>
(function () {
    const btn     = document.getElementById('toggle-pwd');
    const input   = document.getElementById('password');
    const iconOn  = document.getElementById('icon-eye-open');
    const iconOff = document.getElementById('icon-eye-off');
    if (!btn || !input) return;

    btn.addEventListener('click', function () {
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        /* swap ikon: gunakan display inline style agar tidak bergantung class hidden tailwind */
        iconOn.style.display  = isHidden ? 'none' : 'inline';
        iconOff.style.display = isHidden ? 'inline' : 'none';
        btn.setAttribute('aria-pressed', String(isHidden));
    });
}());
</script>
</body>
</html>