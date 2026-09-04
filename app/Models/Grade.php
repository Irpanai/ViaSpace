<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'year',
        'discipline_score',
        'teamwork_score',
        'skill_score',
        'average_score',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
