<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$attendance = App\Models\Attendance::where('user_id', 43)->latest()->first();
if ($attendance) {
    App\Models\Logbook::where('attendance_id', $attendance->id)->delete();
    
    $attendance->update([
        'check_out_time' => null,
        'check_out_lat' => null,
        'check_out_lng' => null,
        'check_out_photo_path' => null,
    ]);
    echo "Check-out reset successfully.\n";
} else {
    echo "No attendance found.\n";
}
