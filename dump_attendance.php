<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$attendance = App\Models\Attendance::where('user_id', 43)->latest()->first();
if ($attendance) {
    print_r([
        'id' => $attendance->id,
        'date' => $attendance->date,
        'photo_path' => $attendance->photo_path,
    ]);
} else {
    echo "No attendance found for Irfan\n";
}
