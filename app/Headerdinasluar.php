<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Headerdinasluar extends Model
{
    protected $table = 'header_dinas_luar';
    protected $fillable = ['id','tanggal_berangkat','tanggal_pulang','status'];
    public $timestamps  = false;

    public function pegawaidinasluar(){
        return $this->belongsTo('App\Detailpegawaidinasluar','id','dinas_luar_id');
    }
    public function tujuandinas(){
        return $this->belongsTo('App\Detailtujuandinasluar','id','dinas_luar_id');
    }
    
}
