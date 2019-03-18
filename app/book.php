<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class book extends Model
{
  protected $table = 'books';
  protected $guarded = array('id');
  public static $rules = array(
    'title' => 'required'
  );
}
