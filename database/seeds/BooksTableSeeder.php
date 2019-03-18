<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $param = [
        'title' => 'はじめてのPHP',
        'picture' => 'php1.jpg',
      ];
      DB::table('books')->insert($param);

      $param = [
        'title' => 'それなりのPHP',
        'picture' => 'php2.jpg',
      ];
      DB::table('books')->insert($param);

      $param = [
        'title' => 'けっこう進んだのPHP',
        'picture' => 'php3.jpg',
      ];
      DB::table('books')->insert($param);

      $param = [
        'title' => 'かなり進んだのPHP',
        'picture' => 'php1.jpg',
      ];
      DB::table('books')->insert($param);


    }
}
