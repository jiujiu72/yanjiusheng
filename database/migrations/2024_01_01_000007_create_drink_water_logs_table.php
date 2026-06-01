<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrinkWaterLogsTable extends Migration
{
    public function up()
    {
        Schema::create('drink_water_logs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->integer('amount'); // ml
            $table->enum('type', ['warm_water', 'cold_water', 'other'])->default('warm_water');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drink_water_logs');
    }
}
