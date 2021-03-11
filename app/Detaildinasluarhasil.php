<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detaildinasluarhasil extends Model
{
    protected $table = 'detail_dinas_luar';
    protected $fillable = ['dinas_luar_id','fasyankes_id','hasil_dinas'];
    public $timestamps  = false;

    public function fasyankes(){
        return $this->hasOne('App\FasyankesDt', 'fasyankes_id', 'id');
    }
}
