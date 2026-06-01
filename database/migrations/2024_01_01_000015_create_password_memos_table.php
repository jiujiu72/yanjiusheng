<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordMemosTable extends Migration
{
    public function up()
    {
        Schema::create('password_memos', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('url')->nullable();
            $table->string('username');
            $table->text('encrypted_password');
            $table->string('category')->default('other');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_memos');
    }
}
