<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePomodorosTable extends Migration
{
    public function up()
    {
        Schema::create('pomodoros', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('task')->nullable();
            $table->integer('duration')->default(25); // minutes
            $table->boolean('completed')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pomodoros');
    }
}
