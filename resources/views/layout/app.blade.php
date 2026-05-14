<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "Pod's — Where Every Cup Tells a Story")</title>

    {{-- google fonts: playfair display (serif) + dm sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- sweetalert2 cdn — di head agar SwalModal tersedia saat navbar script berjalan --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* ================================================================
           DESIGN GLOBAL — satu sumber untuk seluruh proyek
        ================================================================ */
        :root {
            --font-serif:    'Playfair Display', Georgia, serif;
            --font-sans:     'DM Sans', system-ui, sans-serif;
            --pods-espresso: #1C0F0A;
            --pods-brown:    #3D1F0F;
            --pods-caramel:  #C8813B;
            --pods-cream:    #F5E9D3;
            --pods-offwhite: #FBF6EE;
            --pods-muted:    #A08060;
            --navbar-h:      72px;
            --layout-w:      1280px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html {
            min-width: var(--layout-w);
            scroll-behavior: smooth;
        }
        body {
            font-family: var(--font-sans);
            background-color: var(--pods-offwhite);
            color: var(--pods-espresso);
            min-width: var(--layout-w);
            overflow-x: auto;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, .font-serif { font-family: var(--font-serif); }

        /* focus ring global: karamel, konsisten di seluruh site */
        *:focus-visible { outline: 2px solid #C8813B; outline-offset: 3px; border-radius: 4px; }

        /* ================================================================
           KOMPONEN SHARED: pods-input
           didefinisikan di sini agar tidak duplikat di setiap halaman.
        ================================================================ */
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
        .pods-input.is-error {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
        }
        .pods-input.is-error:focus {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.18);
        }

        .sr-only {
            position: absolute; width: 1px; height: 1px;
            padding: 0; margin: -1px; overflow: hidden;
            clip: rect(0,0,0,0); white-space: nowrap; border-width: 0;
        }
    </style>

    @stack('head-scripts')
</head>
<body>

    {{-- NAVBAR: fixed solid, di-include sebelum main --}}
    @include('layout.navbar')

    {{-- MAIN CONTENT WRAPPER --}}
    <main style="flex: 1; padding-top: var(--navbar-h);">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('layout.footer')

    {{-- ================================================================
         VARIASI NOTIFIKASI
         SwalModal  — aksi kritikal (konfirmasi, pembayaran berhasil)
         SwalToast  — feedback non-blocking (sukses CRUD)
         Inline @error — validasi form (di blade masing-masing, bukan di sini)
    ================================================================ --}}
    <script>
        /* toast global — dipanggil via window.SwalToast.fire() */
        window.SwalToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            background: '#FBF6EE',
            color: '#1C0F0A',
            didOpen: function (toast) {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
        });

        /* modal global — dipanggil via window.SwalModal.fire() */
        window.SwalModal = Swal.mixin({
            confirmButtonColor: '#C8813B',
            background: '#FBF6EE',
            color: '#1C0F0A',
        });
    </script>

    @if (session('toast'))
    <script>
        window.SwalToast.fire({
            icon:  'success',
            title: {{ json_encode(session('toast')) }},
        });
    </script>
    @endif

    @if (session('success'))
    <script>
        window.SwalModal.fire({
            icon:  'success',
            title: 'Berhasil!',
            text:  {{ json_encode(session('success')) }},
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        window.SwalModal.fire({
            icon:  'error',
            title: 'Terjadi Kesalahan',
            text:  {{ json_encode(session('error')) }},
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>