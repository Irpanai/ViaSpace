<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

var_dump(env('FONNTE_TOKEN'));
$response = \App\Services\FonnteService::sendMessage('085849880281', 'Halo Admin! Ini adalah pesan uji coba dari sistem ViaSpace untuk memastikan notifikasi WhatsApp berjalan dengan lancar. Terima kasih!');
var_dump($response);
