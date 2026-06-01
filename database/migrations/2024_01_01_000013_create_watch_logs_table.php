<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchLogsTable extends Migration
{
    public function up()
    {
        Schema::create('watch_logs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['tv', 'movie', 'anime'])->default('tv');
            $table->string('platform')->nullable();
            $table->string('episode')->nullable();
            $table->date('watch_date');
            $table->integer('rating')->nullable();
            $table->text('notes')->nullable();
            $table->string('source')->default('manual');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('watch_logs');
    }
}
