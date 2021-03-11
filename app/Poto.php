<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poto extends Model
{
  protected $table    = 'poto';
  protected $fillable = ['header_kunjungan_id','mime','link_folder','link_name','status'];
  public $timestamps  = false;
}
