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
<body class="bg-zinc-950 text-zinc-100 min-h-screen flex items-center justify-center p-4 relative">
    <div class="w-full max-w-md relative z-10">
        <!-- Logo / Branding -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-indigo-600 inline-flex items-center gap-2">
                diabsen<span class="text-indigo-500 font-black">++</span>
            </h1>
            <p class="text-zinc-400 mt-2 text-sm">Sistem Absensi Guru Berbasis QR Code & Cloud</p>
        </div>

        <!-- Login Card -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-zinc-100 mb-2">Selamat Datang</h2>
            <p class="text-xs text-zinc-400 mb-6">
                Silakan masukkan <span class="text-indigo-600 font-semibold">NIDN</span> (untuk Guru) atau <span class="text-indigo-600 font-semibold">Username</span> (untuk Staf TU / Wakasek) untuk masuk.
            </p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 text-xs rounded-lg p-3.5 mb-6 flex flex-col gap-1">
                    @foreach ($errors->all() as $error)
                        <span class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            {{ $error }}
                        </span>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Input Login (NIDN/Username) -->
                <div>
                    <label for="login_input" class="block text-xs font-medium text-zinc-300 mb-2">NIDN / Username</label>
                    <div class="relative">
                        <input type="text" name="login_input" id="login_input" required 
                            value="{{ old('login_input') }}"
                            placeholder="Contoh: 19820315... atau tu" 
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded bg-zinc-950 border-zinc-800 text-indigo-600 focus:ring-indigo-500/20">
                        <span class="text-xs text-zinc-400">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-xl text-sm shadow-sm transition-colors">
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
