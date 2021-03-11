<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailRsDeskripsi extends Model
{
  protected $table    = 'detail_rs_deskripsi';
  protected $fillable = ['header_kunjungan_id','fasyankes_dt_id','petugas','hp1','email','detail_penyelesaian','informasi_simponi','keterangan','url_berkas','oldname_berkas'];
  public $timestamps  = false;

  public function fasyankes(){
    return $this->belongsTo('App\FasyankesDt','id','fasyankes_dt_id');
  }
  
}
