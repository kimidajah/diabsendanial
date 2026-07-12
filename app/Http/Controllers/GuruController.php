<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    public function dashboard()
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        // Ambil absen hari ini
        $todayAttendance = Attendance::where('teacher_id', $teacher->id)
            ->whereDate('date', Carbon::today())
            ->first();

        return view('guru.dashboard', compact('teacher', 'todayAttendance'));
    }

    public function history()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        $attendances = Attendance::where('teacher_id', $teacher->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('guru.history', compact('attendances'));
    }

    public function leave()
    {
        return view('guru.leave');
    }

    public function storeLeave(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:izin,sakit',
            'reason' => 'required|string|min:10',
            'proof_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        // Upload file ke public/uploads/proof_files
        $file = $request->file('proof_file');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/proof_files'), $fileName);
        $filePath = 'uploads/proof_files/' . $fileName;

        // Simpan data pengajuan izin
        $leave = Leave::create([
            'teacher_id' => $teacher->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'reason' => $request->reason,
            'proof_file' => $filePath,
            'status' => 'pending', // Status awal pending, menunggu persetujuan Wakasek
        ]);

        return redirect()->route('guru.history')->with('success', 'Pengajuan ' . ucfirst($request->type) . ' berhasil dikirim dan menunggu persetujuan Wakasek.');
    }
}
