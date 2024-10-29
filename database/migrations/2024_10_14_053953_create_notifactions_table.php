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
        Schema::create('notifactions', function (Blueprint $table) {
            $table->id();
            $table->string('all_books');
            $table->string('pdf_books');
            $table->string('hard_books');
            $table->string('borrowable_books');
            $table->string('reservable_books');
            $table->string('active_users');
            $table->string('new_regisered_users');
            $table->string('all_taken_books');
            $table->string('time_expired_users');
            $table->string('all_fine');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifactions');
    }
};
