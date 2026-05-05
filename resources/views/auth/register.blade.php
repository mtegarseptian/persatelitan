<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Persatelitan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-orbitron { font-family: 'Orbitron', monospace; }
        .stars { background: radial-gradient(ellipse at bottom, #1B2735 0%, #090A0F 100%); }
        .input-field {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.15);
            transition: all 0.3s;
        }
        .input-field:focus {
            background: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
            outline: none;
        }
    </style>
</head>
<body class="stars min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 items-center justify-center mb-4 shadow-2xl">
                <i class="fas fa-satellite text-white text-3xl"></i>
            </div>
            <h1 class="font-orbitron font-black text-3xl text-white tracking-wider">PERSATELITAN</h1>
            <p class="text-gray-400 mt-1">Buat Akun Baru</p>
        </div>

        <div class="rounded-2xl border border-white/10 p-8"
             style="background: rgba(13, 18, 36, 0.9); backdrop-filter: blur(20px);">

            <h2 class="text-xl font-semibold text-white mb-6">Registrasi</h2>

            @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
                @foreach($errors->all() as $error)
                <p><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-white text-sm"
                               placeholder="Nama Anda">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-white text-sm"
                               placeholder="your@email.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="password" name="password" required
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-white text-sm"
                               placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="password" name="password_confirmation" required
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-white text-sm"
                               placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-[1.02] shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium">Masuk di sini</a>
            </p>
        </div>
    </div>
</body>
</html>