<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiteraturesTable extends Migration
{
    public function up()
    {
        Schema::create('literatures', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('authors')->nullable();
            $table->string('journal')->nullable();
            $table->integer('year')->nullable();
            $table->string('doi')->nullable();
            $table->text('abstract')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['unread', 'reading', 'finished'])->default('unread');
            $table->integer('rating')->default(0);
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('literatures');
    }
}
