@extends('wakasek.layout')

@section('title', 'Pengaturan Sistem & Kalender Libur')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Batas Jam Masuk & Tambah Hari Libur -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Batas Jam Masuk Form -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-zinc-100 mb-4">Pengaturan Batas Absensi</h3>
            
            <form action="{{ route('wakasek.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="time_limit_in" class="block text-xs text-zinc-400 mb-2">Batas Jam Masuk Harian</label>
                    <input type="time" name="time_limit_in" id="time_limit_in" required value="{{ $timeLimit }}"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <button type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs py-3 px-4 rounded-xl shadow-sm">
                    Perbarui Jam Masuk
                </button>
            </form>
        </div>

        <!-- Tambah Hari Libur Form -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-zinc-100 mb-4">Tambah Hari Libur / Tanggal Merah</h3>
            
            <form action="{{ route('wakasek.holidays.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="date" class="block text-xs text-zinc-400 mb-2">Tanggal</label>
                    <input type="date" name="date" id="date" required
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label for="name" class="block text-xs text-zinc-400 mb-2">Nama Keterangan Libur</label>
                    <input type="text" name="name" id="name" required placeholder="Contoh: Tahun Baru Islam"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <button type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs py-3 px-4 rounded-xl shadow-sm">
                    Tambahkan Hari Libur
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Daftar Hari Libur -->
    <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm">
        <h3 class="text-sm font-bold text-zinc-100 mb-4">Kalender Hari Libur Nasional & Tanggal Merah</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-zinc-800 text-zinc-400 font-semibold uppercase tracking-wider">
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse ($holidays as $holiday)
                        <tr class="hover:bg-zinc-800">
                            <td class="py-3.5 px-4 font-medium text-zinc-250">
                                {{ $holiday->date->isoFormat('D MMMM Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-zinc-400">
                                {{ $holiday->name }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form action="{{ route('wakasek.holidays.destroy', $holiday->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus hari libur ini?')"
                                        class="bg-red-50 border border-red-200 text-red-750 text-[10px] px-2.5 py-1 rounded">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-zinc-500">
                                Belum ada agenda hari libur yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
