<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
  public function scopeUserGetBook()
  {
      $property = Property::where('user_id', Auth::user()->id)
                      ->join('bookdata','bookdata.id','=','properties.bookdata_id')
                      ->select('properties.id','title','picture','cover','freememo')
                      ->get();
      return $property;
  }
  public function scopeUserNothaveBook()
  {
      $haveProperties = Property::where('user_id', [Auth::user()->id])->get('bookdata_id');
      $notProperties = Bookdata::whereNotIn('id', $haveProperties)
                      ->get();
      return $notProperties;
  }
  public static $searchRules = array(
    'find' => 'required'
  );
}
