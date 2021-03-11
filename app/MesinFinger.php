<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MesinFinger extends Model
{
    protected $table = 'mesin_finger';
    protected $fillable = ['pin','tanggal','verifikasi','status'];
    // public $timestamp = true;
    public $timestamps = false;
}
