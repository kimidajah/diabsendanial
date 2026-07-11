@extends('wakasek.layout')

@section('title', 'Kelola Data Guru')

@section('content')
<div class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 hover:border-zinc-700/50 transition duration-300">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-bold text-white">Kelola Akun Guru</h3>
            <p class="text-xs text-zinc-400 mt-0.5">Tambah guru baru, perbarui data guru, atau kelola kata sandi akun.</p>
        </div>
        <button onclick="toggleModal('add-modal', true)" 
            class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs py-2.5 px-4 rounded-xl transition duration-200 shadow-lg shadow-indigo-600/20 flex items-center gap-1.5 self-start sm:self-center active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Guru Baru
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-zinc-800 text-zinc-400 font-medium text-xs uppercase tracking-wider">
                    <th class="py-3.5 px-4">Nama Guru</th>
                    <th class="py-3.5 px-4">NIDN / Username</th>
                    <th class="py-3.5 px-4">No. Telepon</th>
                    <th class="py-3.5 px-4">Kode Token QR</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse ($teachers as $teacher)
                    <tr class="hover:bg-zinc-800/20 transition duration-150">
                        <td class="py-4 px-4 font-semibold text-zinc-200">
                            {{ $teacher->name }}
                        </td>
                        <td class="py-4 px-4 text-zinc-400 font-mono text-xs">
                            {{ $teacher->nidn }}
                        </td>
                        <td class="py-4 px-4 text-zinc-400">
                            {{ $teacher->phone_number ?? '-' }}
                        </td>
                        <td class="py-4 px-4 text-zinc-500 font-mono text-xs truncate max-w-[120px]">
                            {{ $teacher->qr_code_token }}
                        </td>
                        <td class="py-4 px-4 text-right space-x-2">
                            <button 
                                onclick="openEditModal('{{ $teacher->id }}', '{{ $teacher->name }}', '{{ $teacher->nidn }}', '{{ $teacher->phone_number }}')"
                                class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white text-xs px-3 py-1.5 rounded-lg border border-zinc-700 transition duration-200 inline-flex items-center gap-1 active:scale-95">
                                Edit
                            </button>
                            
                            <form action="{{ route('wakasek.teachers.destroy', $teacher->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus akun guru ini? Tindakan ini tidak dapat dibatalkan.')"
                                    class="bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white text-xs px-3 py-1.5 rounded-lg border border-red-500/20 hover:border-transparent transition duration-200 inline-flex items-center gap-1 active:scale-95">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-zinc-500 text-sm">
                            Belum ada data guru. Klik tombol di atas untuk menambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ================= MODAL TAMBAH GURU ================= -->
<div id="add-modal" class="fixed inset-0 z-50 overflow-y-auto hidden bg-zinc-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 w-full max-w-md rounded-2xl p-6 shadow-2xl animate-fade-in">
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-zinc-850">
            <h4 class="text-base font-bold text-white">Tambah Akun Guru Baru</h4>
            <button onclick="toggleModal('add-modal', false)" class="text-zinc-500 hover:text-zinc-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="{{ route('wakasek.teachers.store') }}" method="POST" class="space-y-4 text-left">
            @csrf
            <div>
                <label for="add-name" class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="add-name" required placeholder="Ahmad Zulkarnaen, M.Pd"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
            </div>

            <div>
                <label for="add-nidn" class="block text-xs font-medium text-zinc-300 mb-1.5">NIDN (Gunakan sebagai Username)</label>
                <input type="text" name="nidn" id="add-nidn" required placeholder="Contoh: 19820315..."
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
            </div>

            <div>
                <label for="add-phone" class="block text-xs font-medium text-zinc-300 mb-1.5">Nomor Telepon (Optional)</label>
                <input type="text" name="phone_number" id="add-phone" placeholder="Contoh: 0812345..."
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
            </div>

            <div>
                <label for="add-password" class="block text-xs font-medium text-zinc-300 mb-1.5">Kata Sandi Default</label>
                <input type="password" name="password" id="add-password" required placeholder="Min. 6 Karakter"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="toggleModal('add-modal', false)"
                    class="flex-1 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white py-2.5 rounded-xl text-xs transition duration-200 border border-zinc-700">
                    Batal
                </button>
                <button type="submit" 
                    class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 rounded-xl text-xs transition duration-200 shadow-lg shadow-indigo-600/20">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL EDIT GURU ================= -->
<div id="edit-modal" class="fixed inset-0 z-50 overflow-y-auto hidden bg-zinc-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 w-full max-w-md rounded-2xl p-6 shadow-2xl animate-fade-in">
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-zinc-850">
            <h4 class="text-base font-bold text-white">Edit Informasi Guru</h4>
            <button onclick="toggleModal('edit-modal', false)" class="text-zinc-500 hover:text-zinc-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="edit-form" action="" method="POST" class="space-y-4 text-left">
            @csrf
            @method('PUT')
            <div>
                <label for="edit-name" class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="edit-name" required
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
            </div>

            <div>
                <label for="edit-nidn" class="block text-xs font-medium text-zinc-300 mb-1.5">NIDN</label>
                <input type="text" name="nidn" id="edit-nidn" required
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
            </div>

            <div>
                <label for="edit-phone" class="block text-xs font-medium text-zinc-300 mb-1.5">Nomor Telepon (Optional)</label>
                <input type="text" name="phone_number" id="edit-phone"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
            </div>

            <div>
                <label for="edit-password" class="block text-xs font-medium text-zinc-300 mb-1.5">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" id="edit-password" placeholder="Min. 6 Karakter"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="toggleModal('edit-modal', false)"
                    class="flex-1 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white py-2.5 rounded-xl text-xs transition duration-200 border border-zinc-700">
                    Batal
                </button>
                <button type="submit" 
                    class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 rounded-xl text-xs transition duration-200 shadow-lg shadow-indigo-600/20">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleModal(modalId, show) {
        const modal = document.getElementById(modalId);
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    function openEditModal(id, name, nidn, phone) {
        const form = document.getElementById('edit-form');
        form.action = `/wakasek/teachers/${id}`;
        
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-nidn').value = nidn;
        document.getElementById('edit-phone').value = phone === 'null' ? '' : phone;
        document.getElementById('edit-password').value = '';
        
        toggleModal('edit-modal', true);
    }
</script>
@endsection
