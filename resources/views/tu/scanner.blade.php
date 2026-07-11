@extends('tu.layout')

@section('title', 'Scanner Kehadiran')

@section('styles')
<style>
    /* Styling html5-qrcode standard UI elements slightly to match our dark theme */
    #reader {
        border: none !important;
        background: #09090b !important;
        border-radius: 1rem;
        overflow: hidden;
    }
    #reader__dashboard_section_csr button {
        background-color: #4f46e5 !important;
        border: none !important;
        color: white !important;
        padding: 0.5rem 1rem !important;
        font-size: 0.75rem !important;
        border-radius: 0.5rem !important;
        cursor: pointer !important;
    }
    #reader__dashboard_section_csr select {
        background-color: #18181b !important;
        border: 1px solid #27272a !important;
        color: white !important;
        padding: 0.25rem 0.5rem !important;
        border-radius: 0.375rem !important;
    }
</style>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Scanner Card -->
    <div class="lg:col-span-2 bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 flex flex-col hover:border-zinc-700/50 transition duration-300">
        <div class="mb-4">
            <h3 class="text-lg font-bold text-white">Pemindai QR Code</h3>
            <p class="text-xs text-zinc-400 mt-0.5">Izinkan akses kamera browser, lalu arahkan kamera ke QR Code Guru.</p>
        </div>
        
        <!-- Video Scanner Container -->
        <div class="flex-grow flex items-center justify-center p-4 bg-zinc-950/50 border border-zinc-800 rounded-xl overflow-hidden min-h-[350px]">
            <div class="w-full max-w-md">
                <div id="reader"></div>
            </div>
        </div>
    </div>

    <!-- Scanner Info & Log Kehadiran -->
    <div class="lg:col-span-1 flex flex-col gap-6">
        <!-- Status Feedback Card (Live Update) -->
        <div id="status-card" class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 transition-all duration-300">
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
                <h3 id="result-title" class="font-bold text-white text-lg leading-tight">Nama Guru</h3>
                <p id="result-nidn" class="text-xs text-zinc-400 mt-1">NIDN: -</p>
                
                <div class="mt-4 pt-4 border-t border-zinc-850 space-y-3 text-sm">
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
        <div class="bg-zinc-900/40 border border-zinc-800/80 rounded-2xl p-6 flex-grow">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRV0El71dBcHraKeyjD635RYuxxI60RxOPFm8TOqky1tgdhyCi63JJmZIqXJEJOPACGCA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    // Web Audio API Beep Generator (No audio asset file required!)
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
        let html5QrcodeScanner = null;
        let isProcessing = false;
        const scanLogList = document.getElementById('scan-log-list');
        let scanSessionLogs = [];

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
            // Quiet fail - scanning logs output can be verbose
        }

        // Initialize HTML5 QR Code Scanner
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                rememberLastUsedCamera: true
            },
            /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

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
                iconWrapper.className = "w-12 h-12 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-4";
                statusEl.className = status.toLowerCase() === 'hadir' ? "font-semibold text-emerald-400" : "font-semibold text-amber-400";
                msgEl.className = "mt-4 p-3 rounded-lg text-xs bg-emerald-500/10 border border-emerald-500/20 text-emerald-400";
            } else {
                iconSuccess.classList.add('hidden');
                iconError.classList.remove('hidden');
                iconWrapper.className = "w-12 h-12 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mb-4";
                statusEl.className = "font-semibold text-rose-400";
                msgEl.className = "mt-4 p-3 rounded-lg text-xs bg-rose-500/10 border border-rose-500/20 text-rose-400";
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
                const statusColor = log.status.toLowerCase() === 'hadir' ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : 'text-amber-400 bg-amber-500/10 border-amber-500/20';
                
                const item = document.createElement('div');
                item.className = "flex justify-between items-center bg-zinc-950 p-2.5 rounded-lg border border-zinc-850/80 animate-fade-in";
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
