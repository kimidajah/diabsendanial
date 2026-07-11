<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guru Dashboard') - diabsen++</title>
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
    @yield('styles')
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen flex flex-col justify-between">
    <!-- Navbar -->
    <header class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Branding -->
                <div class="flex items-center">
                    <a href="{{ route('guru.dashboard') }}" class="text-2xl font-bold tracking-tight text-indigo-600">
                        diabsen<span class="text-indigo-500 font-extrabold">++</span>
                    </a>
                    <span class="ml-2.5 px-2 py-0.5 text-[10px] font-semibold bg-zinc-800 text-zinc-300 rounded border border-zinc-700">Guru</span>
                </div>

                <!-- Navigation Desktop -->
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('guru.dashboard') }}" 
                        class="text-sm font-medium {{ request()->routeIs('guru.dashboard') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-zinc-400 hover:text-zinc-200' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('guru.history') }}" 
                        class="text-sm font-medium {{ request()->routeIs('guru.history') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-zinc-400 hover:text-zinc-200' }}">
                        Riwayat Absen
                    </a>
                    <a href="{{ route('guru.leave') }}" 
                        class="text-sm font-medium {{ request()->routeIs('guru.leave') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-zinc-400 hover:text-zinc-200' }}">
                        Ajukan Izin
                    </a>
                </nav>

                <!-- Profile & Logout -->
                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-semibold text-zinc-200">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-zinc-500">NIDN: {{ auth()->user()->username }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 p-2 rounded-xl text-xs border border-zinc-700 flex items-center gap-1.5">
                            <span class="hidden sm:inline">Keluar</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl p-4 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl p-4 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Mobile Navigation Bottom Bar -->
    <div class="md:hidden bg-zinc-900 border-t border-zinc-800 sticky bottom-0 z-50 px-4 py-2 flex justify-around items-center">
        <a href="{{ route('guru.dashboard') }}" 
            class="flex flex-col items-center justify-center py-1 {{ request()->routeIs('guru.dashboard') ? 'text-indigo-600' : 'text-zinc-400 hover:text-zinc-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z" />
            </svg>
            <span class="text-[9px] mt-1 font-medium">Dashboard</span>
        </a>
        <a href="{{ route('guru.history') }}" 
            class="flex flex-col items-center justify-center py-1 {{ request()->routeIs('guru.history') ? 'text-indigo-600' : 'text-zinc-400 hover:text-zinc-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-[9px] mt-1 font-medium">Riwayat</span>
        </a>
        <a href="{{ route('guru.leave') }}" 
            class="flex flex-col items-center justify-center py-1 {{ request()->routeIs('guru.leave') ? 'text-indigo-600' : 'text-zinc-400 hover:text-zinc-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span class="text-[9px] mt-1 font-medium">Izin</span>
        </a>
    </div>

    <!-- Footer -->
    <footer class="bg-zinc-950 border-t border-zinc-900 py-4 text-center text-xs text-zinc-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            &copy; 2026 diabsen++. Hak Cipta Dilindungi.
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
