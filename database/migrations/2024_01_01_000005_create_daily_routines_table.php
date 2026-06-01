<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRoutinesTable extends Migration
{
    public function up()
    {
        Schema::create('daily_routines', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('wake_time')->nullable();
            $table->time('sleep_time')->nullable();
            $table->decimal('study_hours', 4, 1)->default(0);
            $table->decimal('exercise_minutes', 5, 1)->default(0);
            $table->integer('mood')->default(3); // 1-5
            $table->text('summary')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_routines');
    }
}
