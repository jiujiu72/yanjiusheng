<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommuteSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('commute_settings', function (Blueprint $table) {
            $table->id();
            $table->string('city')->default('Beijing');
            $table->time('morning_commute')->default('08:30');
            $table->time('evening_commute')->default('21:00');
            $table->integer('reminder_minutes_before')->default(30);
            $table->boolean('rain_alert')->default(true);
            $table->boolean('heat_alert')->default(true);
            $table->boolean('cold_alert')->default(true);
            $table->integer('heat_threshold')->default(35);
            $table->integer('cold_threshold')->default(0);
            $table->text('notes')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commute_settings');
    }
}
