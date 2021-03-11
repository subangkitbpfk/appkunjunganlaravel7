<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Test extends Controller
{
    public function index(){
      dd("test");
      // return view('template');
      // dd("test");
    }

    public function testpegawai(){
    	return "testhalamanpegawai";
    }
}
