<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\HeaderKunjungan;
use App\HeaderDetailRs;
use App\FasyankesDt;
use App\DetailRsDeskripsi;
use App\Pegawai;
use App\Poto;
use Carbon\Carbon;
use App\Headerdinasluar as hd;
use App\Detailpegawaidinasluar as dptl;
use App\Detailtujuandinasluar as dtdl;
use App\Detailkontakkunjungan as detailkontak;
use App\Dokumendinas as dk;
use App\Detaildinasluarhasil as ddlh;


class KunjunganController extends Controller
{
    public function forminputdinas(){
      $datas = FasyankesDt::orderBy('nama','ASC')->get();
      return view('formkunjungan.index',compact('datas'));
    }
    /*ambil berkas json*/
    public function ambil_berkas($id){
      $data = dk::where('fasyankes_id',$id)->get();
      if(empty($data)){
        return "kosong";
      }
      return $data;
    }

    public function ambil_kontak($id){

      $data['detailkontak'] = detailkontak::where('fasyankes_id',$id)->get();
      $data['fasyankes'] = FasyankesDt::where('id',$id)->get();
      if(empty($data)){
        return "kosong";
      }
      return $data;
    }



    public function tampil_berkas(){

    }

    // fasyankesdl untuk json edit data
    public function fasyankesdl($id){
      $data = dtdl::where('dinas_luar_id',$id)->get();
      // foreach ($data as $dt){
      //   dd($dt->fasyankes);
      // }


      // foreach($data as $dt){
      //   $dt['data'] = FasyankesDt::where('id',$dt->fasyankes_id)->get();
      // }
      return view('formkunjungan.ubah.index');

    }

    public function postlaporandinas(Request $request){
      // dd($request->all());
      // dd(intval($request->faskes_id[0]));
      /* insert hasil dinas luar */
      $insertddlh  = ddlh::create([
                        'dinas_luar_id' =>$request->kodedinasluar,
                        'fasyankes_id' =>$request->faskes_id[0],
                        'hasil_dinas' =>$request->hasildinasluar
                      ]);
      if(!$insertddlh){
        dd("gagal");
      }
      /* insert dokumen dinas */
      $tujuan_upload = 'upload_dokumen';
      if(!$request->file('berkas')){//validasi berkas harus diisi
        dd("berkas harus diisi");
      }
      if(count($request->file('berkas'))&& $request->berkas !== ''){
          for($m=0;$m<count($request->file('berkas'));$m++){
            //lakukkan insert disini
            $file = $request->file('berkas')[$m];
            $nama_old = $request->file('berkas')[$m]->getClientOriginalName();
            $ext = $request->file('berkas')[$m]->getClientOriginalExtension();//extension
            $nama_file = "bpfksby-".$m."-".time()."_".$request->kodedinasluar."_".date("Ymd_His").".".$ext;
            // dd($nama_file);
            $file->move('upload_dokumen',$nama_file);
            $filePath= 'upload_dokumen/'.$nama_file; //namepath
            $real_patch = $request->file('berkas')[$m]->getRealPath();

            $insertberkas = dk::create([
              'dinas_luar_id' => $request->kodedinasluar,
              'fasyankes_id' => $request->faskes_id[0],
              'nama_dokumen_lama' => $nama_old,
              'nama_dokumen' => $nama_file,
              'path' => $filePath,
              'keterangan' => $request->keteranganberkas[$m]
            ]);
          }

          if(!$insertberkas){
            dd("gagal upload berkas");
          }

      }else{
        dd("berkas harus diisi");
      }
      /*end if insert berkas */

      /*kondisi perhitungan array pada table kontak acuan saya ambil nama*/
      if(!empty($request->id)){
          for($i=0;$i<count($request->id);$i++){
            // insert data document
            $insert = detailkontak::create([
                      'dinas_luar_id' => $request->kodedinasluar,
                      'fasyankes_id' => $request->faskes_id[0],//fasyankes
                      'nama_kontak' => $request->id[$i],
                      'jabatan_kontak' => @$request->namajabatan[$i],
                      'kontak_satu' => @$request->namakontaksatu[$i],
                      'kontak_dua' => @$request->namakontakdua[$i],
                    ]);
          }
          if(!$insert){
            dd("gagal");
          }
      }//end if
      //update tabel tim_yang_dituju status = 1 (sudah diinput)
      $affectedRows = dtdl::where('fasyankes_id', intval($request->faskes_id[0]))->update(['status' => 1]);
      if(!$affectedRows){
        dd("gagal update status tim tujuan");
      }


      /* insert tabel persetujuan */
      if($insertddlh){
        return redirect()->back()->with('success', "Data sudah di masukkan dengan sukses!");
      }


    }


    public function getffdinas($id){
      $data = dtdl::where('dinas_luar_id',$id)->get();
      $map = $data->map(function($item,$i){
        $item->nama = FasyankesDt::where('id',$item->fasyankes_id)->first();
        $item->dinasluar = hd::where('id',$item->dinas_luar_id)->first();
        $item->pegawai = dptl::where('dinas_luar_id',$item->dinas_luar_id)->get();
        $item->pegawai->map(function($a,$b){
          return $a->pegawai;
        });
        return $item;
      })->toArray();

      return response()->json($map);
    }

    public function postinputdinas(Request $request){
      /* masukkan tabel header */
      $dtheader = new hd;
      $dtheader->tanggal_berangkat = $request->mulaitanggal;
      $dtheader->tanggal_pulang = $request->sampaitanggal;
      $dtheader->status = 0;
      $dtheader->save();
      // dd($dtheader->id);//id terakhir
      /* masukkan tabel detail pegawai dinas luar */
      for($i=0;$i<count($request->pegawai);$i++){
         $statuspegawai = dptl::create([
                        'dinas_luar_id' => $dtheader->id,
                        'nip' => $request->pegawai[$i]
                      ]);
          // dd($statuspegawai);
        // dd($request->pegawai);
      }
      /* masukkan tabel detail tujuan dinas luar */
      for($i=0;$i<count($request->fasyankes_id);$i++){
        $statusfasyankes = dtdl::create([
                        'dinas_luar_id' => $dtheader->id,
                        'fasyankes_id' => $request->fasyankes_id[$i],
                        'status' => 0
                        ]);
      }
      return redirect()->back()->with('success', "Data Sudah di Masukkan dengan sukses!");
    }

    public function viewpostinputdinas(){//view dinas
      $data = hd::all();
      // dd($data);

      foreach($data as $dt){
        // dd($dt->tujuandinas);
        // dd($pegawais);
        // dd($dt->pegawaidinasluar->pegawai);
      }
      // dd($pegawais);
      return view('formkunjungan.view',compact('data'));

    }

    public function viewpostlaporandinas(){
      //data fasyankes yang sudah di entry
      $data = dtdl::orderBy('dinas_luar_id','DESC')->where('status','1')->get();
      return view('formlaporandinas.view',compact('data'));
    }

    public function formlaporandinas(){
      $dtinputandinas = hd::all();
      return view('formlaporandinas.index',compact('dtinputandinas'));
    }
    public function index(){

      // $dtfaskesdikunjungi =

      /* jumlah fasyankes yang dikunjungi */

       /* berkas yang sudah di input */

       /* jumlah kontak yang sudah didapat*/

      /* pegawai yang melakukan dl*/

      return view('kunjungan.index');
    }

    public function viewkunjungan(){
      $map = HeaderKunjungan::orderBy('id','DESC')->get();
      $gabungan = array();
      $rumat = $map->map(function($item,$i)use($gabungan){
        $item->gabungan = explode(" ",$item->pegawai_id);//array gabungan
        $item->panjang = count($item->gabungan)-1;
        $item->getHeaderDetailRs->map(function($a,$b)use($gabungan){
          $a->getFasyankes_dt->map(function($c,$d)use($gabungan){
            // $c->gabungan .= $c->nama.",";
            //dd($c->nama);
            return $c;
          });
        })->toArray();
        return $item;
      })->toArray();
      // dd($rumat);
      return view('kunjungan.view',compact('rumat'));
    }

    public function simpanfoto(Request $request){ //save foto
      // dd($request->all());
      $data = $request->all();
      $file = $request->file('upload');
      $nama_gambar = $file->getClientOriginalName();
      $tipe_file = $file->getClientOriginalExtension();
      $real_patch = $file->getRealPath();
      $ukuran = $file->getSize();
      $mimetipe = $file->getMimeType();

      $nama_file = time()."_".$data['id']."_".$file->getClientOriginalName();
      $tujuan_upload = 'data_file';
      $file->move($tujuan_upload,$nama_file);

      Poto::create([
        'header_kunjungan_id'=> $data['id'],
        'mime' => $mimetipe,
        'link_folder' => '',
        'link_name' => $nama_file
      ]);
      return redirect()->back();
    }

    public function headerkunjungan_json(){
      $data = HeaderKunjungan::all();
      return view('',compact(''));
    }
    public function tambahkunjungan(){
      $fasyankesdt = DB::table('fasyankes_dt')->get();
      $pegawai = DB::table('pegawai')->get();
      return view('kunjungan.addkunjungan',compact('fasyankesdt','pegawai'));
    }

    public function fasyankesdt_id($id){
      $fasyankesdt = DB::table('fasyankes_dt')->where('id',$id)->first();
      return response()->json($fasyankesdt);
    }
    // get pegawai
    public function getpegawai(){
      $data = Pegawai::all();
      return response()->json($data);
    }

    public function lihatdetaildeskripsi($id){
      $data = HeaderKunjungan::finOrFail($id);
      return response()->json($data);
    }

    public function simpankunjunganheader(Request $request){// untuk header
      $dataku = $request->all();
      $dt = '';
      $idpegawai = '';
      // dd($dataku);
      // dd($map);
      // dd($dataku);
      /* pengecekkan dan penggabungan pegawai */
      if(isset($dataku['states'])){
        foreach ($dataku['states'] as $dt) {
          $idpegawai .= $dt.' ';
        }
        // $pegawaiku = json_encode($dataku['states']);
      }else{
        $pegawaiku = "-";
      }
      // dd($idpegawai);
      // dd($dataku);
      /* insert header tabel kunjungan */
      $headerkunjungan = HeaderKunjungan::create([
        'pimpinan' => 1,
        'pegawai_id' => $idpegawai,
        'mulai' => \Carbon\Carbon::parse($dataku['mulaitanggal']),
        'sampai' =>\Carbon\Carbon::parse($dataku['akhirtanggal']),
      ]);
      // insert header detail rsrumahsakit
      if(isset($dataku['rsdetail'])){ //pengecekkan array rumahsakit
        foreach($dataku['rsdetail'] as $value) {
          $header_detail_rs = HeaderDetailRs::create([
            'header_kunjungan_id' => $headerkunjungan->id,
            'fasyankes_dt_id' => $value
          ]);
        }
      }else{
        dd("data array kosong");
      }
      // cek headerkunjungan tampilkan detailnya ke json
      if(isset($dataku['rsdetail'])){
        $mapRs = collect($dataku['rsdetail'])->map(function($item,$i)use($dt){
            $dt = FasyankesDt::where('id',$item)->first();
            // dd($dt);
            return $dt;
        })->toArray();
      }
      $kunjungan_id = $headerkunjungan->id;
      $message = array(
                  'status' => '1',
                  'msg' => 'succesS',
                  'mapRs'=> $mapRs,
                  'headerkunjungan' => $kunjungan_id
                );
      // return response()->json($mapRs);
      return response()->json($message);
    }

    public function simpandetailrs(Request $request){
      $data = $request->all();
      // dd($request->file('fileupload'));

      // $file = $request->file('fileupload');
      // $ext = $file->getClientOriginalExtension();
      // $newName = "dokumen_kunjungan_".date("Ymd_His").".".$ext;
      // $file->move('uploads/file',$newName);
      // $filePath= 'uploads/file/'.$newName;

      // dd($data['namars'][0]);
      // ambil nilai fix array dari id karena pasti anda
      $jumArray = count($data['namars']);

      // dd($jumArray);
      for($i=0;$i<$jumArray;$i++){
        /* upload dokumen */
        $file = $request->file('fileupload')[$i];
        $ext = $file->getClientOriginalExtension();
        $newName = "dokumen_kunjungan_".date("Ymd_His").".".$ext;
        $file->move('upload_dokumen',$newName);
        $filePath= 'upload_dokumen/'.$newName; //namepath

        /*insert database*/
          $insert = DetailRsDeskripsi::create([
            'header_kunjungan_id' => intval($data['headerkunj_id'][$i]),
            'fasyankes_dt_id' => intval($data['namars'][$i]),
            'petugas' => $data['petugas'][$i],
            'hp1' => $data['hp'][$i],
            'email' => $data['email'][$i],
            'detail_penyelesaian' => $data['detailpenyelesaian'][$i],
            'informasi_simponi' => $data['informasisimponi'][$i],
            'keterangan' => '-',
            'url_berkas' => $filePath,
            'oldname_berkas' => '-'
          ]);

          // dd($insert);
        }
        if($insert){
          $message = array(
                      'status' => '1',
                      'msg' => 'success',
                    );
          }
        // update status header kunjungan menjadi 2 biar kebuka di tabel

      return response()->json($message);
    }

    public function cetakkunjungan(Request $request,$id){
      // dd(intval($id));
      $tampung = array();
      $namars = array();
      $data = HeaderKunjungan::where('id',intval($id))->get();

      $map = $data->map(function($item,$i)use($namars){
        $item->getHeaderDetailRs->map(function($a,$b){
        })->toArray();
        return $item;
      })->toArray();
      $pegawai_id = explode(" ",$data[0]->pegawai_id);


      // test memakai first
      $dataDeskripsi = HeaderKunjungan::where('id',intval($id))->first();
      if($dataDeskripsi){
        $des = $dataDeskripsi->getDetailRsDeskripsi->map(function($item,$i){
          // dd($item->fasyankes_dt_id);
          $item->fasyankes = FasyankesDt::where('id',$item->fasyankes_dt_id)->get();
          return $item;
        })->toArray();
        // dd($des);
      }else{

      }

      $mulai = $this->dateIdn(carbon::parse($dataDeskripsi['mulai']));
      $sampai = $this->dateIdn(carbon::parse($dataDeskripsi['sampai']));
      return view('kunjungan.printkunjungan',compact('data','pegawai_id','map','dataDeskripsi','des','mulai','sampai'));
    }

    public function rsid($id){
      dd("test ini". $id);
    }



    public function tjheader(Request $request){
      dd("tjheader");
    }


    public function store(Request $request){

    }

    public function showedit($id){

    }

    public function update(Request $request){

    }

    public function delete(Request $id){

    }

    // function dayIdn

    public function dateIdn($date = array())
      {
          $date = explode(' ', $date);
          // dd($date);
          if (count($date) < 3) {
              $date = explode('-', $date[0]);
              // dd($date[1]);
              // dd(count($date));
              if (count($date) < 3) {
                  // dd(count($date));
                  $date = explode('-', '2016-01-01');
              } else {

              }
          }
          $month = $this->monthIdn($date[1]);
          // dd($month);
          $final = $date[2] . " " . $month . " " . $date[0];
          return $final;
      }

      public function monthIdn($month = array())
          {
              switch ($month) {
                  case '1':
                      return "Januari";
                  case '2':
                      return "Februari";
                  case '3':
                      return "Maret";
                  case '4':
                      return "April";
                  case '5':
                      return "Mei";
                  case '6':
                      return "Juni";
                  case '7':
                      return "Juli";
                  case '8':
                      return "Agustus";
                  case '9':
                      return "September";
                  case '10':
                      return "Oktober";
                  case '11':
                      return "November";
                  case '12':
                      return "Desember";
              }
}






}
