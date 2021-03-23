<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FasyankesDt extends Model
{
  protected $table    = 'fasyankes_dt';
  protected $fillable = ['nama','alamat','provinsi','kota','telp','email'];
  public $timestamps  = false;

}
