<?php
$user = User::where('email', 'ahmadalifariz@viaspace.com')->first();
if ($user) {
    $user->update(['name' => 'Alifariz', 'email' => 'alifariz@viaspace.com']);
}
echo "Tinker done\n";
