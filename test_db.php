<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Challenge;
use Illuminate\Support\Facades\DB;

try {
    DB::table('challenges')->delete();
    Challenge::create([
        'name' => 'Test Challenge',
        'description' => 'Test Description',
        'xp_reward' => 100,
        'difficulty' => 'easy',
    ]);
    echo "Successfully created challenge\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
