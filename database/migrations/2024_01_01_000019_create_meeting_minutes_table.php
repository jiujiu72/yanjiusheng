<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingMinutesTable extends Migration
{
    public function up()
    {
        Schema::create('meeting_minutes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['online_academic', 'offline_academic', 'group_meeting']);
            $table->string('topic');
            $table->text('attendees')->nullable();
            $table->text('action_items')->nullable();
            $table->text('conclusions')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('research_project_id')->nullable();
            $table->dateTime('meeting_time');
            $table->integer('duration_minutes')->nullable();
            $table->timestamps();

            $table->foreign('research_project_id')
                  ->references('id')
                  ->on('research_projects')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('meeting_minutes');
    }
}
