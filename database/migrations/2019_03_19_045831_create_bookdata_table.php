<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookdata', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('title');
            $table->string('picture')->nullable();
            $table->text('detail')->nullable();

            $table->string('isbn');
            $table->string('title');
            $table->string('volume');
            $table->string('series');
            $table->string('publisher');
            $table->string('pubdate');
            $table->string('cover');
            $table->string('author');

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
        Schema::dropIfExists('bookdata');
    }
}
