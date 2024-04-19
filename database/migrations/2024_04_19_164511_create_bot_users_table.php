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
        Schema::create('bot_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_id')->nullable();
            $table->unsignedBigInteger('chat_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->foreignId('school_id')->nullable()->constrained('schools');
            $table->string('status')->nullable();
            $table->string('language')->nullable();
            $table->boolean('is_registered')->default(false);
            $table->timestamps();

            $table->unique(['from_id', 'chat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_users');
    }
};
