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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->string('publicationYear');
            $table->enum('lang', ['en', 'fa', 'pa']);
            $table->string('edition');
            $table->string('translator')->nullable();
            $table->string('isbn');
            $table->string('code');
            $table->text('description')->nullable();
            $table->foreignId('cat_id')->constrained('categories', 'id');
            $table->foreignId('dep_id')->constrained('departments', 'id');
            $table->foreignId('sec_id')->constrained('sections', 'id');
            $table->enum('format', ['hard', 'pdf', 'both']);
            $table->enum('borrow', ['yes', 'no']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
