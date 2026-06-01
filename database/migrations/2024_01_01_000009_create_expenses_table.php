<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('category', ['research', 'travel', 'equipment', 'books', 'printing', 'other'])->default('other');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->boolean('is_reimbursed')->default(false);
            $table->decimal('reimbursed_amount', 10, 2)->default(0);
            $table->date('reimbursed_at')->nullable();
            $table->string('receipt_note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
