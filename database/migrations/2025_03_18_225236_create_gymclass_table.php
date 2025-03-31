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
        Schema::create('gym_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('location');
            $table->decimal('price', 8, 2);
            $table->integer('duration');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('max_participants');
            $table->timestamps();
        });

        Schema::create('gym_class_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_user');
        Schema::dropIfExists('gym_classes');
    }
};
