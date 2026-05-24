<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "Dashboard Admin — Pod's")</title>

    {{-- FONTS: playfair display (heading) + dm sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

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
            --sidebar-w:     260px;
            --topbar-h:      64px;
            --layout-w:      1280px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { min-width: var(--layout-w); scroll-behavior: smooth; }

        body {
            font-family: var(--font-sans);
            background-color: #F0E8DC;
            color: var(--pods-espresso);
            min-width: var(--layout-w);
            overflow-x: auto;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, .font-serif { font-family: var(--font-serif); }

        *:focus-visible {
            outline: 2px solid #C8813B;
            outline-offset: 3px;
            border-radius: 4px;
        }

        .adm-shell { display: flex; min-height: 100vh; }

        .adm-sidebar-wrap {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w); z-index: 40;
        }

        .adm-main-area {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex; flex-direction: column; min-height: 100vh;
        }

        .adm-topbar-wrap {
            position: fixed; top: 0;
            left: var(--sidebar-w); right: 0;
            height: var(--topbar-h); z-index: 30;
        }

        .adm-page-content { padding-top: var(--topbar-h); flex: 1; }

        .adm-card {
            background: #FFFDF9;
            border-radius: 12px;
            border: 1px solid #EDE0CC;
            box-shadow: 0 1px 4px rgba(28,15,10,0.06);
        }

        .pods-btn-primary {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.5625rem 1.25rem; border-radius: 8px;
            background: var(--pods-caramel); color: #1C0F0A;
            font-family: var(--font-sans); font-size: 0.875rem; font-weight: 600;
            letter-spacing: 0.02em; border: none; cursor: pointer; text-decoration: none;
            transition: background 0.18s, transform 0.1s, box-shadow 0.18s;
        }
        .pods-btn-primary:hover  { background: #D99045; box-shadow: 0 4px 14px rgba(200,129,59,0.35); }
        .pods-btn-primary:active { transform: scale(0.96); }

        .pods-btn-ghost {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.5rem 1rem; border-radius: 8px; background: transparent;
            color: var(--pods-espresso); font-family: var(--font-sans);
            font-size: 0.875rem; font-weight: 500;
            border: 1.5px solid #D4C4AE; cursor: pointer; text-decoration: none;
            transition: background 0.18s, border-color 0.18s;
        }
        .pods-btn-ghost:hover { background: rgba(200,129,59,0.08); border-color: #C8813B; }

        .pods-btn-danger {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.5rem 1rem; border-radius: 8px;
            background: #FEE2E2; color: #991B1B;
            font-family: var(--font-sans); font-size: 0.875rem; font-weight: 600;
            border: 1.5px solid #FECACA; cursor: pointer;
            transition: background 0.18s, border-color 0.18s;
        }
        .pods-btn-danger:hover { background: #FCA5A5; border-color: #F87171; }

        @keyframes adm-fade-up {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .adm-animate { animation: adm-fade-up 0.35s cubic-bezier(0.22,1,0.36,1) both; }

        .sr-only {
            position: absolute; width: 1px; height: 1px;
            padding: 0; margin: -1px; overflow: hidden;
            clip: rect(0,0,0,0); white-space: nowrap; border-width: 0;
        }
    </style>

    @stack('head-scripts')
</head>
<body>

<div class="adm-shell">

    <aside class="adm-sidebar-wrap" aria-label="Navigasi admin pusat">
        @include('admin.layouts.sidebar')
    </aside>

    <div class="adm-main-area">
        <header class="adm-topbar-wrap" aria-label="Topbar admin">
            @include('admin.layouts.topbar')
        </header>
        <main class="adm-page-content">
            @yield('content')
        </main>
    </div>
</div>

<script>
    window.SwalToast = Swal.mixin({
        toast: true, position: 'top-end',
        showConfirmButton: false, timer: 3500, timerProgressBar: true,
        background: '#FBF6EE', color: '#1C0F0A',
        didOpen: function (t) {
            t.addEventListener('mouseenter', Swal.stopTimer);
            t.addEventListener('mouseleave', Swal.resumeTimer);
        },
    });

    window.SwalModal = Swal.mixin({
        confirmButtonColor: '#C8813B',
        background: '#FBF6EE', color: '#1C0F0A',
    });
</script>

@if (session('toast'))
<script>window.SwalToast.fire({ icon: 'success', title: {{ json_encode(session('toast')) }} });</script>
@endif

@if (session('success'))
<script>window.SwalModal.fire({ icon: 'success', title: 'Berhasil!', text: {{ json_encode(session('success')) }} });</script>
@endif

@if (session('error'))
<script>window.SwalModal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: {{ json_encode(session('error')) }} });</script>
@endif

@stack('scripts')
</body>
</html>