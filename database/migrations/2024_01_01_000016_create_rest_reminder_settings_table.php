<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestReminderSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('rest_reminder_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('first_reminder_minutes')->default(45);
            $table->integer('second_reminder_minutes')->default(90);
            $table->integer('snooze_minutes')->default(10);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rest_reminder_settings');
    }
}
