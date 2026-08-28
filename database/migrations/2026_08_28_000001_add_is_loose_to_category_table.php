<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category', function (Blueprint $table) {
            $table->boolean('is_loose')->default(0)->after('show_home');
        });
    }

    public function down(): void
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn('is_loose');
        });
    }
};
