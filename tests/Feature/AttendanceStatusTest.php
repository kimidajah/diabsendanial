<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup setting batas jam masuk
        Setting::create([
            'key' => 'time_limit_in',
            'value' => '07:30',
        ]);
    }

    public function test_scan_qr_code_marks_hadir_before_time_limit()
    {
        // Hubungkan TU
        $tu = User::create([
            'name' => 'Operator TU',
            'email' => 'tu@diabsen.com',
            'role' => 'tu',
            'password' => bcrypt('password'),
        ]);

        $guru = User::create([
            'name' => 'Guru Joko',
            'role' => 'guru',
            'password' => bcrypt('password'),
        ]);

        $teacher = Teacher::create([
            'user_id' => $guru->id,
            'nidn' => '123456',
            'name' => 'Guru Joko',
            'qr_code_token' => 'TOKEN_JOKO',
        ]);

        // Simulasikan jam sekarang adalah 07:15 (sebelum 07:30)
        Carbon::setTestNow(Carbon::create(2026, 7, 11, 7, 15, 0));

        $response = $this->actingAs($tu)
            ->postJson('/tu/scan', [
                'qr_token' => 'TOKEN_JOKO',
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => 'Hadir'
        ]);

        $this->assertDatabaseHas('attendances', [
            'teacher_id' => $teacher->id,
            'status' => 'hadir',
        ]);

        Carbon::setTestNow(); // Reset time simulation
    }

    public function test_scan_qr_code_marks_terlambat_after_time_limit()
    {
        $tu = User::create([
            'name' => 'Operator TU',
            'email' => 'tu@diabsen.com',
            'role' => 'tu',
            'password' => bcrypt('password'),
        ]);

        $guru = User::create([
            'name' => 'Guru Joko',
            'role' => 'guru',
            'password' => bcrypt('password'),
        ]);

        $teacher = Teacher::create([
            'user_id' => $guru->id,
            'nidn' => '123456',
            'name' => 'Guru Joko',
            'qr_code_token' => 'TOKEN_JOKO',
        ]);

        // Simulasikan jam sekarang adalah 07:45 (setelah 07:30)
        Carbon::setTestNow(Carbon::create(2026, 7, 11, 7, 45, 0));

        $response = $this->actingAs($tu)
            ->postJson('/tu/scan', [
                'qr_token' => 'TOKEN_JOKO',
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => 'Terlambat'
        ]);

        $this->assertDatabaseHas('attendances', [
            'teacher_id' => $teacher->id,
            'status' => 'terlambat',
        ]);

        Carbon::setTestNow(); // Reset time simulation
    }
}
