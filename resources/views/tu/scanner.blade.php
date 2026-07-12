@extends('tu.layout')

@section('title', 'Scanner Kehadiran')

@section('styles')
<style>
    #reader {
        border: none !important;
        border-radius: 1rem;
        overflow: hidden;
        background: #000 !important;
    }
    #reader video {
        border-radius: 1rem;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Scanner Card -->
    <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl p-6 flex flex-col shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-bold text-zinc-100">Pemindai QR Code</h3>
            <p class="text-xs text-zinc-400 mt-0.5">Buka akses kamera browser dengan menekan tombol, lalu arahkan kamera ke QR Code Guru.</p>
        </div>
        
        <!-- Video Scanner Container -->
        <div class="flex-grow flex flex-col items-center justify-center p-4 bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden min-h-[380px] relative">
            <!-- Scanner target (only shown when scanning is active) -->
            <div id="reader-wrapper" class="w-full max-w-md hidden text-center">
                <div id="reader" class="w-full rounded-xl overflow-hidden shadow-inner"></div>
                <div class="mt-4">
                    <button id="btn-stop-scan" class="bg-rose-600 hover:bg-rose-700 text-white font-medium text-xs py-2.5 px-5 rounded-xl shadow-sm transition-colors">
                        Matikan Kamera
                    </button>
                </div>
            </div>
            
            <!-- Placeholder / Start Scan Button (shown when idle) -->
            <div id="scanner-placeholder" class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                    </svg>
                </div>
                <h4 class="text-sm font-bold text-zinc-100 mb-2">Kamera Belum Aktif</h4>
                <p class="text-xs text-zinc-400 max-w-xs mb-6">Klik tombol di bawah ini untuk mengizinkan akses dan mulai memindai QR Code Guru.</p>
                <button id="btn-start-scan" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs py-3 px-6 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                    </svg>
                    Buka Kamera & Mulai Scan
                </button>
            </div>
        </div>
    </div>

    <!-- Scanner Info & Log Kehadiran -->
    <div class="lg:col-span-1 flex flex-col gap-6">
        <!-- Status Feedback Card (Live Update) -->
        <div id="status-card" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm">
            <h4 class="text-sm font-semibold text-zinc-400 mb-4">Hasil Pemindaian Terakhir</h4>
            
            <div id="status-idle" class="flex flex-col items-center justify-center py-8 text-center">
                <div class="w-12 h-12 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-zinc-500 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                    </svg>
                </div>
                <p class="text-xs text-zinc-500">Kamera siap. Arahkan QR Code untuk memulai pencatatan absensi.</p>
            </div>
            
            <div id="status-result" class="hidden">
                <div id="status-icon-wrapper" class="w-12 h-12 rounded-full flex items-center justify-center mb-4">
                    <svg id="status-icon-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg id="status-icon-error" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 id="result-title" class="font-bold text-zinc-100 text-lg leading-tight">Nama Guru</h3>
                <p id="result-nidn" class="text-xs text-zinc-400 mt-1">NIDN: -</p>
                
                <div class="mt-4 pt-4 border-t border-zinc-800 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Waktu Scan:</span>
                        <span id="result-time" class="font-semibold text-zinc-200">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Status Kehadiran:</span>
                        <span id="result-status" class="font-semibold">-</span>
                    </div>
                </div>
                
                <div id="result-msg" class="mt-4 p-3 rounded-lg text-xs">
                    Pesan status.
                </div>
            </div>
        </div>
        
        <!-- Quick Log list -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 flex-grow shadow-sm">
            <h4 class="text-sm font-semibold text-zinc-400 mb-3">Scan Sesi Ini</h4>
            <div id="scan-log-list" class="space-y-3.5 max-h-[220px] overflow-y-auto text-xs text-zinc-500">
                <!-- Log item placeholder -->
                <p class="text-center py-4">Belum ada aktivitas scan pada sesi ini.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Html5Qrcode Library from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" referrerpolicy="no-referrer"></script>

<script>
    // Web Audio API Beep Generator
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    
    function playBeep(frequency, duration, type) {
        try {
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.type = type || 'sine';
            oscillator.frequency.value = frequency;
            
            gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.3, audioCtx.currentTime + 0.05);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);

            oscillator.start(audioCtx.currentTime);
            oscillator.stop(audioCtx.currentTime + duration);
        } catch (e) {
            console.error("Audio Context Error", e);
        }
    }

    function playSuccessSound() {
        playBeep(880, 0.15, 'sine'); // High pitch short beep
    }

    function playErrorSound() {
        playBeep(220, 0.35, 'sawtooth'); // Low pitch long buzz
    }

    document.addEventListener("DOMContentLoaded", function() {
        let html5QrCode = null;
        let isProcessing = false;
        const scanLogList = document.getElementById('scan-log-list');
        let scanSessionLogs = [];

        const startBtn = document.getElementById('btn-start-scan');
        const stopBtn = document.getElementById('btn-stop-scan');
        const placeholder = document.getElementById('scanner-placeholder');
        const wrapper = document.getElementById('reader-wrapper');

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            
            // Temporary stop scan processing
            isProcessing = true;
            
            // Post dynamic token to backend
            fetch("{{ route('tu.scan') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qr_token: decodedText })
            })
            .then(async response => {
                const data = await response.json();
                
                if (response.ok) {
                    playSuccessSound();
                    displayResult(true, data.data.name, data.data.nidn, data.data.check_in, data.data.status, data.message);
                    addLogSession(data.data.name, data.data.status, data.data.check_in);
                } else {
                    playErrorSound();
                    displayResult(false, 'Gagal Absen', '-', '-', '-', data.message);
                }
            })
            .catch(error => {
                playErrorSound();
                displayResult(false, 'Error Sistem', '-', '-', '-', 'Gagal menghubungkan ke server.');
                console.error(error);
            })
            .finally(() => {
                // Resume scan processing after 2.5 seconds delay (cooldown to prevent double scan)
                setTimeout(() => {
                    isProcessing = false;
                }, 2500);
            });
        }

        function onScanFailure(error) {
            // Quiet fail
        }

        function startScanning() {
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            
            placeholder.classList.add('hidden');
            wrapper.classList.remove('hidden');

            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess, 
                onScanFailure
            )
            .catch(err => {
                console.error("Gagal mengaktifkan kamera", err);
                if (!window.isSecureContext) {
                    alert("Gagal mengakses kamera karena halaman diakses melalui koneksi HTTP tidak aman (Insecure Context).\n\nBrowser memblokir akses kamera pada IP HTTP non-localhost. Silakan gunakan HTTPS, localhost, ngrok, atau konfigurasi Chrome Flags.");
                } else {
                    alert("Gagal mengakses kamera. Pastikan izin kamera telah diberikan.");
                }
                stopScanning();
            });
        }

        function stopScanning() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(() => {
                    placeholder.classList.remove('hidden');
                    wrapper.classList.add('hidden');
                }).catch(err => {
                    console.error("Gagal menonaktifkan kamera", err);
                });
            } else {
                placeholder.classList.remove('hidden');
                wrapper.classList.add('hidden');
            }
        }

        startBtn.addEventListener('click', startScanning);
        stopBtn.addEventListener('click', stopScanning);

        // Display Scan Result Cards
        function displayResult(success, name, nidn, time, status, message) {
            const idleCard = document.getElementById('status-idle');
            const resultCard = document.getElementById('status-result');
            
            const iconSuccess = document.getElementById('status-icon-success');
            const iconError = document.getElementById('status-icon-error');
            const iconWrapper = document.getElementById('status-icon-wrapper');
            
            const titleEl = document.getElementById('result-title');
            const nidnEl = document.getElementById('result-nidn');
            const timeEl = document.getElementById('result-time');
            const statusEl = document.getElementById('result-status');
            const msgEl = document.getElementById('result-msg');
            
            // Toggle classes based on success status
            if (success) {
                iconSuccess.classList.remove('hidden');
                iconError.classList.add('hidden');
                iconWrapper.className = "w-12 h-12 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center mb-4";
                statusEl.className = status.toLowerCase() === 'hadir' ? "font-semibold text-emerald-700" : "font-semibold text-amber-700";
                msgEl.className = "mt-4 p-3 rounded-lg text-xs bg-emerald-50 border border-emerald-200 text-emerald-800";
            } else {
                iconSuccess.classList.add('hidden');
                iconError.classList.remove('hidden');
                iconWrapper.className = "w-12 h-12 rounded-full bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-center mb-4";
                statusEl.className = "font-semibold text-rose-700";
                msgEl.className = "mt-4 p-3 rounded-lg text-xs bg-rose-50 border border-rose-200 text-rose-850";
            }

            titleEl.textContent = name;
            nidnEl.textContent = `NIDN: ${nidn}`;
            timeEl.textContent = `${time} WIB`;
            statusEl.textContent = status;
            msgEl.textContent = message;

            idleCard.classList.add('hidden');
            resultCard.classList.remove('hidden');
        }

        // Add Log to list on side card
        function addLogSession(name, status, time) {
            scanSessionLogs.unshift({ name, status, time });
            
            if (scanSessionLogs.length > 5) {
                scanSessionLogs.pop();
            }

            scanLogList.innerHTML = '';
            scanSessionLogs.forEach(log => {
                const statusColor = log.status.toLowerCase() === 'hadir' ? 'text-emerald-700 bg-emerald-50 border-emerald-250' : 'text-amber-700 bg-amber-50 border-amber-250';
                
                const item = document.createElement('div');
                item.className = "flex justify-between items-center bg-zinc-950 p-2.5 rounded-lg border border-zinc-800";
                item.innerHTML = `
                    <div class="font-medium text-zinc-300 truncate max-w-[120px]">${log.name}</div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-zinc-500">${log.time}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold border ${statusColor}">${log.status}</span>
                    </div>
                `;
                scanLogList.appendChild(item);
            });
        }
    });
</script>
@endsection
