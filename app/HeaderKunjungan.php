<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeaderKunjungan extends Model
{
    protected $table    = 'header_kunjungan';
    protected $fillable = ['id','pimpinan','pegawai_id','mulai','sampai','status'];
    public $timestamps  = false;

    public function getHeaderDetailRs(){
      return $this->hasMany('App\HeaderDetailRs','header_kunjungan_id','id');
    }

    public function getDetailRsDeskripsi(){
      return $this->hasMany('App\DetailRsDeskripsi','header_kunjungan_id','id',);
    }

}
