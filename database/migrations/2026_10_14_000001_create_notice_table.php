<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notice', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('description');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamps();
        });

        // Register notice permissions and grant them to the admin role (role_id = 1)
        $now = now();
        $rights = ['view', 'create', 'edit', 'delete'];

        foreach ($rights as $right) {
            $rightName = 'notice.' . $right;

            $exists = DB::table('right')->where('name', $rightName)->exists();
            if (!$exists) {
                DB::table('right')->insert([
                    'name' => $rightName,
                    'module' => 'notice',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $rightId = DB::table('right')->where('name', $rightName)->value('id');
            $grantExists = DB::table('role_right')
                ->where('role_id', 1)
                ->where('right_id', $rightId)
                ->exists();
            if (!$grantExists) {
                DB::table('role_right')->insert([
                    'role_id' => 1,
                    'right_id' => $rightId,
                    'permission' => 1,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notice');

        $rightIds = DB::table('right')->where('name', 'like', 'notice.%')->pluck('id');
        DB::table('role_right')->whereIn('right_id', $rightIds)->delete();
        DB::table('right')->where('name', 'like', 'notice.%')->delete();
    }
};
