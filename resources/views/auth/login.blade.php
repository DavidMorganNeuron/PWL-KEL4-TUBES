<!DOCTYPE html>
<html>
<head>
    <title>Pod's - Login</title>
</head>
<body style="font-family: sans-serif; padding: 20px;">

    <h2>Masuk ke Pod's</h2>

    {{-- pesan success --}}
    @if(session('success'))
        <div style="color: green; margin-bottom: 10px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    {{-- pesan error --}}
    @if($errors->any())
        <div style="color: red; margin-bottom: 10px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div style="margin-bottom: 10px;">
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>

    <p style="margin-top: 20px;">
        Belum punya akun? <a href="/register">Daftar di sini</a>
    </p>

</body>
</html>