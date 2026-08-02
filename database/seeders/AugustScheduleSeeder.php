<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AugustScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan Data Lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schedule::truncate();
        Attendance::truncate();
        User::where('role', 'intern')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Daftar 10 Anak Magang Asli
        $internsData = [
            ['name' => 'Nur Ira Lestari', 'nickname' => 'Ira', 'phone' => '6285124043728'],
            ['name' => 'Riziki Ilmi', 'nickname' => 'Riziki', 'phone' => '6289519514129'],
            ['name' => 'Revalina Aulia Anggraeni', 'nickname' => 'Reva', 'phone' => '628524681806'],
            ['name' => 'Arsyla Aika Nurrizky Hidayat', 'nickname' => 'Arsyla', 'phone' => '6282157309979'],
            ['name' => 'Muhamad Sidiq', 'nickname' => 'Sidiq', 'phone' => '6287735203330'],
            ['name' => 'Muhammad Andi Maulana', 'nickname' => 'Andi', 'phone' => '6281247172727'],
            ['name' => 'Siti Nor Srirahmi', 'nickname' => 'Rahmi', 'phone' => '6282159885651'],
            ['name' => 'Alya Nuruzzahrah', 'nickname' => 'Alya', 'phone' => '6281247710365'],
            ['name' => 'Alifariz Anwar', 'nickname' => 'Alifariz', 'phone' => '6281346331599'],
            ['name' => 'Ahmad Nabil Avila', 'nickname' => 'Nabil', 'phone' => '6283827468095'],
        ];

        $users = [];
        foreach ($internsData as $data) {
            $email = strtolower($data['nickname']) . '@viaspace.com';
            $user = User::create([
                'name' => $data['name'],
                'nickname' => $data['nickname'],
                'email' => $email,
                'phone_number' => $data['phone'],
                'password' => Hash::make('password123'),
                'role' => 'intern',
                'must_change_password' => true,
            ]);
            $users[$data['nickname']] = $user->id;
        }

        // 3. Pola Jadwal Agustus 2026 berdasarkan referensi gambar Excel
        // Tanggal 3 sampai 31 Agustus (Senin - Sabtu)
        $schedulePattern = [
            3 => ['Arsyla', 'Rahmi', 'Reva', 'Ira'], // Senin
            4 => ['Riziki', 'Sidiq', 'Alya', 'Alifariz'], // Selasa
            5 => ['Nabil', 'Andi', 'Arsyla', 'Rahmi'], // Rabu
            6 => ['Reva', 'Ira', 'Riziki', 'Sidiq'], // Kamis
            7 => ['Alya', 'Alifariz', 'Nabil', 'Andi'], // Jumat
            
            10 => ['Arsyla', 'Reva', 'Riziki', 'Alya'], // Senin
            11 => ['Rahmi', 'Ira', 'Sidiq', 'Alifariz'], // Selasa
            12 => ['Nabil', 'Andi', 'Arsyla', 'Riziki'], // Rabu
            13 => ['Reva', 'Alya', 'Rahmi', 'Sidiq'], // Kamis
            14 => ['Ira', 'Alifariz', 'Nabil', 'Arsyla'], // Jumat
            
            17 => ['Andi', 'Riziki', 'Rahmi', 'Reva'], // Senin
            18 => ['Alya', 'Sidiq', 'Ira', 'Nabil'], // Selasa
            19 => ['Alifariz', 'Andi', 'Arsyla', 'Rahmi'], // Rabu
            20 => ['Reva', 'Ira', 'Riziki', 'Alya'], // Kamis
            21 => ['Sidiq', 'Nabil', 'Alifariz', 'Andi'], // Jumat
            
            24 => ['Arsyla', 'Reva', 'Riziki', 'Ira'], // Senin
            25 => ['Rahmi', 'Alya', 'Sidiq', 'Nabil'], // Selasa
            26 => ['Alifariz', 'Andi', 'Arsyla', 'Reva'], // Rabu
            27 => ['Riziki', 'Ira', 'Rahmi', 'Alya'], // Kamis
            28 => ['Sidiq', 'Nabil', 'Alifariz', 'Andi'], // Jumat
            
            31 => ['Arsyla', 'Riziki', 'Reva', 'Rahmi'], // Senin
        ];

        // Tanggal untuk Sabtu (All Team)
        $saturdays = [8, 15, 22, 29];

        $year = 2026;
        $month = 8; // Agustus

        // Generate Jadwal Senin - Jumat
        foreach ($schedulePattern as $day => $nicknames) {
            $date = Carbon::create($year, $month, $day)->format('Y-m-d');
            foreach ($nicknames as $nickname) {
                if (isset($users[$nickname])) {
                    Schedule::create([
                        'user_id' => $users[$nickname],
                        'date' => $date
                    ]);
                }
            }
        }

        // Generate Jadwal Sabtu (All Team)
        foreach ($saturdays as $day) {
            $date = Carbon::create($year, $month, $day)->format('Y-m-d');
            foreach ($users as $nickname => $userId) {
                Schedule::create([
                    'user_id' => $userId,
                    'date' => $date
                ]);
            }
        }

    }
}

