<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookdata extends Model
{
  protected $table = 'bookdata';
  protected $guarded = array('id');
  public static $rules = array(
    'title' => 'required'
  );
}
