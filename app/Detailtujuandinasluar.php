<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailtujuandinasluar extends Model
{
    protected $table = 'tim_tujuan';
    protected $fillable = ['dinas_luar_id','fasyankes_id','status'];  
    public $timestamps  = false;

    public function fasyankes(){
        return $this->hasOne('App\FasyankesDt','id','fasyankes_id');
    }

    public function dinasluar(){
        return $this->hasOne('App\Headerdinasluar','id','dinas_luar_id');
    }

}
