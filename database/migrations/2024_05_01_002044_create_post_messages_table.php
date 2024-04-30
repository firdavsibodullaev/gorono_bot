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
        Schema::create('post_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_user_id');
            $table->jsonb('file_ids')->nullable();
            $table->text('text')->nullable();
            $table->jsonb('entities')->nullable();
            $table->unsignedBigInteger('progress_message_id')->nullable();
            $table->boolean('is_ready_for_post')->default(false);
            $table->boolean('is_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_messages');
    }
};
