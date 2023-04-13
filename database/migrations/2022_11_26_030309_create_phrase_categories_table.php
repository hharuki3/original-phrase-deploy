<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhraseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phrase_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('phrase_id');
            $table->unsignedBigInteger('category_id');
            $table->foreign('phrase_id')->references('id')->on('phrases');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phrase_categories');
    }
}
