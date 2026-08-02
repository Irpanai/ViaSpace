<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find the latest file in storage/app/public/attendances
$files = glob(storage_path('app/public/attendances/*.*'));
if ($files) {
    // Sort by modification time
    array_multisort(array_map('filemtime', $files), SORT_NUMERIC, SORT_DESC, $files);
    $latestFile = $files[0];
    
    // Get relative path
    $relativePath = 'attendances/' . basename($latestFile);
    
    $attendance = App\Models\Attendance::where('user_id', 43)->latest()->first();
    if ($attendance) {
        $attendance->photo_path = $relativePath;
        $attendance->save();
        echo "Successfully assigned $relativePath to Irfan's attendance\n";
    }
} else {
    echo "No files found in storage/app/public/attendances\n";
}
