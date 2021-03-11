<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeaderDetailRs extends Model
{
  protected $table    = 'header_detail_rs';
  protected $fillable = ['header_kunjungan_id','fasyankes_dt_id'];
  public $timestamps  = false;

  public function getFasyankes_dt(){
    return $this->hasMany('App\FasyankesDt','id','fasyankes_dt_id');
  }
}
