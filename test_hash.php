<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if ($user) {
    echo "Password in DB: " . $user->password . "\n";
    echo "Matches Skyrote13? " . (\Illuminate\Support\Facades\Hash::check('Skyrote13', $user->password) ? 'YES' : 'NO') . "\n";
} else {
    echo "No user found.\n";
}
