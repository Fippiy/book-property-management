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
  public function user()
  {
    return $this->belongsTo('App\User');
  }
  public function bookdata()
  {
    return $this->belongsTo('App\Bookdata');
  }
}
