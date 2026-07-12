<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wakasek Dashboard') - diabsen++</title>
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
                    <a href="{{ route('wakasek.dashboard') }}" class="text-2xl font-bold tracking-tight text-indigo-600">
                        diabsen<span class="text-indigo-500 font-extrabold">++</span>
                    </a>
                    <span class="ml-2.5 px-2 py-0.5 text-[10px] font-semibold bg-zinc-800 text-zinc-300 rounded border border-zinc-700">Wakasek</span>
                </div>

                <!-- Navigation Desktop -->
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('wakasek.dashboard') }}" 
                        class="text-sm font-medium {{ request()->routeIs('wakasek.dashboard') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-zinc-400 hover:text-zinc-200' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('wakasek.teachers') }}" 
                        class="text-sm font-medium {{ request()->routeIs('wakasek.teachers*') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-zinc-400 hover:text-zinc-200' }}">
                        Kelola Guru
                    </a>
                    <a href="{{ route('wakasek.leaves') }}" 
                        class="text-sm font-medium {{ request()->routeIs('wakasek.leaves*') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-zinc-400 hover:text-zinc-200' }}">
                        Persetujuan Izin
                    </a>
                    <a href="{{ route('wakasek.reports') }}" 
                        class="text-sm font-medium {{ request()->routeIs('wakasek.reports*') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-zinc-400 hover:text-zinc-200' }}">
                        Laporan
                    </a>
                    <a href="{{ route('wakasek.settings') }}" 
                        class="text-sm font-medium {{ request()->routeIs('wakasek.settings*') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-zinc-400 hover:text-zinc-200' }}">
                        Pengaturan
                    </a>
                </nav>

                <!-- Profile & Logout -->
                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-semibold text-zinc-200">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-zinc-500">Wakasek Kurikulum</p>
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
    <div class="md:hidden bg-zinc-900 border-t border-zinc-800 sticky bottom-0 z-50 px-2 py-2 flex justify-around items-center">
        <a href="{{ route('wakasek.dashboard') }}" 
            class="flex flex-col items-center justify-center py-1 {{ request()->routeIs('wakasek.dashboard') ? 'text-indigo-600' : 'text-zinc-400 hover:text-zinc-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span class="text-[9px] mt-1 font-medium">Dashboard</span>
        </a>
        <a href="{{ route('wakasek.teachers') }}" 
            class="flex flex-col items-center justify-center py-1 {{ request()->routeIs('wakasek.teachers*') ? 'text-indigo-600' : 'text-zinc-400 hover:text-zinc-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A2.25 2.25 0 0112.75 21.5h-1.5a2.25 2.25 0 01-2.25-2.263V19.13m4.5 0c.086 0 .172-.007.258-.022M9.252 19.128a9.38 9.38 0 01-2.625.372 9.337 9.337 0 01-4.121-.952 4.125 4.125 0 017.533-2.493M9.252 19.128v-.003c0-1.113.285-2.16.786-3.07M9.252 19.128v.109A2.25 2.25 0 017 21.5h-1.5a2.25 2.25 0 01-2.263V19.13M12.75 14.25c.086 0 .172-.007.258-.022M9 7.5a3 3 0 116 0 3 3 0 01-6 0zm6 2.25a3 3 0 116 0 3 3 0 01-6 0zm-12 0a3 3 0 116 0 3 3 0 01-6 0z" />
            </svg>
            <span class="text-[9px] mt-1 font-medium">Guru</span>
        </a>
        <a href="{{ route('wakasek.leaves') }}" 
            class="flex flex-col items-center justify-center py-1 {{ request()->routeIs('wakasek.leaves*') ? 'text-indigo-600' : 'text-zinc-400 hover:text-zinc-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.375M9 18h3.375m1.875-12h7.5c.621 0 1.125.504 1.125 1.125v17.25c0 .621-.504 1.125-1.125 1.125h-11.25a9 9 0 01-9-9v-9c0-.621.504-1.125 1.125-1.125h5.625M9 3.75h.008v.008H9V3.75z" />
            </svg>
            <span class="text-[9px] mt-1 font-medium">Persetujuan</span>
        </a>
        <a href="{{ route('wakasek.reports') }}" 
            class="flex flex-col items-center justify-center py-1 {{ request()->routeIs('wakasek.reports*') ? 'text-indigo-600' : 'text-zinc-400 hover:text-zinc-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span class="text-[9px] mt-1 font-medium">Laporan</span>
        </a>
        <a href="{{ route('wakasek.settings') }}" 
            class="flex flex-col items-center justify-center py-1 {{ request()->routeIs('wakasek.settings*') ? 'text-indigo-600' : 'text-zinc-400 hover:text-zinc-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 01-6 0z" />
            </svg>
            <span class="text-[9px] mt-1 font-medium">Pengaturan</span>
        </a>
    </div>

    <!-- Footer -->
    <footer class="bg-zinc-950 border-t border-zinc-900 py-4 text-center text-xs text-zinc-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            &copy; 2026 diabsen++. Hak Cipta Dilindungi.
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
