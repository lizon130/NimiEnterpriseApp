<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_notification')) {
            return;
        }

        Schema::create('user_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->string('receiver_id', 40);
            $table->string('type', 30)->default('general')->comment('order, notice');
            $table->string('title');
            $table->text('message');
            $table->string('url')->nullable();
            $table->tinyInteger('is_read')->default(0)->comment('0=Unread, 1=Read');
            $table->timestamps();

            $table->index(['receiver_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notification');
    }
};
