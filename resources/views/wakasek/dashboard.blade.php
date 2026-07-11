@extends('wakasek.layout')

@section('title', 'Dashboard Wakasek')

@section('content')
<div class="space-y-8">
    
    <!-- Header/Overview Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-zinc-100 tracking-tight">Overview Kehadiran Guru</h2>
            <p class="text-xs text-zinc-400 mt-1">Laporan ringkasan absensi harian dan bulanan secara real-time.</p>
        </div>
        <div class="bg-zinc-900 border border-zinc-800 px-4 py-2 rounded-xl text-xs text-zinc-300 shadow-sm">
            Bulan: <span class="font-bold text-zinc-100">{{ date('F Y') }}</span>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Guru -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-sm">
            <span class="text-xs text-zinc-400 font-medium block">Total Guru Aktif</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-zinc-100">{{ $totalTeachers }}</span>
                <span class="text-[10px] text-zinc-500">Orang</span>
            </div>
        </div>

        <!-- Hadir -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-sm">
            <span class="text-xs text-zinc-400 font-medium block">Hadir (Bulan Ini)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-emerald-600">{{ $counts['hadir'] }}</span>
                <span class="text-xs text-zinc-500 font-medium">({{ $percentages['hadir'] }}%)</span>
            </div>
        </div>

        <!-- Terlambat -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-sm">
            <span class="text-xs text-zinc-400 font-medium block">Terlambat (Bulan Ini)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-amber-600">{{ $counts['terlambat'] }}</span>
                <span class="text-xs text-zinc-500 font-medium">({{ $percentages['terlambat'] }}%)</span>
            </div>
        </div>

        <!-- Izin/Sakit -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-sm">
            <span class="text-xs text-zinc-400 font-medium block">Izin & Sakit (Bulan Ini)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-indigo-600">{{ $counts['izin'] + $counts['sakit'] }}</span>
                <span class="text-xs text-zinc-500 font-medium">({{ round($percentages['izin'] + $percentages['sakit'], 1) }}%)</span>
            </div>
        </div>

        <!-- Alfa -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-sm">
            <span class="text-xs text-zinc-400 font-medium block">Alfa (Bulan Ini)</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-extrabold text-rose-600">{{ $counts['alfa'] }}</span>
                <span class="text-xs text-zinc-500 font-medium">({{ $percentages['alfa'] }}%)</span>
            </div>
        </div>
    </div>

    <!-- Detailed Charts & Daily Log Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Persentase Kehadiran (Visual Bars) -->
        <div class="lg:col-span-1 bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-zinc-100 mb-6">Persentase Kehadiran</h3>
                
                <div class="space-y-5">
                    <!-- Hadir -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Hadir Tepat Waktu</span>
                            <span class="text-emerald-600">{{ $percentages['hadir'] }}%</span>
                        </div>
                        <div class="w-full bg-zinc-950 h-2.5 rounded-full overflow-hidden border border-zinc-800">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $percentages['hadir'] }}%"></div>
                        </div>
                    </div>

                    <!-- Terlambat -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Terlambat</span>
                            <span class="text-amber-500 h-full rounded-full" style="width: {{ $percentages['terlambat'] }}%"></div>
                        </div>
                    </div>

                    <!-- Izin -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Izin</span>
                            <span class="text-indigo-600">{{ $percentages['izin'] }}%</span>
                        </div>
                        <div class="w-full bg-zinc-950 h-2.5 rounded-full overflow-hidden border border-zinc-800">
                            <div class="bg-indigo-500 h-full rounded-full" style="width: {{ $percentages['izin'] }}%"></div>
                        </div>
                    </div>

                    <!-- Sakit -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Sakit</span>
                            <span class="text-rose-600">{{ $percentages['sakit'] }}%</span>
                        </div>
                        <div class="w-full bg-zinc-950 h-2.5 rounded-full overflow-hidden border border-zinc-800">
                            <div class="bg-rose-500 h-full rounded-full" style="width: {{ $percentages['sakit'] }}%"></div>
                        </div>
                    </div>

                    <!-- Alfa -->
                    <div>
                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                            <span class="text-zinc-300">Alfa / Tanpa Keterangan</span>
                            <span class="text-rose-700">{{ $percentages['alfa'] }}%</span>
                        </div>
                        <div class="w-full bg-zinc-950 h-2.5 rounded-full overflow-hidden border border-zinc-800">
                            <div class="bg-rose-600 h-full rounded-full" style="width: {{ $percentages['alfa'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-zinc-800 text-[10px] text-zinc-500">
                Data di atas dihitung berdasarkan seluruh log absensi pada bulan ini.
            </div>
        </div>

        <!-- Kehadiran Hari Ini (Log) -->
        <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-zinc-100 mb-4">Aktivitas Scan Absensi Hari Ini</h3>
            
            <div class="overflow-y-auto max-h-[300px]">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-800 text-zinc-400 font-semibold uppercase tracking-wider">
                            <th class="py-2.5 px-3">Nama Guru</th>
                            <th class="py-2.5 px-3">Jam Masuk</th>
                            <th class="py-2.5 px-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse ($todayAttendances as $row)
                            <tr class="hover:bg-zinc-800">
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-zinc-200">{{ $row->teacher->name }}</div>
                                    <div class="text-[10px] text-zinc-500 mt-0.5">NIDN: {{ $row->teacher->nidn }}</div>
                                </td>
                                <td class="py-3 px-3 text-zinc-300">
                                    {{ $row->check_in ? date('H:i:s', strtotime($row->check_in)) : '-' }} WIB
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if ($row->status == 'hadir')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-emerald-50 border border-emerald-200 text-emerald-700 rounded">
                                            Hadir
                                        </span>
                                    @elseif ($row->status == 'terlambat')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-amber-50 border border-amber-200 text-amber-700 rounded">
                                            Terlambat
                                        </span>
                                    @elseif ($row->status == 'izin')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-indigo-50 border border-indigo-200 text-indigo-700 rounded">
                                            Izin
                                        </span>
                                    @elseif ($row->status == 'sakit')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-rose-50 border border-rose-200 text-rose-700 rounded">
                                            Sakit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold bg-zinc-50 border border-zinc-200 text-zinc-700 rounded">
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
