@extends('guru.layout')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-bold text-zinc-100">Riwayat Kehadiran</h3>
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
            <tbody class="divide-y divide-zinc-800">
                @forelse ($attendances as $attendance)
                    <tr class="hover:bg-zinc-800">
                        <td class="py-4 px-4 font-medium text-zinc-250">
                            {{ $attendance->date->isoFormat('dddd, D MMMM Y') }}
                        </td>
                        <td class="py-4 px-4 text-zinc-400">
                            {{ $attendance->check_in ? date('H:i:s', strtotime($attendance->check_in)) : '-' }} WIB
                        </td>
                        <td class="py-4 px-4">
                            @if ($attendance->status == 'hadir')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full">
                                    Hadir
                                </span>
                            @elseif ($attendance->status == 'terlambat')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-amber-50 border border-amber-200 text-amber-700 rounded-full">
                                    Terlambat
                                </span>
                            @elseif ($attendance->status == 'izin')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-full">
                                    Izin
                                </span>
                            @elseif ($attendance->status == 'sakit')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-rose-50 border border-rose-200 text-rose-700 rounded-full">
                                    Sakit
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-zinc-50 border border-zinc-200 text-zinc-700 rounded-full">
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
