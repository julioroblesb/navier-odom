<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test what happens when we use updateOrCreate with bcrypt and 'hashed' cast
$user = \App\Models\User::updateOrCreate(
    ['email' => 'test_hash@gmail.com'],
    [
        'name' => 'Test Hash',
        'password' => bcrypt('Skyrote13'),
        'is_super_admin' => true,
        'tenant_id' => null,
    ]
);

echo "Inserted Hash: " . $user->password . "\n";
echo "Matches Skyrote13? " . (\Illuminate\Support\Facades\Hash::check('Skyrote13', $user->password) ? 'YES' : 'NO') . "\n";
