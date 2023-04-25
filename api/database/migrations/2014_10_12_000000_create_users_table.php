<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\UserRole;
use App\Enums\UserStatus;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('role',array_map(
                fn (UserRole $term) => $term->value,
                UserRole::cases()
            ))->default(UserRole::USER->value);
            $table->enum('status',array_map(
                fn (UserStatus $term) => $term->value,
                UserStatus::cases()
            ))->default(UserStatus::ACTIVE->value);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('user_name')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
