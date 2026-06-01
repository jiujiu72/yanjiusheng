<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('group', ['advisor', 'senior', 'peer', 'junior', 'other'])->default('peer');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('wechat')->nullable();
            $table->string('research_direction')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
