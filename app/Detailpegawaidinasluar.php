<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailpegawaidinasluar extends Model
{
    protected $table = 'bm_dinas_luar';
    protected $fillable = ['dinas_luar_id','nip'];
    public $timestamps  = false;

    public function pegawai(){
        return $this->hasOne('App\Pegawai','id','nip');
    }

    
}
