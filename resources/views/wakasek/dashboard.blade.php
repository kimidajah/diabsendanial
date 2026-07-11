@extends('wakasek.layout')

@section('title', 'Dashboard Wakasek')

@section('content')
<div class="space-y-8">
    
    <!-- Header/Overview Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Overview Kehadiran Guru</h2>
            <p class="text-xs text-zinc-400 mt-1">Laporan ringkasan absensi harian dan bulanan secara real-time.</p>
        </div>
        <div class="bg-zinc-900 border border-zinc-800 px-4 py-2 rounded-xl text-xs text-zinc-300">
            Bulan: <span class="font-bold text-white">{{ date('F Y') }}</span>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Guru -->
        <div class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-5 hover:border-zinc-700/50 transition duration-300">
            <span class="text-xs text-zinc-400 font-medium block">Total Guru Aktif</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-white">{{ $totalTeachers }}</span>
                <span class="text-[10px] text-zinc-500">Orang</span>
            </div>
        </div>

        <!-- Hadir -->
        <div class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-5 hover:border-zinc-700/50 transition duration-300">
            <span class="text-xs text-zinc-400 font-medium block">Hadir (Bulan Ini)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-emerald-400">{{ $counts['hadir'] }}</span>
                <span class="text-xs text-zinc-500 font-medium">({{ $percentages['hadir'] }}%)</span>
            </div>
        </div>

        <!-- Terlambat -->
        <div class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-5 hover:border-zinc-700/50 transition duration-300">
            <span class="text-xs text-zinc-400 font-medium block">Terlambat (Bulan Ini)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-amber-400">{{ $counts['terlambat'] }}</span>
                <span class="text-xs text-zinc-500 font-medium">({{ $percentages['terlambat'] }}%)</span>
            </div>
        </div>

        <!-- Izin/Sakit -->
        <div class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-5 hover:border-zinc-700/50 transition duration-300">
            <span class="text-xs text-zinc-400 font-medium block">Izin & Sakit (Bulan Ini)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-indigo-400">{{ $counts['izin'] + $counts['sakit'] }}</span>
                <span class="text-xs text-zinc-500 font-medium">({{ round($percentages['izin'] + $percentages['sakit'], 1) }}%)</span>
            </div>
        </div>

        <!-- Alfa -->
        <div class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-5 hover:border-zinc-700/50 transition duration-300">
            <span class="text-xs text-zinc-400 font-medium block">Alfa (Bulan Ini)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-rose-500">{{ $counts['alfa'] }}</span>
                <span class="text-xs text-zinc-500 font-medium">({{ $percentages['alfa'] }}%)</span>
            </div>
        </div>
    </div>

    <!-- Detailed Charts & Daily Log Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Persentase Kehadiran (Visual Bars) -->
        <div class="lg:col-span-1 bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 hover:border-zinc-700/50 transition duration-300 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-white mb-6">Persentase Kehadiran</h3>
                
                <div class="space-y-5">
                    <!-- Hadir -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Hadir Tepat Waktu</span>
                            <span class="text-emerald-400">{{ $percentages['hadir'] }}%</span>
                        </div>
                        <div class="w-full bg-zinc-950 h-2.5 rounded-full overflow-hidden border border-zinc-900">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-full rounded-full" style="width: {{ $percentages['hadir'] }}%"></div>
                        </div>
                    </div>

                    <!-- Terlambat -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Terlambat</span>
                            <span class="text-amber-400">{{ $percentages['terlambat'] }}%</span>
                        </div>
                        <div class="w-full bg-zinc-950 h-2.5 rounded-full overflow-hidden border border-zinc-900">
                            <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-full rounded-full" style="width: {{ $percentages['terlambat'] }}%"></div>
                        </div>
                    </div>

                    <!-- Izin -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Izin</span>
                            <span class="text-indigo-400">{{ $percentages['izin'] }}%</span>
                        </div>
                        <div class="w-full bg-zinc-950 h-2.5 rounded-full overflow-hidden border border-zinc-900">
                            <div class="bg-gradient-to-r from-indigo-500 to-violet-500 h-full rounded-full" style="width: {{ $percentages['izin'] }}%"></div>
                        </div>
                    </div>

                    <!-- Sakit -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Sakit</span>
                            <span class="text-rose-400">{{ $percentages['sakit'] }}%</span>
                        </div>
                        <div class="w-full bg-zinc-950 h-2.5 rounded-full overflow-hidden border border-zinc-900">
                            <div class="bg-gradient-to-r from-rose-500 to-pink-500 h-full rounded-full" style="width: {{ $percentages['sakit'] }}%"></div>
                        </div>
                    </div>

                    <!-- Alfa -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Alfa / Tanpa Keterangan</span>
                            <span class="text-rose-600">{{ $percentages['alfa'] }}%</span>
                        </div>
                        <div class="w-full bg-zinc-950 h-2.5 rounded-full overflow-hidden border border-zinc-900">
                            <div class="bg-gradient-to-r from-rose-700 to-rose-600 h-full rounded-full" style="width: {{ $percentages['alfa'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-zinc-800/60 text-[10px] text-zinc-500">
                Data di atas dihitung berdasarkan seluruh log absensi pada bulan ini.
            </div>
        </div>

        <!-- Kehadiran Hari Ini (Log) -->
        <div class="lg:col-span-2 bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 hover:border-zinc-700/50 transition duration-300">
            <h3 class="text-sm font-bold text-white mb-4">Aktivitas Scan Absensi Hari Ini</h3>
            
            <div class="overflow-y-auto max-h-[300px]">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-850 text-zinc-400 font-semibold uppercase tracking-wider">
                            <th class="py-2.5 px-3">Nama Guru</th>
                            <th class="py-2.5 px-3">Jam Masuk</th>
                            <th class="py-2.5 px-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-850/40">
                        @forelse ($todayAttendances as $row)
                            <tr class="hover:bg-zinc-800/10 transition duration-150">
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-zinc-200">{{ $row->teacher->name }}</div>
                                    <div class="text-[10px] text-zinc-500 mt-0.5">NIDN: {{ $row->teacher->nidn }}</div>
                                </td>
                                <td class="py-3 px-3 text-zinc-300">
                                    {{ $row->check_in ? date('H:i:s', strtotime($row->check_in)) : '-' }} WIB
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if ($row->status == 'hadir')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded">
                                            Hadir
                                        </span>
                                    @elseif ($row->status == 'terlambat')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded">
                                            Terlambat
                                        </span>
                                    @elseif ($row->status == 'izin')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded">
                                            Izin
                                        </span>
                                    @elseif ($row->status == 'sakit')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded">
                                            Sakit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-zinc-800 border border-zinc-700 text-zinc-400 rounded">
                                            Alfa
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-zinc-500">
                                    Belum ada guru yang melakukan scan hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
