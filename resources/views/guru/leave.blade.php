@extends('guru.layout')

@section('title', 'Ajukan Izin / Sakit')

@section('content')
<div class="max-w-2xl mx-auto bg-zinc-900 border border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm">
    <div class="mb-8">
        <h3 class="text-xl font-bold text-zinc-100">Form Pengajuan Izin & Sakit</h3>
        <p class="text-xs text-zinc-400 mt-1">Isi formulir berikut dengan lengkap untuk mengajukan dispensasi ketidakhadiran.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl p-4 mb-6">
            <span class="font-semibold block mb-1">Terdapat kesalahan pengisian:</span>
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.leave.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Jenis Pengajuan -->
        <div>
            <label class="block text-xs font-medium text-zinc-300 mb-3">Jenis Pengajuan</label>
            <div class="grid grid-cols-2 gap-4">
                <!-- Radio Izin -->
                <label class="relative flex items-center justify-between p-4 bg-zinc-950 border border-zinc-800 rounded-xl cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-zinc-100 block">Izin</span>
                            <span class="text-[10px] text-zinc-500">Urusan penting / dinas</span>
                        </div>
                    </div>
                    <input type="radio" name="type" value="izin" required class="text-indigo-600 bg-zinc-950 border-zinc-800 focus:ring-indigo-500/20" {{ old('type') == 'izin' ? 'checked' : '' }}>
                </label>

                <!-- Radio Sakit -->
                <label class="relative flex items-center justify-between p-4 bg-zinc-950 border border-zinc-800 rounded-xl cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-zinc-100 block">Sakit</span>
                            <span class="text-[10px] text-zinc-500">Kondisi kesehatan</span>
                        </div>
                    </div>
                    <input type="radio" name="type" value="sakit" required class="text-indigo-600 bg-zinc-950 border-zinc-800 focus:ring-indigo-500/20" {{ old('type', 'sakit') == 'sakit' ? 'checked' : '' }}>
                </label>
            </div>
        </div>

        <!-- Rentang Tanggal -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-xs font-medium text-zinc-300 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" required 
                    value="{{ old('start_date', date('Y-m-d')) }}"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label for="end_date" class="block text-xs font-medium text-zinc-300 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" required 
                    value="{{ old('end_date', date('Y-m-d')) }}"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
        </div>

        <!-- Alasan Pengajuan -->
        <div>
            <label for="reason" class="block text-xs font-medium text-zinc-300 mb-2">Alasan Pengajuan</label>
            <textarea name="reason" id="reason" rows="4" required placeholder="Tuliskan alasan pengajuan secara rinci (minimal 10 karakter)..."
                class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('reason') }}</textarea>
        </div>

        <!-- Unggah File Bukti -->
        <div>
            <label for="proof_file" class="block text-xs font-medium text-zinc-300 mb-2">Upload Bukti Pendukung</label>
            <div class="flex items-center justify-center w-full">
                <label for="proof_file" class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-zinc-800 bg-zinc-950 rounded-xl cursor-pointer">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-zinc-500 mb-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                        </svg>
                        <p class="text-xs text-zinc-400 font-semibold mb-1">Klik untuk upload file</p>
                        <p class="text-[10px] text-zinc-500">PDF, PNG, JPG, JPEG (Maks. 2MB)</p>
                    </div>
                    <input type="file" name="proof_file" id="proof_file" class="hidden" required accept=".pdf,image/*">
                </label>
            </div>
            <!-- File selected feedback name placeholder -->
            <div id="file-name-feedback" class="mt-2 text-xs text-indigo-600 hidden flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span id="file-name-text"></span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 pt-4 border-t border-zinc-800">
            <a href="{{ route('guru.dashboard') }}" 
                class="flex-1 text-center bg-zinc-800 hover:bg-zinc-700 text-zinc-300 py-3 px-4 rounded-xl text-sm border border-zinc-700">
                Batal
            </a>
            <button type="submit" 
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-xl text-sm shadow-sm transition-colors">
                Kirim Pengajuan
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById('proof_file');
        const feedbackDiv = document.getElementById('file-name-feedback');
        const feedbackText = document.getElementById('file-name-text');

        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                feedbackText.textContent = `File terpilih: ${fileName}`;
                feedbackDiv.classList.remove('hidden');
            } else {
                feedbackDiv.classList.add('hidden');
            }
        });
    });
</script>
@endsection
