@extends('wakasek.layout')

@section('title', 'Persetujuan Izin & Sakit')

@section('content')
<div class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 hover:border-zinc-700/50 transition duration-300">
    <div class="mb-6">
        <h3 class="text-lg font-bold text-white">Daftar Pengajuan Izin / Sakit</h3>
        <p class="text-xs text-zinc-400 mt-0.5">Tinjau dokumen bukti dan verifikasi status kehadiran dispensasi guru.</p>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-zinc-800 text-zinc-400 font-medium text-xs uppercase tracking-wider">
                    <th class="py-3.5 px-4">Nama Guru</th>
                    <th class="py-3.5 px-4">Jenis</th>
                    <th class="py-3.5 px-4">Tanggal Kehadiran</th>
                    <th class="py-3.5 px-4">Alasan</th>
                    <th class="py-3.5 px-4">Dokumen Bukti</th>
                    <th class="py-3.5 px-4">Status Verifikasi</th>
                    <th class="py-3.5 px-4 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse ($leaves as $leave)
                    <tr class="hover:bg-zinc-800/20 transition duration-150">
                        <!-- Nama Guru -->
                        <td class="py-4 px-4 font-semibold text-zinc-200">
                            {{ $leave->teacher->name }}
                        </td>
                        <!-- Jenis Izin -->
                        <td class="py-4 px-4">
                            @if ($leave->type == 'izin')
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded">
                                    Izin
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded">
                                    Sakit
                                </span>
                            @endif
                        </td>
                        <!-- Tanggal -->
                        <td class="py-4 px-4 text-zinc-300 text-xs font-medium">
                            {{ $leave->start_date->format('Y-m-d') }} s/d {{ $leave->end_date->format('Y-m-d') }}
                        </td>
                        <!-- Alasan -->
                        <td class="py-4 px-4 text-zinc-400 text-xs max-w-xs truncate" title="{{ $leave->reason }}">
                            {{ $leave->reason }}
                        </td>
                        <!-- Dokumen Bukti -->
                        <td class="py-4 px-4 text-xs">
                            <a href="{{ asset($leave->proof_file) }}" target="_blank" 
                                class="text-indigo-400 hover:text-indigo-300 font-semibold inline-flex items-center gap-1 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Buka Dokumen
                            </a>
                        </td>
                        <!-- Status -->
                        <td class="py-4 px-4">
                            @if ($leave->status == 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full">
                                    Disetujui
                                </span>
                            @elseif ($leave->status == 'rejected')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-full">
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold bg-zinc-800 border border-zinc-700 text-zinc-400 rounded-full">
                                    Menunggu
                                </span>
                            @endif
                        </td>
                        <!-- Aksi -->
                        <td class="py-4 px-4 text-right">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('wakasek.leaves.status', $leave->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" {{ $leave->status == 'approved' ? 'disabled' : '' }}
                                        class="bg-emerald-600 hover:bg-emerald-500 disabled:opacity-30 disabled:hover:bg-emerald-600 text-white text-xs px-2.5 py-1.5 rounded-lg transition duration-200 active:scale-95">
                                        Setujui
                                    </button>
                                </form>
                                
                                <form action="{{ route('wakasek.leaves.status', $leave->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" {{ $leave->status == 'rejected' ? 'disabled' : '' }}
                                        class="bg-rose-600 hover:bg-rose-500 disabled:opacity-30 disabled:hover:bg-rose-600 text-white text-xs px-2.5 py-1.5 rounded-lg transition duration-200 active:scale-95">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-zinc-500 text-sm">
                            Tidak ada data pengajuan izin/sakit.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
