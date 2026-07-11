<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Settings
        \App\Models\Setting::create([
            'key' => 'time_limit_in',
            'value' => '07:30',
            'description' => 'Batas jam masuk absensi guru'
        ]);

        // Seed Wakasek
        User::create([
            'name' => 'Wakasek Kurikulum',
            'email' => 'wakasek@diabsen.com',
            'username' => 'wakasek',
            'role' => 'wakasek',
            'password' => bcrypt('password'),
        ]);

        // Seed TU
        User::create([
            'name' => 'Staf Tata Usaha',
            'email' => 'tu@diabsen.com',
            'username' => 'tu',
            'role' => 'tu',
            'password' => bcrypt('password'),
        ]);

        // Seed Guru 1
        $user1 = User::create([
            'name' => 'Budi Santoso, M.Pd',
            'username' => '198203152010121001',
            'role' => 'guru',
            'password' => bcrypt('password'),
        ]);
        \App\Models\Teacher::create([
            'user_id' => $user1->id,
            'nidn' => '198203152010121001',
            'name' => 'Budi Santoso, M.Pd',
            'phone_number' => '081234567890',
            'qr_code_token' => 'QR_BUDI_19820315',
        ]);

        // Seed Guru 2
        $user2 = User::create([
            'name' => 'Siti Aminah, S.Pd',
            'username' => '198907242015042002',
            'role' => 'guru',
            'password' => bcrypt('password'),
        ]);
        \App\Models\Teacher::create([
            'user_id' => $user2->id,
            'nidn' => '198907242015042002',
            'name' => 'Siti Aminah, S.Pd',
            'phone_number' => '081234567891',
            'qr_code_token' => 'QR_SITI_19890724',
        ]);

        // Seed Guru 3
        $user3 = User::create([
            'name' => 'Joko Susilo, S.T',
            'username' => '199305112020081003',
            'role' => 'guru',
            'password' => bcrypt('password'),
        ]);
        \App\Models\Teacher::create([
            'user_id' => $user3->id,
            'nidn' => '199305112020081003',
            'name' => 'Joko Susilo, S.T',
            'phone_number' => '081234567892',
            'qr_code_token' => 'QR_JOKO_19930511',
        ]);
    }
}
