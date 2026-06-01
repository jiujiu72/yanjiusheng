<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['weekly', 'monthly'])->default('weekly');
            $table->string('title');
            $table->date('period_start');
            $table->date('period_end');
            $table->text('achievements')->nullable();
            $table->text('problems')->nullable();
            $table->text('next_plan')->nullable();
            $table->text('content')->nullable();
            $table->integer('rating')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
