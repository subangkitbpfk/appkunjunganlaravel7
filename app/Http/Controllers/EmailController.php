<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Notification;
use PDF;
use App\Notifications\TaskComplete;
// require 'vendor/autoload.php';
use Mailgun\Mailgun;
use Mail;
use QrCode;
use ZkHelp as zk;
use GuzzleHttp\Client;
use DB;
use GuzzleHttp\Exception\RequestException;
use App\MesinFinger;


use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class EmailController extends Controller
{
    //
    public function index(){

    }
    public function send(Request $request){
    	// require 'vendor/autoload.php';

    	/* mailgun */
    	$user = User::first();
        Mail::send('testemail', ['user' => $user], function ($m) use ($user) {
            $m->from('subangkit@ymail.com', 'Your Application');
            $m->to($user->email, 'subangkit')->subject('Your Reminder!');
        });
        /* end mailgun */


        // $details = [

        //     'greeting' => 'Hi Artisan',

        //     'body' => 'This is my first notification from ItSolutionStuff.com',

        //     'thanks' => 'Thank you for using ItSolutionStuff.com tuto!',

        //     'actionText' => 'View My Site',

        //     'actionURL' => url('/'),

        //     'order_id' => 101

        // ];
        // Notification::send($user, new TaskComplete($details));
        // dd('done');
    }

    public function map(){
    	$response = \GoogleMaps::load('geocoding')
		->setParam (['address' =>'santa cruz'])
 		->get();
 		dd($response);
    }

    public function barcode(){
    	$qrcode = \QrCode::size(500)
            ->format('png')
            ->generate('ItSolutionStuff.com');
        $a='a';
  		return view('testbarcode',compact('qrcode','a'));
    }

    public function generatePDF(){
    	$data = ['title' => 'Welcome to belajarphp.net'];

        $pdf = PDF::loadView('testpdf', $data);
        return $pdf->stream();
        // return $pdf->download('laporan-pdf.pdf');

    }
    public function testzk(){ //proses ambil data dari mesin
    $IP="192.168.1.201";
    // $IP="192.168.1.201"; ip default absen
    $Key="0";
    $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
    // $Connect = fsockopen($IP, "4370", $errno, $errstr, 10);
    // $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    // $connection =  @socket_connect($socket,'192.168.100.4', 4370);
    // dd($Connect);
    // dd($connection);
    // parse
    if($Connect){
        $soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
        $newLine="\r\n";
        fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
        fputs($Connect, "Content-Type: text/xml".$newLine);
        fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
        fputs($Connect, $soap_request.$newLine);
        $buffer="";
        while($Response=fgets($Connect, 1024)){
            $buffer=$buffer.$Response;
        }
    }else echo "Koneksi Gagal";
    $buffer=$this->Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
    $buffer=explode("\r\n",$buffer);
    $dataabsen = array();
    for($a=1;$a<count($buffer);$a++){
        $data=$this->Parse_Data($buffer[$a],"<Row>","</Row>");
        $PIN=$this->Parse_Data($data,"<PIN>","</PIN>");
        $DateTime=$this->Parse_Data($data,"<DateTime>","</DateTime>");
        $Verified=$this->Parse_Data($data,"<Verified>","</Verified>");
        $Status=$this->Parse_Data($data,"<Status>","</Status>");

        $dataabsen[] = [
            'pin' => $PIN,
            'waktu' => $DateTime,
            'verifikasi' => $Verified,
            'status' => $Status
        ];
     }
     return json_encode($dataabsen);
    }

    public function Parse_Data($data,$p1,$p2){
        $data=" ".$data;
        $hasil="";
        $awal=strpos($data,$p1);
        if($awal!=""){
            $akhir=strpos(strstr($data,$p1),$p2);
            if($akhir!=""){
                $hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
                }
            }
        return $hasil;
    }

    public function filters($collect, $date){//2020-05-07 08:41:21
        $return = $collect->map(function($v, $i) use ($date){
            if(substr($v->timestamp,0,10) == $date){
                return $v;
            }
        })->filter();
        return $return;
    }
    public function save_mesin(){
    /*
    $yearnow = \Carbon\carbon::now()->year;
    $bulanno = \Carbon\carbon::now()->month;
    $bulanini = $yearnow."-".$bulanno;
    opsi combobox untuk bulan
    $bulanini = '2020-07';
    */
    $data = collect(json_decode($this->testzk()));
    $dtmesin = MesinFinger::select('tanggal')->where('tanggal','like','%2020%')->get(); //ambil data ditabel untuk dicek
    $data = $data->map(function($v, $i) use ($dtmesin){ //pengecekkan data pada json dengan tabel
            if(empty($dtmesin->where('tanggal',$v->waktu)->toArray())){
                return $v;
            }
        })->filter();

    // data yang baru simpan ke curl domain  juga

    // data beda
    $insert = $this->SimpanData($data);

    if($insert){
            return " Data Sudah di Sinkronisasikan ";
        }
    }
    public function SimpanData($data = array()){ //simpan data ke tabel mesin finger
        foreach ($data as $dt) {
            if($dt->pin!= ""){
                $insert = MesinFinger::create([
                'pin' => $dt->pin,
                'tanggal' => \Carbon\Carbon::parse($dt->waktu)->format("Y-m-d H:i:s"),
                'verifikasi' => $dt->verifikasi,
                'status'=>$dt->status
                ]);
            }
        }
        return "datadisimpan";
    }

    public function GenerateReport(Request $request){//perbulan
        // jika tidak diisi maka akan generete bulan ini

        // tangkap inputan
    }

    public function clearLogMesin(){

    }







}
