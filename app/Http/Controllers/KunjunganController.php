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
    public function laporanindex(){//laporan
      $dtheader = hd::orderBy('id','DESC')->get();
      return view('laporan.index',compact('dtheader'));
    }
    public function cetaklaporanuser($id){//cetak laporan user
      $dtlaporan = hd::where('id',$id)->first();
      $data['data'] = $dtlaporan;
      $data['fasyankes'] = dtdl::where('dinas_luar_id',$id)->get();
      $cekstatus = collect($data['fasyankes'])->map(function($item,$i){
                    $item->namafasyankes = FasyankesDt::where('id',$item->fasyankes_id)->first();//ambil nama untuk ditampilkan
                    return $item;
      });
      $data['cekstatus'] = $cekstatus;
      // $data['pegawai'] = dptl::where('dinas_luar_id')->get();
      return response()->json($data);
    }

    public function laporanrelease(Request $request){
      $data = hd::where('id',$request->dinas_luar_id)->first();
      $dt['data'] = $data;
      $dt['fasyankes'] = dtdl::where('dinas_luar_id',$request->dinas_luar_id)->get();
      $mapkontak = collect($dt['fasyankes'])->map(function($item,$i){
                      $item->kontak = detailkontak::where('dinas_luar_id',$item->dinas_luar_id)->where('fasyankes_id',$item->fasyankes_id)->get();
                      $item->hasilkunjungan = ddlh::where('dinas_luar_id',$item->dinas_luar_id)->where('fasyankes_id',$item->fasyankes_id)->first();
                      return $item;
      });
      // dd($mapkontak);
      $dt['pegawai'] = dptl::where('dinas_luar_id',$request->dinas_luar_id)->get();
      $dt['kontakkunjungan'] = $mapkontak;
      $berangkat = $this->dateIdn($data->tanggal_berangkat);
      $pulang = $this->dateIdn($data->tanggal_pulang);

      $dt['tanggal'] = array('berangkat' => $berangkat, 'pulang' => $pulang);

      // dd($dt);

      return view('laporan.perjalanandinas.index',compact('dt'));
      // dd($dt);
      // dd($request->all());
    }

    public function indexfasyankes(){//master fasyankes
      $data = FasyankesDt::orderBy('id','DESC')->get();
      $provinsi = $this->provinsi();
      return view('master.fasyankes.index',compact('data','provinsi'));
    }


    public function postfasyankes(Request $request){
      // dd($request->all());
      $datafasyankes = FasyankesDt::create([
        'nama' => $request->namafasyankes,
        'alamat' => $request->alamat,
        'kota' => $request->kota,
        'provinsi' => $request->provinsi,
        'telepon' => $request->telp,
        'email' => $request->email
        ]);
      if($datafasyankes){
        //sukses
        return redirect()->back()->with('success', "Data sudah di masukkan dengan sukses!");
        // dd("sukses");
      }else{
        dd("gagal");
      }
    }



    public function fasyankesdt_json(){
      $data = FasyankesDt::orderBy('nama','ASC')->get();
      return $data;
    }
    public function forminputdinas(){
      $datas = FasyankesDt::orderBy('nama','ASC')->get();
      $provinsi = $this->provinsi();
      // dd($provinsi);
      return view('formkunjungan.index',compact('datas','provinsi'));
    }

    public function jsonprovinsinm($nama){
      // return $nama;
      $arrProvinsi =  array();
      // $nama = 'Aceh';
      $data = $this->provinsi();
      foreach ($data as $key => $value) {
        // code...
        if($key == $nama){
          $arrProvinsi [] = $value;
        }else{

        }
      }
      return response()->json($arrProvinsi);
      // dd($data);
    }


    /*ambil berkas json*/
    public function ambil_berkas($id){
      $data = dk::where('fasyankes_id',$id)->get();
      if(empty($data)){
        return "kosong";
      }
      return $data;
    }

    /*edit pegawai*/
    public function edit_pegawai($id){
      $data = dptl::where('dinas_luar_id',$id)->get();
      $map = $data->map(function($item,$i){
        $item->namapegawai = $item->pegawai['nama'];
        $item->nik = $item->pegawai['nik'];
        return $item;
      });
      return $map;

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

      /*
      $cek validasi apakah sudah ada atau belom ,jika belum lanjutin
      cek pada tabel tujuan_cek statusnya apakah 1 jika 0
      */
      $cek  = dtdl::where('dinas_luar_id',$request->kodedinasluar)->where('fasyankes_id',$request->faskes_id[0])->first();
      if($cek->status == 1){
        return redirect()->back()->with('gagal', "Data sudah ada!");
        // return redirect()->back()->with('success', "Data sudah di masukkan dengan sukses!");
        dd("data sudah pernah dimasukkan");
      }
      dd("teest");

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
      $data = Pegawai::orderBy('nama','ASC')->get();
      return response()->json($data);
    }
    //get nip selected fasyankes
    public function getnipselected($dinas_luar_id,$nip){
      $data = dptl::where('dinas_luar_id',$dinas_luar_id)->where('nip',$nip)->first();
      $dtarray['data'] = $data;
      $dtarray['pegawai'] = $dtpegawai = $data->pegawai;
      $dtarray['allpegawai'] = Pegawai::orderBy('nama','ASC')->get();
      return response()->json($dtarray);
    }

    public function getidfasyankes($dinas_luar_id,$id){
        $dt = dtdl::where('dinas_luar_id',$dinas_luar_id)->where('fasyankes_id',$id)->first();
        $data['data'] = $dt;
        $data['fasyankes'] = $dt->fasyankes;
        $data['allfasyankes'] = FasyankesDt::orderBy('nama','ASC')->get();
        return response()->json($data);
    }

    public function gettimtujuan($id){
      $data = dtdl::where('dinas_luar_id',$id)->get();
      $map = $data->map(function($item,$i){
              $item->namafasyankes = $item->fasyankes;
              return $item;
      });
      return response()->json($map);
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
      // ProvinsiController
      public function provinsi(){
        $data =  array(
          'Aceh' => array(
                  'Kabupaten Aceh Barat',
                  'Kabupaten Aceh Barat Daya',
                  'Kabupaten Aceh Besar',
                  'Kabupaten Aceh Jaya',
                  'Kabupaten Aceh Selatan',
                  'Kabupaten Aceh Singkil',
                  'Kabupaten Aceh Tamiang',
                  'Kabupaten Aceh Tengah',
                  'Kabupaten Aceh Tenggara',
                  'Kabupaten Aceh Timur',
                  'Kabupaten Aceh Utara',
                  'Kabupaten Bener Meriah',
                  'Kabupaten Bireuen',
                  'Kabupaten Gayo Lues',
                  'Kabupaten Nagan Raya',
                  'Kabupaten Pidie',
                  'Kabupaten Pidie Jaya',
                  'Kabupaten Simeulue',
                  'Kota Banda Aceh',
                  'Kota Langsa',
                  'Kota Lhokseumawe',
                  'Kota Sabang',
                  'Kota Subulussalam',
                  ),
          'Bali' => array(
                  'Kabupaten Badung',
                  'Kabupaten Bangil',
                  'Kabupaten Buleleng',
                  'Kabupaten Gianyar',
                  'Kabupaten Jembrana',
                  'Kabupaten Karangasem',
                  'Kabupaten Klungkung',
                  'Kabupaten Tabanan',
                  'Kota Denpasar',
                  ),
          'Banten' => array(
                  'Kabupaten Lebak',
                  'Kabupaten Pandeglang',
                  'Kabupaten Serang',
                  'Kabupaten Tangerang',
                  'Kota Cilegon',
                  'Kota Serang',
                  'Kota Tangerang',
                  'Kota Tangerang selatan',
                  ),
          'Bengkulu' => array(
                  'Kabupaten Bengkulu Selatan',
                  'Kabupaten Bemgkulu Tengah',
                  'Kabupaten Bengkulu Utara',
                  'Kabupaten Kaur',
                  'Kabupaten kapahiang',
                  'Kabupaten Lebong',
                  'Kabupaten Mukomuko',
                  'Kabupaten Rejang Lebong',
                  'Kabupaten seluma',
                  'Kota Bengkulu',
                  ),
          'D.I Yogyakarta' => array(
                  'Kabupaten Bantul',
                  'Kabupaten Gunung kildul',
                  'Kabupaten Kulon Progo',
                  'Kabupaten Sleman',
                  'Kota Yogyakarta',
                  ),
          'D.K.I Jakarta' => array(
                  'Kabupaten Kepulauan Seribu',
                  'Kota Jakarta Barat',
                  'Kota Jakarta Pusat',
                  'Kota Jakarta Selatan',
                  'Kota Jakarta Timur',
                  'Kota Jakarta Utara',
                  ),
          'Gorontalo' => array(
                  'Kabupaten Boalemo',
                  'Kabupaten Bone Bolango',
                  'Kabupaten Gorontalo',
                  'Kabupaten gorontalo Utara',
                  'Kabupaten Pahuwato',
                  'Kota Gorontalo',
                  ),
          'Jambi' => array(
                  'Kabupaten Batanghari',
                  'Kabupaten Bungo',
                  'Kabupaten Kerinci',
                  'Kabupaten Merangin',
                  'Kabupaten Muaro Jambi',
                  'Kabupaten Sarolangun',
                  'Kabupaten Tanjung Jabung Barat',
                  'Kabupaten Tanjung Jabung Timur',
                  'Kabupaten Tebo',
                  'Kota Jambi',
                  'Kota Sungai Penuh',
                  ),
          'Jawa Barat' => array(
                  'Kabupaten Bandung',
                  'Kabupaten Bandung Barat',
                  'Kabupaten Bekasi',
                  'Kabupaten Bogor',
                  'Kabupaten Ciamis',
                  'Kabupaten Cianjur',
                  'Kabupaten Cirebon',
                  'Kabupaten Garut',
                  'Kabupaten Indramayu',
                  'Kabupaten Karawang',
                  'Kabupaten Kuningan',
                  'Kabupaten Majalengka',
                  'Kabupaten Pangandaran',
                  'Kabupaten Purwakarta',
                  'Kabupaten Subang',
                  'Kabupaten Sukabumi',
                  'Kabupaten Sumedang',
                  'Kabupaten Tasikmalaya',
                  'Kota Bandung',
                  'Kota Banjar',
                  'Kota Bekasi',
                  'Kota Bogor',
                  'Kota Cimahi',
                  'Kota Cirebon',
                  'Kota Depok',
                  'Kota Sukabumi',
                  'Kota Tasikmalaya',
                  ),
          'Jawa Tengah' => array(
                  'Kabupaten Banjarnegara',
                  'Kabupaten Banyumas',
                  'Kabupaten Batang',
                  'Kabupaten Blora',
                  'Kabupaten Boyolali',
                  'Kabupaten Brebes',
                  'Kabupaten Cilacap',
                  'Kabupaten Demak',
                  'Kabupaten Grobogan',
                  'Kabupaten Jepara',
                  'Kabupaten Karanganyar',
                  'Kabupaten Kebumen',
                  'Kabupaten Kendal',
                  'Kabupaten Klaten',
                  'Kabupaten Kudus',
                  'Kabupaten Magelang',
                  'Kabupaten Pati',
                  'Kabupaten Pekalongan',
                  'Kabupaten Pemalang',
                  'Kabupaten Purbalingga',
                  'Kabupaten Purworejo',
                  'Kabupaten Rembang',
                  'Kabupaten Semarang',
                  'Kabupaten Sragen',
                  'Kabupaten Sukoharjo',
                  'Kabupaten Tegal',
                  'Kabupaten Temanggung',
                  'Kabupaten Wonogiri',
                  'Kabupaten Wonosobo',
                  'Kota Magelang',
                  'Kota Pekalongan',
                  'Kota Salatiga',
                  'Kota Semarang',
                  'Kota Surakarta',
                  'Kota Tegal',
                  ),
          'Jawa Timur' => array(
                  'Kabupaten Bangkalan',
                  'Kabupaten Banyuwangi',
                  'Kabupaten Blitar',
                  'Kabupaten Bojonegoro',
                  'Kabupaten Bondowoso',
                  'Kabupaten Gresik',
                  'Kabupaten Jember',
                  'Kabupaten Jombang',
                  'Kabupaten Kediri',
                  'Kabupaten Lamongan',
                  'Kabupaten Lumajang',
                  'Kabupaten Madiun',
                  'Kabupaten Magetan',
                  'Kabupaten Malang',
                  'Kabupaten Mojokerto',
                  'Kabupaten Nganjuk',
                  'Kabupaten Ngawi',
                  'Kabupaten Pacitan',
                  'Kabupaten Pamekasan',
                  'Kabupaten Pasuruan',
                  'Kabupaten Ponorogo',
                  'Kabupaten Probolinggo',
                  'Kabupaten Sampang',
                  'Kabupaten Sidoarjo',
                  'Kabupaten Situbondo',
                  'Kabupaten Sumenep',
                  'Kabupaten Trenggalek',
                  'Kabupaten Tuban',
                  'Kabupaten Tulungagung',
                  'Kota Batu',
                  'Kota Blitar',
                  'Kota Kediri',
                  'Kota Madiun',
                  'Kota Malang',
                  'Kota Mojokerto',
                  'Kota Pasuruan',
                  'Kota Probolinggo',
                  'Kota Surabaya',
                  ),
          'Kalimantan Barat' => array(
                  'Kabupaten Bengkayang',
                  'Kabupaten Kapuas Hulu',
                  'Kabupaten Kayong Utara',
                  'Kabupaten Ketapang',
                  'Kabupaten Kubu Raya',
                  'Kabupaten Landak',
                  'Kabupaten Melawi',
                  'Kabupaten Pontianak',
                  'Kabupaten Sambas',
                  'Kabupaten Sanggau',
                  'Kabupaten Sekadau',
                  'Kabupaten Sintang',
                  'Kota Pontianak',
                  'Kota Singkawang',
                  ),
          'Kalimantan Selatan' => array(
                  'Kabupaten Balangan',
                  'Kabupaten Banjar',
                  'Kabupaten Barito Kuala',
                  'Kabupaten Hulu Sungai Selatan',
                  'Kabupaten Hulu Sungai Tengah',
                  'Kabupaten Hulu Sungai Utara',
                  'Kabupaten Kotabaru',
                  'Kabupaten Tabalong',
                  'Kabupaten Tanah Bumbu',
                  'Kabupaten Tanah Laut',
                  'Kabupaten Tapin',
                  'Kota Banjarbaru',
                  'Kota Banjarmasin',
                  ),
          'Kalimantan Tengah' => array(
                  'Kabupaten Barito Selatan',
                  'Kabupaten Barito Timur',
                  'Kabupaten Barito Utara',
                  'Kabupaten Gunung Mas',
                  'Kabupaten Kapuas',
                  'Kabupaten Katingan',
                  'Kabupaten Kotawaringin Barat',
                  'Kabupaten Kotawaringin Timur',
                  'Kabupaten Lamandau',
                  'Kabupaten Murung Raya',
                  'Kabupaten Pulang Pisau',
                  'Kabupaten Sukamara',
                  'Kabupaten Seruyan',
                  'Kota Palangka Raya',
                  ),
          'Kalimantan Timur' => array(
                  'Kabupaten Berau',
                  'Kabupaten Kutai Barat',
                  'Kabupaten Kutai Kartanegara',
                  'Kabupaten Kutai Timur',
                  'Kabupaten Paser',
                  'Kabupaten Penajam Paser Utara',
                  'Kabupaten Mahakam Ulu',
                  'Kota Balikpapan',
                  'Kota Bontang',
                  'Kota Samarinda',
                  ),
          'Kalimantan Utara' => array(
                  'Kabupaten Bulungan',
                  'Kabupaten Malinau',
                  'Kabupaten Nunukan',
                  'Kabupaten Tana Tidung',
                  'Kota Tarakan',
                  ),
          'Kepulauan Bangka Belitung' => array(
                  'Kabupaten Bangka',
                  'Kabupaten Bangka Barat',
                  'Kabupaten Bangka Selatan',
                  'Kabupaten Bangka Tengah',
                  'Kabupaten Belitung',
                  'Kabupaten Belitung Timur',
                  'Kota Pangkal Pinang',
                  ),
          'Kepulauan Riau' => array(
                  'Kabupaten Bintan',
                  'Kabupaten Karimun',
                  'Kabupaten Kepulauan Anambas',
                  'Kabupaten Lingga',
                  'Kabupaten Natuna',
                  'Kota Batam',
                  'Kota Tanjung Pinang',
                  ),
          'Lampung' => array(
                  'Kabupaten Lampung Barat',
                  'Kabupaten Lampung Selatan',
                  'Kabupaten Lampung Tengah',
                  'Kabupaten Lampung Timur',
                  'Kabupaten Lampung Utara',
                  'Kabupaten Mesuji',
                  'Kabupaten Pesawaran',
                  'Kabupaten Pringsewu',
                  'Kabupaten Tanggamus',
                  'Kabupaten Tulang Bawang',
                  'Kabupaten Tulang Bawang Barat',
                  'Kabupaten Way Kanan',
                  'Kabupaten Pesisir Barat',
                  'Kota Bandar Lampung',
                  'Kota Kotabumi',
                  'Kota Liwa',
                  'Kota Metro',
                  ),
          'Maluku' => array(
                  'Kabupaten Buru',
                  'Kabupaten Buru Selatan',
                  'Kabupaten Kepulauan Aru',
                  'Kabupaten Maluku Barat Daya',
                  'Kabupaten Maluku Tengah',
                  'Kabupaten Maluku Tenggara',
                  'Kabupaten Maluku Tenggara Barat',
                  'Kabupaten Seram Bagian Barat',
                  'Kabupaten Seram Bagian Timur',
                  'Kota Ambon',
                  'Kota Tual',
                  ),
          'Maluku Utara' => array(
                  'Kabupaten Halmahera Barat',
                  'Kabupaten Halmahera Tengah',
                  'Kabupaten Halmahera Utara',
                  'Kabupaten Halmahera Selatan',
                  'Kabupaten Halmahera Timur',
                  'Kabupaten Kepulauan Sula',
                  'Kabupaten Pulau Morotai',
                  'Kabupaten Pulau Taliabu',
                  'Kota Ternate',
                  'Kota Tidore Kepulauan',
                  ),
          'Nusa Tenggara Barat' => array(
                  'Kabupaten Bima',
                  'Kabupaten Dompu',
                  'Kabupaten Lombok Barat',
                  'Kabupaten Lombok Tengah',
                  'Kabupaten Lombok Timur',
                  'Kabupaten Lombok Utara',
                  'Kabupaten Sumbawa',
                  'Kabupaten Sumbawa Barat',
                  'Kota Bima',
                  'Kota Mataram',
                  ),
          'Nusa Tenggara Timur' => array(
                  'Kabupaten Alor',
                  'Kabupaten Belu',
                  'Kabupaten Ende',
                  'Kabupaten Flores Timur',
                  'Kabupaten Kupang',
                  'Kabupaten Lembata',
                  'Kabupaten Manggarai',
                  'Kabupaten Manggarai Barat',
                  'Kabupaten Manggarai Timur',
                  'Kabupaten Ngada',
                  'Kabupaten Nagekeo',
                  'Kabupaten Rote Ndao',
                  'Kabupaten Sabu Raijua',
                  'Kabupaten Sikka',
                  'Kabupaten Sumba Barat',
                  'Kabupaten Sumba Barat Daya',
                  'Kabupaten Sumba Tengah',
                  'Kabupaten Sumba Timur',
                  'Kabupaten Timor Tengah Selatan',
                  'Kabupaten Timor Tengah Utara',
                  'Kota Kupang',
                  ),
          'Papua' => array(
                  'Kabupaten Asmat',
                  'Kabupaten Biak Numfor',
                  'Kabupaten Boven Digoel',
                  'Kabupaten Deiyai',
                  'Kabupaten Dogiyai',
                  'Kabupaten Intan Jaya',
                  'Kabupaten Jayapura',
                  'Kabupaten Jayawijaya',
                  'Kabupaten Keerom',
                  'Kabupaten Kepulauan Yapen',
                  'Kabupaten Lanny Jaya',
                  'Kabupaten Mamberamo Raya',
                  'Kabupaten Mamberamo Tengah',
                  'Kabupaten Mappi',
                  'Kabupaten Merauke',
                  'Kabupaten Mimika',
                  'Kabupaten Nabire',
                  'Kabupaten Nduga',
                  'Kabupaten Paniai',
                  'Kabupaten Pegunungan Bintang',
                  'Kabupaten Puncak',
                  'Kabupaten Puncak Jaya',
                  'Kabupaten Sarmi',
                  'Kabupaten Supiori',
                  'Kabupaten Tolikara',
                  'Kabupaten Waropen',
                  'Kabupaten Yahukimo',
                  'Kabupaten Yalimo',
                  'Kota Jayapura',
                  ),
          'Papua Barat' => array(
                  'Kabupaten Fakfak',
                  'Kabupaten Kaimana',
                  'Kabupaten Manokwari',
                  'Kabupaten Manokwari Selatan',
                  'Kabupaten Maybrat',
                  'Kabupaten Pegunungan Arfak',
                  'Kabupaten Raja Ampat',
                  'Kabupaten Sorong',
                  'Kabupaten Sorong Selatan',
                  'Kabupaten Tambrauw',
                  'Kabupaten Teluk Bintuni',
                  'Kabupaten Teluk Wondama',
                  'Kota Sorong',
                  ),
          'Riau' => array(
                  'Kabupaten Bengkalis',
                  'Kabupaten Indragiri Hilir',
                  'Kabupaten Indragiri Hulu',
                  'Kabupaten Kampar',
                  'Kabupaten Kuantan Singingi',
                  'Kabupaten Pelalawan',
                  'Kabupaten Rokan Hilir',
                  'Kabupaten Rokan Hulu',
                  'Kabupaten Siak',
                  'Kabupaten Kepulauan Meranti',
                  'Kota Dumai',
                  'Kota Pekanbaru',
                  ),
          'Sulawesi Barat' => array(
                  'Kabupaten Majene',
                  'Kabupaten Mamasa',
                  'Kabupaten Mamuju',
                  'Kabupaten Mamuju Utara',
                  'Kabupaten Polewali Mandar',
                  'Kabupaten Mamuju Tengah',
                  ),
          'Sulawesi Selatan' => array(
                  'Kabupaten Bantaeng',
                  'Kabupaten Barru',
                  'Kabupaten Bone	Watampone',
                  'Kabupaten Bulukumba',
                  'Kabupaten Enrekang',
                  'Kabupaten Gowa',
                  'Kabupaten Jeneponto',
                  'Kabupaten Kepulauan Selayar',
                  'Kabupaten Luwu',
                  'Kabupaten Luwu Timur',
                  'Kabupaten Luwu Utara',
                  'Kabupaten Maros',
                  'Kabupaten Pangkajene dan Kepulauan',
                  'Kabupaten Pinrang',
                  'Kabupaten Sidenreng Rappang',
                  'Kabupaten Sinjai',
                  'Kabupaten Soppeng',
                  'Kabupaten Takalar',
                  'Kabupaten Tana Toraja',
                  'Kabupaten Toraja Utara',
                  'Kabupaten Wajo',
                  'Kota Makassar',
                  'Kota Palopo',
                  'Kota Parepare',
                  ),
          'Sulawesi Tengah' => array(
                  'Kabupaten Banggai',
                  'Kabupaten Banggai Kepulauan',
                  'Kabupaten Banggai Laut',
                  'Kabupaten Buol',
                  'Kabupaten Donggala',
                  'Kabupaten Morowali',
                  'Kabupaten Parigi Moutong',
                  'Kabupaten Poso',
                  'Kabupaten Sigi',
                  'Kabupaten Tojo Una-Una',
                  'Kabupaten Tolitoli',
                  'Kota Palu',
                  ),
          'Sulawesi Tenggara' => array(
                  'Kabupaten Bombana',
                  'Kabupaten Buton',
                  'Kabupaten Buton Utara',
                  'Kabupaten Kolaka',
                  'Kabupaten Kolaka Timur',
                  'Kabupaten Kolaka Utara',
                  'Kabupaten Konawe',
                  'Kabupaten Konawe Selatan',
                  'Kabupaten Konawe Utara',
                  'Kabupaten Konawe Kepulauan',
                  'Kabupaten Muna',
                  'Kabupaten Wakatobi',
                  'Kota Bau-Bau',
                  'Kota Kendari',
                  ),
          'Sulawesi Utara' => array(
                  'Kabupaten Bolaang Mongondow',
                  'Kabupaten Bolaang Mongondow Selatan',
                  'Kabupaten Bolaang Mongondow Timur',
                  'Kabupaten Bolaang Mongondow Utara',
                  'Kabupaten Kepulauan Sangihe',
                  'Kabupaten Kepulauan Siau Tagulandang Biaro',
                  'Kabupaten Kepulauan Talaud',
                  'Kabupaten Minahasa',
                  'Kabupaten Minahasa Selatan',
                  'Kabupaten Minahasa Tenggara',
                  'Kabupaten Minahasa Utara',
                  'Kota Bitung',
                  'Kota Kotamobagu',
                  'Kota Manado',
                  'Kota Tomohon',
                  ),
          'Sumatera Barat' => array(
                  'Kabupaten Agam',
                  'Kabupaten Dharmasraya',
                  'Kabupaten Kepulauan Mentawai',
                  'Kabupaten Lima Puluh Kota',
                  'Kabupaten Padang Pariaman',
                  'Kabupaten Pasaman',
                  'Kabupaten Pasaman Barat',
                  'Kabupaten Pesisir Selatan',
                  'Kabupaten Sijunjung',
                  'Kabupaten Solok',
                  'Kabupaten Solok Selatan',
                  'Kabupaten Tanah Datar',
                  'Kota Bukittinggi',
                  'Kota Padang',
                  'Kota Padangpanjang',
                  'Kota Pariaman',
                  'Kota Payakumbuh',
                  'Kota Sawahlunto',
                  'Kota Solok',
                  ),
          'Sumatera Selatan' => array(
                  'Kabupaten Banyuasin',
                  'Kabupaten Empat Lawang',
                  'Kabupaten Lahat',
                  'Kabupaten Muara Enim',
                  'Kabupaten Musi Banyuasin',
                  'Kabupaten Musi Rawas',
                  'Kabupaten Ogan Ilir',
                  'Kabupaten Ogan Komering Ilir',
                  'Kabupaten Ogan Komering Ulu',
                  'Kabupaten Ogan Komering Ulu Selatan',
                  'Kabupaten Penukal Abab Lematang Ilir',
                  'Kabupaten Ogan Komering Ulu Timur',
                  'Kota Lubuklinggau',
                  'Kota Pagar Alam',
                  'Kota Palembang',
                  'Kota Prabumulih',
                  ),
          'Sumatera Utara' => array(
                  'Kabupaten Asahan',
                  'Kabupaten Batubara',
                  'Kabupaten Dairi',
                  'Kabupaten Deli Serdang',
                  'Kabupaten Humbang Hasundutan',
                  'Kabupaten Karo	Kabanjahe',
                  'Kabupaten Labuhanbatu',
                  'Kabupaten Labuhanbatu Selatan',
                  'Kabupaten Labuhanbatu Utara',
                  'Kabupaten Langkat',
                  'Kabupaten Mandailing Natal',
                  'Kabupaten Nias',
                  'Kabupaten Nias Barat',
                  'Kabupaten Nias Selatan',
                  'Kabupaten Nias Utara',
                  'Kabupaten Padang Lawas',
                  'Kabupaten Padang Lawas Utara',
                  'Kabupaten Pakpak Bharat',
                  'Kabupaten Samosir',
                  'Kabupaten Serdang Bedagai',
                  'Kabupaten Simalungun',
                  'Kabupaten Tapanuli Selatan',
                  'Kabupaten Tapanuli Tengah',
                  'Kabupaten Tapanuli Utara',
                  'Kabupaten Toba Samosir',
                  'Kota Binjai',
                  'Kota Gunungsitoli',
                  'Kota Medan',
                  'Kota Padangsidempuan',
                  'Kota Pematangsiantar',
                  'Kota Sibolga',
                  'Kota Tanjungbalai',
                  'Kota Tebing Tinggi',
                  ),
          );
          return $data;
      }
      //end provinsi

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
