@extends('guru.layout')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 hover:border-zinc-700/50 transition duration-300">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-bold text-white">Riwayat Kehadiran</h3>
            <p class="text-xs text-zinc-400 mt-0.5">Daftar lengkap catatan kehadiran Anda.</p>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-zinc-800 text-zinc-400 font-medium text-xs uppercase tracking-wider">
                    <th class="py-3.5 px-4">Tanggal</th>
                    <th class="py-3.5 px-4">Waktu Scan</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse ($attendances as $attendance)
                    <tr class="hover:bg-zinc-800/20 transition duration-150">
                        <td class="py-4 px-4 font-medium text-zinc-200">
                            {{ $attendance->date->isoFormat('dddd, D MMMM Y') }}
                        </td>
                        <td class="py-4 px-4 text-zinc-400">
                            {{ $attendance->check_in ? date('H:i:s', strtotime($attendance->check_in)) : '-' }} WIB
                        </td>
                        <td class="py-4 px-4">
                            @if ($attendance->status == 'hadir')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full">
                                    Hadir
                                </span>
                            @elseif ($attendance->status == 'terlambat')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-full">
                                    Terlambat
                                </span>
                            @elseif ($attendance->status == 'izin')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-full">
                                    Izin
                                </span>
                            @elseif ($attendance->status == 'sakit')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-full">
                                    Sakit
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-zinc-800 border border-zinc-700 text-zinc-400 rounded-full">
                                    Alfa
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-zinc-400 text-xs truncate max-w-xs">
                            {{ $attendance->notes ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-zinc-500 text-sm">
                            Belum ada riwayat absensi.
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
@endsection
