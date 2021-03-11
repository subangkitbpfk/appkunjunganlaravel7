<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailkontakkunjungan extends Model
{
  protected $table = 'detail_kontak';
  protected $fillable = ['dinas_luar_id','fasyankes_id','nama_kontak','jabatan_kontak','kontak_satu','kontak_dua'];
  public $timestamps  = false;

  public function headerdinas(){
    return $this->hasOne('App\Headerdinasluar', 'dinas_luar_id', 'id');
  }
  public function fasyankes(){
    return $this->hasOne('App\FasyankesDt', 'fasyankes_id', 'id');
  }
}
