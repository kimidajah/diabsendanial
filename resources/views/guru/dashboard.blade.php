@extends('guru.layout')

@section('title', 'Dashboard Guru')

@section('styles')
<style>
    /* Custom style for QR Code container to ensure high contrast */
    #qrcode img {
        margin: 0 auto;
        padding: 12px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Profil Guru Card -->
    <div class="lg:col-span-1 bg-zinc-900 border border-zinc-800 rounded-2xl p-6 flex flex-col justify-between shadow-sm">
        <div>
            <div class="flex items-center gap-3.5 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-xl font-bold">
                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-bold text-zinc-100 text-lg leading-tight">{{ $teacher->name }}</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">NIDN: {{ $teacher->nidn }}</p>
                </div>
            </div>
            
            <div class="space-y-4 text-sm border-t border-zinc-800 pt-4">
                <div>
                    <span class="text-xs text-zinc-500 block">No. Telepon</span>
                    <span class="text-zinc-300 font-medium">{{ $teacher->phone_number ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-zinc-500 block">Status Kehadiran Hari Ini</span>
                    <div class="mt-1">
                        @if ($todayAttendance)
                            @if ($todayAttendance->status == 'hadir')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Hadir ({{ date('H:i', strtotime($todayAttendance->check_in)) }})
                                </span>
                            @elseif ($todayAttendance->status == 'terlambat')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Terlambat ({{ date('H:i', strtotime($todayAttendance->check_in)) }})
                                </span>
                            @elseif ($todayAttendance->status == 'izin')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    Izin
                                </span>
                            @elseif ($todayAttendance->status == 'sakit')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Sakit
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-zinc-50 border border-zinc-200 text-zinc-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span>
                                    Alfa
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-zinc-50 border border-zinc-200 text-zinc-600 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span>
                                Belum Absen Harian
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 border-t border-zinc-800 pt-4 text-xs text-zinc-500">
            Pastikan membawa HP saat memasuki gerbang sekolah untuk melakukan scanning.
        </div>
    </div>

    <!-- QR Code Card -->
    <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl p-8 flex flex-col items-center justify-center text-center shadow-sm">
        <h3 class="text-lg font-bold text-zinc-100 mb-2">QR Code Kehadiran Anda</h3>
        <p class="text-xs text-zinc-400 mb-8 max-w-sm">Tunjukkan QR Code ini kepada petugas Tata Usaha di gerbang masuk sekolah untuk memverifikasi kehadiran.</p>
        
        <!-- QR Code Target -->
        <div class="relative p-4 bg-zinc-950 border border-zinc-800 rounded-3xl mb-8 flex justify-center items-center">
            <div id="qrcode"></div>
        </div>
        
        <button id="download-qr" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs py-2.5 px-5 rounded-xl shadow-sm flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Unduh QR Code
        </button>
    </div>
</div>
@endsection

@section('scripts')
<!-- QR Code Library from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const qrToken = "{{ $teacher->qr_code_token }}";
        
        // Generate QR Code
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: qrToken,
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        // Download QR Code Action
        document.getElementById('download-qr').addEventListener('click', function() {
            const qrImg = document.querySelector('#qrcode img');
            if (qrImg) {
                const link = document.createElement('a');
                link.download = 'diabsen-qr-{{ $teacher->nidn }}.png';
                link.href = qrImg.src;
                link.click();
            } else {
                // If it renders as canvas instead of image
                const qrCanvas = document.querySelector('#qrcode canvas');
                if (qrCanvas) {
                    const link = document.createElement('a');
                    link.download = 'diabsen-qr-{{ $teacher->nidn }}.png';
                    link.href = qrCanvas.toDataURL("image/png");
                    link.click();
                }
            }
        });
    });
</script>
@endsection
