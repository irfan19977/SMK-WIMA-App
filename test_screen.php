<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Test cache functionality
use Illuminate\Support\Facades\Cache;

$screenShareId = 'test-session-id';

// Simulate teacher broadcasting frame
$testFrame = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A8A';

Cache::put("screen_frame_{$screenShareId}", [
    'image_data' => $testFrame,
    'timestamp' => now()->toISOString(),
    'teacher_name' => 'Test Teacher'
], 10);

echo "Frame stored in cache\n";

// Simulate student retrieving frame
$frame = Cache::get("screen_frame_{$screenShareId}");

if ($frame) {
    echo "Frame retrieved from cache\n";
    echo "Image data length: " . strlen($frame['image_data']) . "\n";
    echo "Timestamp: " . $frame['timestamp'] . "\n";
    echo "Teacher: " . $frame['teacher_name'] . "\n";
} else {
    echo "No frame found in cache\n";
}

?>
