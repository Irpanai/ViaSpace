<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::find(43);
if ($user) {
    print_r([
        'email' => $user->email,
        'role' => $user->role,
    ]);
    
    // Reset password just in case
    $user->password = \Illuminate\Support\Facades\Hash::make('password123');
    $user->save();
    
    echo "Password reset to password123\n";
} else {
    echo "User not found\n";
}
