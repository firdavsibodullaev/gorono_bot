<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bot_user_post_message', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_user_id');
            $table->foreignId('post_message_id');
            $table->string('status')->default('process');
            $table->dateTime('sent_at')->nullable();
            $table->unsignedBigInteger('message_id')->nullable();

            $table->unique(['bot_user_id', 'post_message_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_user_post_message');
    }
};
