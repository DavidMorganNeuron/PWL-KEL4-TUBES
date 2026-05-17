{{-- HALAMAN DAFTAR --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun — Pod's</title>

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
        body        { font-family: var(--font-sans); }
        .font-serif, h1, h2 { font-family: var(--font-serif); }
        *:focus-visible { outline: 2px solid #C8813B; outline-offset: 3px; border-radius: 6px; }

        /* komponen input: satu definisi untuk semua field */
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
            box-sizing: border-box;
        }
        .pods-input:focus {
            outline: none;
            border-color: #C8813B;
            box-shadow: 0 0 0 3px rgba(200, 129, 59, 0.18);
        }
        .pods-input::placeholder { color: #B8A090; }
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

    {{-- KOLOM KIRI: Hero image + branding --}}
    <div
        style="width: 40%; flex-shrink: 0; position: relative; display: flex; flex-direction: column; justify-content: space-between; padding: 3rem; overflow: hidden;"
        aria-hidden="true"
    >
        {{-- gambar hero kopi --}}
        <img
            src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=1400&q=80"
            alt=""
            style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;"
            loading="eager"
        >
        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(28,15,10,0.78) 0%, rgba(61,31,15,0.50) 40%, rgba(28,15,10,0.93) 100%);"></div>

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

        {{-- headline --}}
        <div style="position: relative; z-index: 10;">
            <p style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.3em; text-transform: uppercase; color: #C8813B; margin-bottom: 1.25rem;">
                Bergabung Sekarang
            </p>
            <h1 class="font-serif" style="font-size: 3rem; font-weight: 900; line-height: 1.1; color: #F5E9D3; margin-bottom: 1.25rem;">
                Mulailah<br>
                <em style="color: #C8813B; font-style: italic;">Perjalanan</em><br>
                Selera Anda
            </h1>
            <p style="font-size: 0.875rem; font-weight: 300; line-height: 1.7; color: rgba(245,233,211,0.6); max-width: 240px;">
                Daftar dan dapatkan akses ke menu Pod's.
            </p>
        </div>

        {{-- quote footer --}}
        <div style="position: relative; z-index: 10;">
            <div style="width: 32px; height: 2px; background: #C8813B; margin-bottom: 0.75rem;"></div>
            <p style="font-size: 0.72rem; font-weight: 300; font-style: italic; color: rgba(245,233,211,0.35);">
                "First, we eat. Then, we do everything else." — M.F.K. Fisher
            </p>
        </div>
    </div>

    {{-- KOLOM KANAN: Form register --}}
    <div
        style="width: 60%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; padding: 3rem 4rem; background: #FBF6EE; overflow-y: auto;"
    >
        <div style="width: 100%; max-width: 480px;" class="animate-slide-in">

            {{-- judul form --}}
            <div style="margin-bottom: 1.75rem;">
                <h2 class="font-serif" style="font-size: 2rem; font-weight: 900; line-height: 1.2; color: #1C0F0A;">
                    Daftar Akun Baru
                </h2>
            </div>

            {{-- FORM REGISTER --}}
            <form
                method="POST"
                action="{{ route('register') }}"
                novalidate
                aria-label="Form pendaftaran akun baru Pod's"
            >
                @csrf

                {{-- nama lengkap --}}
                <div style="margin-bottom: 1.1rem;">
                    <label
                        for="name"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #3D1F0F; margin-bottom: 0.375rem;"
                    >
                        Nama Lengkap
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="pods-input {{ $errors->has('name') ? 'is-error' : '' }}"
                        placeholder="cth: Budi Santoso"
                        autocomplete="name"
                        autofocus
                        maxlength="100"
                        aria-required="true"
                        aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                        aria-describedby="name-hint {{ $errors->has('name') ? 'name-error' : '' }}"
                    >
                    <p id="name-hint" style="margin-top: 0.375rem; font-size: 0.75rem; color: #A08060;">
                        Masukkan nama sesuai dengan KTP Anda
                    </p>
                    @error('name')
                        <p id="name-error" class="text-red-600" style="margin-top: 0.25rem; font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- email --}}
                <div style="margin-bottom: 1.1rem;">
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
                        aria-required="true"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                    >
                    <p id="name-hint" style="margin-top: 0.375rem; font-size: 0.75rem; color: #A08060;">
                        Masukkan email Anda
                    </p>
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

                {{-- password --}}
                <div style="margin-bottom: 1.1rem;">
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
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password"
                            minlength="8"
                            aria-required="true"
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            aria-describedby="pwd-strength {{ $errors->has('password') ? 'password-error' : '' }}"
                        >
                        {{-- tombol toggle: mengontrol password & password-confirm sekaligus --}}
                        <button
                            type="button"
                            id="toggle-pwd"
                            style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); padding: 0.25rem; color: #A08060; background: none; border: none; cursor: pointer; transition: color 0.15s;"
                            aria-label="Tampilkan atau sembunyikan password"
                            aria-pressed="false"
                            aria-controls="password password-confirm"
                            onmouseover="this.style.color='#C8813B'"
                            onmouseout="this.style.color='#A08060'"
                        >
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-off" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p id="password-error" class="text-red-600" style="margin-top: 0.25rem; font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- konfirmasi password --}}
                <div style="margin-bottom: 1.75rem;">
                    <label
                        for="password-confirm"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #3D1F0F; margin-bottom: 0.375rem;"
                    >
                        Konfirmasi Password
                    </label>
                    <input
                        type="password"
                        id="password-confirm"
                        name="password_confirmation"
                        class="pods-input {{ $errors->has('password_confirmation') ? 'is-error' : '' }}"
                        placeholder="Ketik ulang password"
                        autocomplete="new-password"
                        aria-required="true"
                        aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                        aria-describedby="{{ $errors->has('password_confirmation') ? 'confirm-error' : '' }}"
                    >
                    @error('password_confirmation')
                        <p id="confirm-error" class="text-red-600" style="margin-top: 0.375rem; font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- tombol submit karamel --}}
                <button
                    type="submit"
                    style="width: 100%; padding: 0.875rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; letter-spacing: 0.05em; background: #C8813B; color: #1C0F0A; border: none; cursor: pointer; transition: background 0.2s, transform 0.1s;"
                    onmouseover="this.style.background='#D99045'"
                    onmouseout="this.style.background='#C8813B'"
                    onmousedown="this.style.transform='scale(0.98)'"
                    onmouseup="this.style.transform='scale(1)'"
                >
                    BUAT AKUN
                </button>

            </form>

            {{-- divider + link ke login --}}
            <div style="display: flex; align-items: center; gap: 1rem; margin: 1.25rem 0;">
                <div style="flex: 1; height: 1px; background: #D4C4AE;"></div>
                <span style="font-size: 0.75rem; font-weight: 500; color: #A08060;">sudah punya akun?</span>
                <div style="flex: 1; height: 1px; background: #D4C4AE;"></div>
            </div>

            <p style="text-align: center; font-size: 0.875rem; color: #A08060;">
                <a
                    href="{{ route('login') }}"
                    style="font-weight: 600; color: #1C0F0A; text-decoration: underline; text-underline-offset: 2px; transition: color 0.2s;"
                    onmouseover="this.style.color='#C8813B'"
                    onmouseout="this.style.color='#1C0F0A'"
                >
                    Kembali ke Halaman Login
                </a>
            </p>

        </div>
    </div>

</div>

<script>
/* toggle visibilitas password: mengontrol #password dan #password-confirm sekaligus */
(function () {
    var btn     = document.getElementById("toggle-pwd");
    var pwdA    = document.getElementById("password");
    var pwdB    = document.getElementById("password-confirm");
    var eyeOpen = document.getElementById("eye-open");
    var eyeOff  = document.getElementById("eye-off");
    if (!btn || !pwdA) return;
    var visible = false;
    btn.addEventListener("click", function () {
        visible = !visible;
        var t = visible ? "text" : "password";
        pwdA.type = t;
        if (pwdB) pwdB.type = t;
        eyeOpen.style.display = visible ? "none" : "block";
        eyeOff.style.display  = visible ? "block" : "none";
        btn.setAttribute("aria-pressed", String(visible));
        btn.setAttribute("aria-label", visible ? "Sembunyikan password" : "Tampilkan password");
    });
}());
</script>
</body>
</html>