<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
  protected $guarded = array('id');
  public static $rules = array(
    'user_id' => 'required',
    'bookdata_id' => 'required'
  );
}
