<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'role',
        'phone_number',
        'avatar',
        'address',
        'instagram',
        'nim',
        'school',
        'must_change_password',
        'is_active',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getMonthlyPointsAttribute()
    {
        $points = 0;
        $currentMonth = now()->format('Y-m');

        $attendances = $this->attendances()->where('date', 'like', $currentMonth . '%')->get();
        foreach ($attendances as $att) {
            // Check in logic
            if ($att->check_in_time) {
                // Determine if late (e.g., after 08:00)
                $setting = Setting::first();
                $lateTime = $setting ? $setting->start_time : '08:00:00';
                $checkInTime = \Carbon\Carbon::parse($att->check_in_time)->format('H:i:s');
                
                if ($checkInTime <= $lateTime) {
                    $points += 10; // On time
                } else {
                    $points += 5; // Late
                }
            }

            // Check out logic
            if ($att->check_out_time) {
                if ($att->logbook) {
                    $points += 10; // Check out + Logbook
                } else {
                    $points += 5; // Check out no logbook
                }
            }
        }
        
        return $points;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
