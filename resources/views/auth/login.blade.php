<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Persatelitan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-orbitron { font-family: 'Orbitron', monospace; }
        .stars {
            background: radial-gradient(ellipse at bottom, #1B2735 0%, #090A0F 100%);
            overflow: hidden;
        }
        .input-field {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.15);
            transition: all 0.3s;
        }
        .input-field:focus {
            background: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="stars min-h-screen flex items-center justify-center p-4">

    {{-- Animated background particles --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        @for($i = 0; $i < 50; $i++)
        <div class="absolute w-1 h-1 bg-white rounded-full opacity-30"
             style="top: {{ rand(0,100) }}%; left: {{ rand(0,100) }}%; animation: twinkle {{ rand(2,6) }}s infinite {{ rand(0,4) }}s;">
        </div>
        @endfor
    </div>

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 items-center justify-center mb-4 shadow-2xl" style="box-shadow: 0 0 40px rgba(59, 130, 246, 0.4);">
                <i class="fas fa-satellite text-white text-3xl"></i>
            </div>
            <h1 class="font-orbitron font-black text-3xl text-white tracking-wider">PERSATELITAN</h1>
            <p class="text-gray-400 mt-1">Sistem Manajemen Satelit</p>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl border border-white/10 p-8"
             style="background: rgba(13, 18, 36, 0.9); backdrop-filter: blur(20px);">

            <h2 class="text-xl font-semibold text-white mb-6">Masuk ke Akun</h2>

            {{-- Errors --}}
            @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
                @foreach($errors->all() as $error)
                <p><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

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
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded">
                        Ingat saya
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-[1.02] shadow-lg"
                        style="box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium">Daftar sekarang</a>
            </p>
        </div>
    </div>

    <style>
        @keyframes twinkle {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.5); }
        }
    </style>
</body>
</html>