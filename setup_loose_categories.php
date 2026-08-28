<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;

// Get all categories
$categories = Category::all();

if ($categories->isEmpty()) {
    echo "No categories found in the database.\n";
    exit;
}

echo "=== Available Categories ===\n";
foreach ($categories as $cat) {
    $status = $cat->is_loose ? '✓ LOOSE' : '  Normal';
    echo "[{$cat->id}] {$cat->name} - {$status}\n";
}

echo "\n=== Setting first 3 root categories as loose ===\n";

$looseCount = 0;
$rootCategories = Category::whereNull('parent_category')->take(3)->get();

foreach ($rootCategories as $cat) {
    $cat->is_loose = 1;
    $cat->save();
    $looseCount++;
    echo "✓ Category '{$cat->name}' (ID: {$cat->id}) is now marked as LOOSE\n";
}

echo "\n✓ Total {$looseCount} categories marked as loose!\n";
echo "\nYou can now see the 'Loose Products' tab on the products page.\n";
echo "\nTo mark more categories as loose, use:\n";
echo "php artisan tinker\n";
echo ">>> \$cat = App\Models\Category::find(5); \$cat->is_loose = 1; \$cat->save();\n";