<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class August2026Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $internNames = [
            'Arsyla',
            'Rahmi',
            'Reva',
            'Ira',
            'Riziki',
            'Sidiq',
            'Alya',
            'Ahmad Alifariz',
            'Nabil',
            'Andi'
        ];

        $interns = [];
        $passwords = [];

        $this->command->info('--- MEMBUAT 10 AKUN MAGANG ---');
        
        foreach ($internNames as $name) {
            // Check if exists
            $email = strtolower(str_replace(' ', '', $name)) . '@viaspace.com';
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $rawPassword = Str::random(6); // 6 char random password
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($rawPassword),
                    'role' => 'intern',
                    'must_change_password' => true, // Force password change
                ]);
                $passwords[$name] = $rawPassword;
                $this->command->line("Name: {$name} | Email: {$email} | Pass: {$rawPassword}");
            } else {
                $this->command->line("User {$name} already exists. Skipping creation.");
            }
            
            $interns[$name] = $user->id;
        }

        $this->command->info('--- MENGISI JADWAL AGUSTUS 2026 ---');

        $year = 2026;
        $month = 8; // August

        // Clear existing schedules for August 2026
        Schedule::whereMonth('date', $month)->whereYear('date', $year)->delete();

        // Exact mapping from the user's screenshot
        $scheduleMap = [
            3  => ['Arsyla', 'Rahmi', 'Reva', 'Ira'],
            4  => ['Riziki', 'Sidiq', 'Alya', 'Ahmad Alifariz'],
            5  => ['Nabil', 'Andi', 'Arsyla', 'Rahmi'],
            6  => ['Reva', 'Ira', 'Riziki', 'Sidiq'],
            7  => ['Alya', 'Ahmad Alifariz', 'Nabil', 'Andi'],
            8  => ['ALL'], // All Team
            10 => ['Arsyla', 'Reva', 'Riziki', 'Alya'],
            11 => ['Rahmi', 'Ira', 'Sidiq', 'Ahmad Alifariz'],
            12 => ['Nabil', 'Andi', 'Arsyla', 'Riziki'],
            13 => ['Reva', 'Alya', 'Rahmi', 'Sidiq'],
            14 => ['Ira', 'Ahmad Alifariz', 'Nabil', 'Arsyla'],
            15 => ['ALL'], // All Team
            17 => ['Andi', 'Riziki', 'Rahmi', 'Reva'],
            18 => ['Alya', 'Sidiq', 'Ira', 'Nabil'],
            19 => ['Ahmad Alifariz', 'Andi', 'Arsyla', 'Rahmi'],
            20 => ['Reva', 'Ira', 'Riziki', 'Alya'],
            21 => ['Sidiq', 'Nabil', 'Ahmad Alifariz', 'Andi'],
            22 => ['ALL'], // All Team
            24 => ['Arsyla', 'Reva', 'Riziki', 'Ira'],
            25 => ['Rahmi', 'Alya', 'Sidiq', 'Nabil'],
            26 => ['Ahmad Alifariz', 'Andi', 'Arsyla', 'Reva'],
            27 => ['Riziki', 'Ira', 'Rahmi', 'Alya'],
            28 => ['Sidiq', 'Nabil', 'Ahmad Alifariz', 'Andi'],
            29 => ['ALL'], // All Team
            31 => ['Arsyla', 'Riziki', 'Reva', 'Rahmi'],
        ];

        $insertData = [];

        foreach ($scheduleMap as $day => $names) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
            
            if ($names[0] === 'ALL') {
                $assignedNames = $internNames;
            } else {
                $assignedNames = $names;
            }

            foreach ($assignedNames as $assignedName) {
                if (isset($interns[$assignedName])) {
                    $insertData[] = [
                        'user_id' => $interns[$assignedName],
                        'date' => $dateStr,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    $this->command->error("Name {$assignedName} not found in user list.");
                }
            }
        }

        if (!empty($insertData)) {
            Schedule::insert($insertData);
            $this->command->info(count($insertData) . " jadwal berhasil di-generate untuk bulan Agustus 2026.");
        }
    }
}
