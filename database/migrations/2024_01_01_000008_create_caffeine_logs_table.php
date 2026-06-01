<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaffeineLogsTable extends Migration
{
    public function up()
    {
        Schema::create('caffeine_logs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->enum('type', ['coffee', 'black_tea', 'green_tea', 'milk_tea', 'other'])->default('coffee');
            $table->string('name')->nullable();
            $table->integer('caffeine_mg')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('caffeine_logs');
    }
}
