<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $fillable = [
        'attendance_id',
        'category',
        'description',
        'link',
        'photo_path',
        'photo_path_2',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
