<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_profiles', function (Blueprint $table) {
            $table->id('profiles_id');
            $table->foreignId('user_id')->constrained('users', 'id_users')->onDelete('cascade');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('phone', 14)->nullable();
            $table->boolean('is_member')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_profiles');
    }
};
