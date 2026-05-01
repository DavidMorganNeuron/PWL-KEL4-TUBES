<!DOCTYPE html>
<html>
<head>
    <title>Pod's - Daftar Akun</title>
</head>
<body style="font-family: sans-serif; padding: 20px;">

    <h2>Daftar Akun Pelanggan Pod's</h2>

    {{-- pesan error jika validasi pendaftaran gagal --}}
    @if($errors->any())
        <div style="color: red; margin-bottom: 10px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf

        <div style="margin-bottom: 10px;">
            <label>Nama Lengkap (Sesuai KTP/Resmi):</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Ulangi Password:</label><br>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit">Daftar</button>
    </form>

    <p style="margin-top: 20px;">
        Sudah punya akun? <a href="/login">Kembali ke halaman Login</a>
    </p>

</body>
</html>