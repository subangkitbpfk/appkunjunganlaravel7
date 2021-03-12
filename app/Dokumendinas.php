<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dokumendinas extends Model
{
  protected $table = 'dokumen_dinas';
  protected $fillable = ['dinas_luar_id','fasyankes_id','nama_dokumen','nama_dokumen_lama','path','keterangan'];
  public $timestamps  = false;

  public function dinasluar(){
    return $this->hasOne('App\Headerdinasluar', 'id', 'dinas_luar_id');
  }

  public function fasyankes(){
    return $this->hasOne('App\FasyankesDt', 'id', 'fasyankes_id');
  }
}
