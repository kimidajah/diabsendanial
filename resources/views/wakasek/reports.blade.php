@extends('wakasek.layout')

@section('title', 'Rekap Laporan Absensi')

@section('content')
<div class="space-y-6">
    <!-- Filter Card -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-zinc-100 mb-4">Filter Rekapitulasi Absensi</h3>
        
        <form action="{{ route('wakasek.reports') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <!-- Filter Guru -->
            <div>
                <label for="teacher_id" class="block text-xs font-medium text-zinc-300 mb-2">Pilih Guru</label>
                <select name="teacher_id" id="teacher_id" 
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Semua Guru</option>
                    @foreach ($teachers as $t)
                        <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tanggal Mulai -->
            <div>
                <label for="start_date" class="block text-xs font-medium text-zinc-300 mb-2">Mulai Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Filter Tanggal Selesai -->
            <div>
                <label for="end_date" class="block text-xs font-medium text-zinc-300 mb-2">Selesai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2.5">
                <button type="submit" 
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs py-3 px-4 rounded-xl shadow-sm">
                    Filter
                </button>
                <a href="{{ route('wakasek.reports') }}" 
                    class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-medium text-xs py-3 px-4 rounded-xl border border-zinc-700 flex justify-center items-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Reports Table Card -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-base font-bold text-zinc-100">Data Log Absensi Keseluruhan</h3>
                <p class="text-xs text-zinc-400 mt-0.5">Daftar kehadiran seluruh guru beserta status dan waktu pemindaian.</p>
            </div>
            
            <!-- Export Button (Points to shared tu.export controller action) -->
            <a href="{{ route('tu.export', request()->query()) }}" 
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs py-2.5 px-4 rounded-xl shadow-sm flex items-center gap-1.5 self-start sm:self-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Ekspor Laporan (Excel)
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-zinc-800 text-zinc-400 font-medium text-xs uppercase tracking-wider">
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Nama Guru / NIDN</th>
                        <th class="py-3.5 px-4">Jam Scan</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Operator Scan</th>
                        <th class="py-3.5 px-4">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse ($attendances as $row)
                        <tr class="hover:bg-zinc-800">
                            <td class="py-4 px-4 font-medium text-zinc-250">
                                {{ $row->date->isoFormat('D MMM Y') }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-medium text-zinc-250">{{ $row->teacher->name }}</div>
                                <div class="text-[10px] text-zinc-500 mt-0.5">NIDN: {{ $row->teacher->nidn }}</div>
                            </td>
                            <td class="py-4 px-4 text-zinc-300">
                                {{ $row->check_in ? date('H:i:s', strtotime($row->check_in)) : '-' }}
                            </td>
                            <td class="py-4 px-4">
                                @if ($row->status == 'hadir')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full">
                                        Hadir
                                    </span>
                                @elseif ($row->status == 'terlambat')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-amber-50 border border-amber-200 text-amber-700 rounded-full">
                                        Terlambat
                                    </span>
                                @elseif ($row->status == 'izin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-full">
                                        Izin
                                    </span>
                                @elseif ($row->status == 'sakit')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-rose-50 border border-rose-200 text-rose-700 rounded-full">
                                        Sakit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-zinc-50 border border-zinc-200 text-zinc-700 rounded-full">
                                        Alfa
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-zinc-400 text-xs">
                                {{ $row->scanner->name ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-zinc-500 text-xs truncate max-w-xs" title="{{ $row->notes }}">
                                {{ $row->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-zinc-500 text-sm">
                                Tidak ada data absensi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
