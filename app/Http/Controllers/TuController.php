<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Setting;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TuController extends Controller
{
    public function scanner()
    {
        return view('tu.scanner');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $todayCarbon = Carbon::today();

        // 1. Cek akhir pekan (Sabtu & Minggu)
        if ($todayCarbon->isWeekend()) {
            return response()->json([
                'success' => false,
                'message' => 'Hari ini adalah hari libur akhir pekan (Sabtu/Minggu). Scan absensi dinonaktifkan.'
            ], 422);
        }

        // 2. Cek hari libur nasional yang terdaftar di database
        $isHoliday = Holiday::whereDate('date', $todayCarbon->format('Y-m-d'))->first();
        if ($isHoliday) {
            return response()->json([
                'success' => false,
                'message' => "Hari ini adalah hari libur: {$isHoliday->name}. Scan absensi dinonaktifkan."
            ], 422);
        }

        $teacher = Teacher::where('qr_code_token', $request->qr_token)->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau guru tidak terdaftar.'
            ], 404);
        }

        $today = $todayCarbon->format('Y-m-d');

        // Periksa apakah sudah ada absensi hari ini
        $existingAttendance = Attendance::where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance) {
            $statusText = ucfirst($existingAttendance->status);
            $timeText = $existingAttendance->check_in ? ' pada jam ' . date('H:i', strtotime($existingAttendance->check_in)) : '';
            return response()->json([
                'success' => false,
                'message' => "Guru {$teacher->name} sudah tercatat {$statusText}{$timeText} hari ini."
            ], 422);
        }

        // Tentukan batas waktu masuk
        $timeLimitStr = Setting::getValue('time_limit_in', '07:30');
        $currentTime = Carbon::now();
        $timeLimit = Carbon::createFromTimeString($timeLimitStr);

        // Bandingkan jam scan dengan batas jam masuk
        $status = $currentTime->greaterThan($timeLimit) ? 'terlambat' : 'hadir';

        // Simpan absensi
        $attendance = Attendance::create([
            'teacher_id' => $teacher->id,
            'scan_by_user_id' => Auth::id(),
            'date' => $today,
            'check_in' => $currentTime->toTimeString(),
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Absen berhasil dicatat!",
            'data' => [
                'name' => $teacher->name,
                'nidn' => $teacher->nidn,
                'status' => ucfirst($status),
                'check_in' => $currentTime->format('H:i:s'),
            ]
        ]);
    }

    public function reports(Request $request)
    {
        $teachers = Teacher::orderBy('name')->get();
        
        $query = Attendance::with(['teacher', 'scanner']);

        // Filter guru
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter tanggal/bulan
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        return view('tu.reports', compact('teachers', 'attendances'));
    }

    public function export(Request $request)
    {
        $query = Attendance::with(['teacher', 'scanner']);

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
            $teacher = Teacher::find($request->teacher_id);
            $filename = 'laporan_absensi_' . str_replace(' ', '_', strtolower($teacher->name)) . '_' . date('Ymd') . '.csv';
        } else {
            $filename = 'laporan_absensi_seluruh_guru_' . date('Ymd') . '.csv';
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // Generate CSV file
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV
            fputcsv($file, ['No', 'Tanggal', 'NIDN', 'Nama Guru', 'Jam Scan', 'Status', 'Operator TU', 'Catatan/Alasan']);

            foreach ($attendances as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->date->format('Y-m-d'),
                    $row->teacher->nidn,
                    $row->teacher->name,
                    $row->check_in ?? '-',
                    ucfirst($row->status),
                    $row->scanner->name ?? '-',
                    $row->notes ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
