<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - diabsen++</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Glow Patterns -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600 rounded-full blur-3xl opacity-20 animate-pulse"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-violet-600 rounded-full blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo / Branding -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400 bg-clip-text text-transparent inline-flex items-center gap-2">
                diabsen<span class="text-indigo-500 font-black">++</span>
            </h1>
            <p class="text-zinc-400 mt-2 text-sm">Sistem Absensi Guru Berbasis QR Code & Cloud</p>
        </div>

        <!-- Login Card -->
        <div class="bg-zinc-900/60 border border-zinc-800/80 backdrop-blur-xl rounded-2xl p-8 shadow-2xl shadow-zinc-950/50 transition-all duration-300 hover:border-zinc-700/50">
            <h2 class="text-xl font-semibold text-white mb-2">Selamat Datang</h2>
            <p class="text-xs text-zinc-400 mb-6">
                Guru masuk menggunakan <span class="text-indigo-400 font-semibold">NIDN</span>. Staf Tata Usaha & Wakasek menggunakan <span class="text-indigo-400 font-semibold">Email / Username</span>.
            </p>

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg p-3.5 mb-6 flex flex-col gap-1">
                    @foreach ($errors->all() as $error)
                        <span class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                            {{ $error }}
                        </span>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Input Login (NIDN/Email/Username) -->
                <div>
                    <label for="login_input" class="block text-xs font-medium text-zinc-300 mb-2">NIDN / Email / Username</label>
                    <div class="relative">
                        <input type="text" name="login_input" id="login_input" required 
                            value="{{ old('login_input') }}"
                            placeholder="Contoh: 19820315... atau tu@diabsen.com" 
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-xs font-medium text-zinc-300 mb-2">Password</label>
                    <input type="password" name="password" id="password" required 
                        placeholder="••••••••" 
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded bg-zinc-950 border-zinc-800 text-indigo-600 focus:ring-indigo-500/20 focus:ring-offset-zinc-900">
                        <span class="text-xs text-zinc-400">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-medium py-3 px-4 rounded-xl text-sm transition-all duration-300 shadow-lg shadow-indigo-600/20 active:scale-[0.98]">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-zinc-600 mt-8">
            &copy; 2026 diabsen++ Team. Hak Cipta Dilindungi Undang-Undang.
        </p>
    </div>
</body>
</html>
