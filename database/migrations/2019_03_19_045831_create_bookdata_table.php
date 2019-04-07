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
            $table->string('picture')->nullable();
            $table->text('detail')->nullable();

            // isbn情報反映用
            $table->string('isbn')->nullable();
            $table->string('title');
            $table->string('volume')->nullable();
            $table->string('series')->nullable();
            $table->string('publisher')->nullable();
            $table->string('pubdate')->nullable();
            $table->string('cover')->nullable();
            $table->string('author')->nullable();

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
