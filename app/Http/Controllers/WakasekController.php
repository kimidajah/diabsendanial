<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WakasekController extends Controller
{
    public function dashboard()
    {
        $totalTeachers = Teacher::count();
        
        // Statistik kehadiran bulan ini
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
        
        $attendances = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])->get();
        
        $totalLogs = $attendances->count();
        $counts = [
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'alfa' => $attendances->where('status', 'alfa')->count(),
        ];
        
        $percentages = [];
        foreach ($counts as $status => $count) {
            $percentages[$status] = $totalLogs > 0 ? round(($count / $totalLogs) * 100, 1) : 0;
        }

        // Kehadiran hari ini
        $today = Carbon::today()->format('Y-m-d');
        $todayAttendances = Attendance::with('teacher')
            ->whereDate('date', $today)
            ->get();

        return view('wakasek.dashboard', compact(
            'totalTeachers', 
            'counts', 
            'percentages', 
            'totalLogs',
            'todayAttendances'
        ));
    }

    public function teachers()
    {
        $teachers = Teacher::with('user')->orderBy('name')->get();
        return view('wakasek.teachers', compact('teachers'));
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'nidn' => 'required|numeric|unique:teachers,nidn',
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        // Buat User Akun
        $user = User::create([
            'name' => $request->name,
            'username' => $request->nidn,
            'role' => 'guru',
            'password' => bcrypt($request->password),
        ]);

        // Buat Guru Profile
        Teacher::create([
            'user_id' => $user->id,
            'nidn' => $request->nidn,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'qr_code_token' => 'QR_' . $request->nidn . '_' . Str::upper(Str::random(8)),
        ]);

        return redirect()->route('wakasek.teachers')->with('success', 'Akun Guru berhasil dibuat.');
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user;

        $request->validate([
            'nidn' => 'required|numeric|unique:teachers,nidn,' . $teacher->id,
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $teacher->update([
            'nidn' => $request->nidn,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        $userUpdate = [
            'name' => $request->name,
            'username' => $request->nidn,
        ];

        if ($request->filled('password')) {
            $userUpdate['password'] = bcrypt($request->password);
        }

        $user->update($userUpdate);

        return redirect()->route('wakasek.teachers')->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroyTeacher($id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user;
        
        $teacher->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('wakasek.teachers')->with('success', 'Akun Guru berhasil dihapus.');
    }

    public function settings()
    {
        $timeLimit = Setting::getValue('time_limit_in', '07:30');
        $holidays = Holiday::orderBy('date')->get();
        return view('wakasek.settings', compact('timeLimit', 'holidays'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'time_limit_in' => 'required|date_format:H:i',
        ]);

        Setting::updateOrCreate(
            ['key' => 'time_limit_in'],
            ['value' => $request->time_limit_in, 'description' => 'Batas jam masuk absensi guru']
        );

        return redirect()->route('wakasek.settings')->with('success', 'Batas jam masuk berhasil diperbarui.');
    }

    public function storeHoliday(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'name' => 'required|string|max:255',
        ]);

        Holiday::create([
            'date' => $request->date,
            'name' => $request->name,
        ]);

        return redirect()->route('wakasek.settings')->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function destroyHoliday($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->route('wakasek.settings')->with('success', 'Hari libur berhasil dihapus.');
    }

    public function leaves()
    {
        $leaves = Leave::with('teacher')->orderBy('created_at', 'desc')->get();
        return view('wakasek.leaves', compact('leaves'));
    }

    public function updateLeaveStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $leave = Leave::findOrFail($id);
        $leave->update(['status' => $request->status]);

        // Update logs kehadiran berdasarkan keputusan approval
        $period = CarbonPeriod::create($leave->start_date, $leave->end_date);
        
        foreach ($period as $date) {
            if ($date->isWeekend() || Holiday::whereDate('date', $date->format('Y-m-d'))->exists()) {
                continue;
            }

            if ($request->status == 'approved') {
                Attendance::updateOrCreate(
                    [
                        'teacher_id' => $leave->teacher_id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => $leave->type,
                        'notes' => 'Izin/Sakit disetujui: ' . $leave->reason,
                        'check_in' => null,
                    ]
                );
            } else {
                // Jika direject, hapus log kehadiran jika statusnya 'izin' atau 'sakit'
                Attendance::where('teacher_id', $leave->teacher_id)
                    ->whereDate('date', $date->format('Y-m-d'))
                    ->whereIn('status', ['izin', 'sakit'])
                    ->delete();
            }
        }

        return redirect()->route('wakasek.leaves')->with('success', 'Status pengajuan izin berhasil diperbarui.');
    }

    public function reports(Request $request)
    {
        $teachers = Teacher::orderBy('name')->get();
        
        $query = Attendance::with(['teacher', 'scanner']);

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        return view('wakasek.reports', compact('teachers', 'attendances'));
    }
}
