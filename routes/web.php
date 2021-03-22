<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

/* keterangan role
1. admin
2. pegawai
3. kepaladivisi
4. kepalabpfk
*/

// http://localhost/blog/public/register -> Register
// Route::get('kunjungan','KunjunganController@index');

Route::get('tambah-kunjungan','KunjunganController@addkunjungan');


Route::get('barcode','EmailController@barcode');
Route::get('laporan-pdf','EmailController@generatePDF');
Route::get('testzk','EmailController@testzk');
Route::get('savemesin','EmailController@save_mesin');


Route::get('send', 'EmailController@send');

Route::get('map','EmailController@map');

Route::get('/notify', function () {

    $user = \App\User::find(1);
    // dd($user);
    $details = [
            'greeting' => 'Hi Artisan',
            'body' => 'This is our example notification tutorial',
            'thanks' => 'Thank you for visiting codechief.org!',
    ];

    $data = $user->notify(new \App\Notifications\TaskComplete($details));
    // dd($data);
    return dd("Done");
});

Route::get('/markAsRead', function(){

    auth()->user()->unreadNotifications->markAsRead();

    return redirect()->back();

})->name('mark');




Route::get('/', function () {
    return view('auth.login');
    // return route('login');

    // posisi sudah di useraktif login
    // auth()->user()->assignRole('pegawai');

    // pengecekkan auth()-> user()->hasRole('admin')

    // remove roles auth()->user()->removeRole('admin')

    // untuk memberikan role auth()->user()->assignRole('admin')



    if(auth()->user()->hasRole('admin')){
    	return 'admin';
    }else{
    	return 'other';
    }
});

/*

// 1 controller tidak bisa diakses 2 role



*/
/* Role admin */
Route::group(['middleware' => ['role:admin']], function () {
// dtjson
  Route::get('fasyankesdt_json','KunjunganController@fasyankesdt_json');
  Route::get('headerkunjungan_json','KunjunganController@headerkunjungan_json');
  Route::get('fasyankes_json/{id}','KunjunganController@fasyankesdt_id');

  /*ui inputan mas denny*/
  Route::get('get-pegawai','KunjunganController@getpegawai');

  Route::get('form-input-dinas','KunjunganController@forminputdinas');
  Route::post('form-input-dinas','KunjunganController@postinputdinas');
  Route::get('view-input-dinas','KunjunganController@viewpostinputdinas');

  Route::get('pegawai_json/{id}','KunjunganController@pegawai_id');
  Route::get('fasyankesdl_json/{id}','KunjunganController@fasyankesdl');

  Route::get('form-laporan-dinas','KunjunganController@formlaporandinas');
  Route::post('form-laporan-dinas','KunjunganController@postlaporandinas');
  Route::get('view-laporan-dinas','KunjunganController@viewpostlaporandinas');
  Route::get('getfasyankesfromdinas/{id}','KunjunganController@getffdinas');

  Route::get('timtujuan/{id}','KunjunganController@gettimtujuan');

  /*laporan*/
   Route::get('laporan','KunjunganController@laporanindex');
   Route::post('laporanrelease','KunjunganController@laporanrelease');
   /*cek laporan ke 1*/
   Route::get('laporan/{id}','KunjunganController@cetaklaporanuser');


  /* untuk edit data pada tabel pegawai view-input-dinas*/
  Route::get('get-pegawai-selected/{fasyankes}/get/{nip}','KunjunganController@getnipselected');
  Route::get('get-fasyankes-selected/{fasyankes}/get/{nip}','KunjunganController@getidfasyankes');


  /*pengecekkan berkas dan amnil berkas */
  route::get('ambil_berkas/{id}','KunjunganController@ambil_berkas');
  /*end pengecekkan dan amnbil berkas */
  route::get('ambil_kontak/{id}','KunjunganController@ambil_kontak');
  /* update pegawai*/
  route::get('edit_pegawai/{id}','KunjunganController@edit_pegawai');
  /*admin */

// end dtjson
  Route::get('laporan-kunjugan','LaporanKunjunganController@index');
  Route::get('halamanutama','KunjunganController@index');
  Route::get('kunjungan','KunjunganController@viewkunjungan');
  Route::get('tambah-kunjungan','KunjunganController@tambahkunjungan');
  Route::post('upload-foto','KunjunganController@simpanfoto');
  Route::post('simpan-kunjungan-header','KunjunganController@simpankunjunganheader');
  Route::post('tjheader','KunjunganController@tjheader');//json untuk tjheader
  Route::post('simpan-detail-deskripsi','KunjunganController@simpandetailrs');//simpan deskripsi rumahsakit

  // upload test
  Route::post('simpan-detail-deskripsi','KunjunganController@simpandetailrs');//simpan deskripsi rumahsakit
  //
  Route::get('cetak-kunjungan/{id}','KunjunganController@cetakkunjungan');
});
/* end Role admin
//


/* Role pegawai */
Route::group(['middleware' => ['role:pegawai']], function () {
    Route::get('/testpegawai','Test@pegawai');
});
/* end Role pegawai*/


/* Role Kepala divisi */
Route::group(['middleware' => ['role:kepaladivisi']], function () {
	// Route::get('/template','Test@index');

});
/* End Role Kepala Divisi */


/* Role Kepala BPFK */
Route::group(['middleware' => ['role:kepaladbpfk']], function () {
	// Route::get('/template','Test@index');
});
/* End Role Kepala BPFK */




Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

 //Route::get('admin-page','KunjunganController@index')->middleware('role:admin')->name('admin.page');
// Route::get('admin-page', function() {
//   return 'Halaman untuk admin';
// })->middleware('role:admin')->name('admin.page');

Route::get('divisi-page', function() {
  return 'Halaman untuk kepaladivisi';
})->middleware('role:kepaladivisi')->name('kepaladivisi.page');

Route::get('kepalabpfk-page', function() {
  return 'Halaman untuk kepaladivisi';
})->middleware('role:kepaladivisi')->name('kepalabpfk.page');




// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
