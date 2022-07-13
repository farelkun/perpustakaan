<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('admin_id');
            $table->bigInteger('user_id');
            $table->dateTime('borrow_date');
            $table->dateTime('return_date')->nullable();
            $table->double('penalty')->default(0);
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
