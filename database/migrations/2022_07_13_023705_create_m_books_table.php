<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_books', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('m_category_id');
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->string('isbn')->nullable();
            $table->string('cover');
            $table->integer('stock');
            $table->string('description')->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_books');
    }
}
