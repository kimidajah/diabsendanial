@extends('guru.layout')

@section('title', 'Riwayat Kehadiran & Pengajuan')

@section('content')
@php
    $activeTab = request()->has('leave_page') ? 'leave' : 'attendance';
@endphp

<div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm">
    <div class="mb-6">
        <h3 class="text-lg font-bold text-zinc-100">Riwayat Anda</h3>
        <p class="text-xs text-zinc-400 mt-0.5">Catatan lengkap absensi harian dan status pengajuan izin/sakit Anda.</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex border-b border-zinc-800 mb-6">
        <button onclick="switchTab('attendance')" id="tab-attendance" class="py-2.5 px-4 text-sm font-semibold border-b-2 transition-colors duration-150 {{ $activeTab === 'attendance' ? 'border-indigo-600 text-indigo-500 font-bold' : 'border-transparent text-zinc-400 hover:text-zinc-300' }}">
            Riwayat Kehadiran
        </button>
        <button onclick="switchTab('leave')" id="tab-leave" class="py-2.5 px-4 text-sm font-semibold border-b-2 transition-colors duration-150 {{ $activeTab === 'leave' ? 'border-indigo-600 text-indigo-500 font-bold' : 'border-transparent text-zinc-400 hover:text-zinc-300' }}">
            Riwayat Pengajuan Izin & Sakit
        </button>
    </div>

    <!-- Tab 1: Attendance History -->
    <div id="content-attendance" class="{{ $activeTab === 'attendance' ? '' : 'hidden' }}">
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
                            <td class="py-4 px-4 text-zinc-400 text-xs truncate max-w-xs" title="{{ $attendance->notes ?? '-' }}">
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
            {{ $attendances->appends(request()->except('attendance_page'))->links() }}
        </div>
    </div>

    <!-- Tab 2: Leave Request History -->
    <div id="content-leave" class="{{ $activeTab === 'leave' ? '' : 'hidden' }}">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-zinc-800 text-zinc-400 font-medium text-xs uppercase tracking-wider">
                        <th class="py-3.5 px-4">Tanggal Pengajuan</th>
                        <th class="py-3.5 px-4">Tipe</th>
                        <th class="py-3.5 px-4">Alasan</th>
                        <th class="py-3.5 px-4">Lampiran</th>
                        <th class="py-3.5 px-4">Status Umpan Balik</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse ($leaves as $leave)
                        <tr class="hover:bg-zinc-800">
                            <td class="py-4 px-4 font-medium text-zinc-250">
                                {{ $leave->start_date->isoFormat('D MMM Y') }} s/d {{ $leave->end_date->isoFormat('D MMM Y') }}
                            </td>
                            <td class="py-4 px-4">
                                @if ($leave->type == 'izin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-full">
                                        Izin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-rose-50 border border-rose-200 text-rose-700 rounded-full">
                                        Sakit
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-zinc-400 text-xs max-w-xs truncate" title="{{ $leave->reason }}">
                                {{ $leave->reason }}
                            </td>
                            <td class="py-4 px-4">
                                @if($leave->proof_file)
                                    <a href="{{ asset($leave->proof_file) }}" target="_blank" class="text-indigo-500 hover:text-indigo-400 text-xs font-medium underline inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-zinc-500 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if ($leave->status == 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full" title="Disetujui oleh Wakasek">
                                        Disetujui
                                    </span>
                                @elseif ($leave->status == 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-rose-50 border border-rose-200 text-rose-700 rounded-full" title="Ditolak oleh Wakasek">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-amber-50 border border-amber-200 text-amber-700 rounded-full" title="Menunggu Persetujuan">
                                        Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-zinc-500 text-sm">
                                Belum ada riwayat pengajuan izin/sakit.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $leaves->appends(request()->except('leave_page'))->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(tab) {
        const tabAttendance = document.getElementById('tab-attendance');
        const tabLeave = document.getElementById('tab-leave');
        const contentAttendance = document.getElementById('content-attendance');
        const contentLeave = document.getElementById('content-leave');

        if (tab === 'attendance') {
            // Tab active styles
            tabAttendance.className = "py-2.5 px-4 text-sm font-semibold border-b-2 border-indigo-600 text-indigo-500 transition-colors duration-150";
            tabLeave.className = "py-2.5 px-4 text-sm font-semibold border-b-2 border-transparent text-zinc-400 hover:text-zinc-300 transition-colors duration-150";
            
            // Show content
            contentAttendance.classList.remove('hidden');
            contentLeave.classList.add('hidden');
        } else {
            // Tab active styles
            tabLeave.className = "py-2.5 px-4 text-sm font-semibold border-b-2 border-indigo-600 text-indigo-500 transition-colors duration-150";
            tabAttendance.className = "py-2.5 px-4 text-sm font-semibold border-b-2 border-transparent text-zinc-400 hover:text-zinc-300 transition-colors duration-150";
            
            // Show content
            contentLeave.classList.remove('hidden');
            contentAttendance.classList.add('hidden');
        }
    }
</script>
@endsection
