<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <center><h3>LAPORAN PERJALANAN DINAS</h3></center>

    <table style="border:0px solid black;margin:auto;width:60%">
      <tr>
        <td>1.</td>
        <td>Nama yang memberangkatkan</td>
        <td>:</td>
        <td>Ka. Sub.Bag. Tatausaha BPFK Sby</td>
      </tr>

      <tr>
        <td>2.</td>
        <td>Tempat yang dikunjungi</td>
        <td>:</td>
        <td>
          @foreach($dt['fasyankes'] as $data)
            - {{$data->fasyankes->nama}}<br>
          @endforeach
        </td>
      </tr>

      <tr>
        <td>3.</td>
        <td>Waktu Perjalanan Dinas</td>
        <td>:</td>
        <td>{{$dt['data']->tanggal_berangkat}} s/d {{$dt['data']->tanggal_pulang}}</td>
      </tr>

      <tr>
        <td>4.</td>
        <td>Nama yang melakukan perjalanan dinas</td>
        <td>:</td>
        <td>
          @foreach($dt['pegawai'] as $data)
            - {{$data->pegawai->nama}}<br>
          @endforeach
        </td>
      </tr>

      <tr>
        <td>5.</td>
        <td>Tujuan perjalanan dinas</td>
        <td>:</td>
        <td>Penyelesaian administrasi keuangan dan sosialisasi aplikasi simponi</td>
      </tr>
      <tr>
        <td>6.</td>
        <td>Hasil Kunjungan / Pertemuan</td>
        <td>:</td>
        <td></td>
      </tr>
    </table>

    <table style="border:0px solid black;margin:auto;width:60%">
      @foreach($dt['fasyankes'] as $dtdetail)
      <tr>
        <td><b>A. Nama Fasyankes : </b>{{$dtdetail->fasyankes->nama}}</td>
      </tr>

      <tr>
        <td>- <b>Petugas yang ditemui :</b></td>
      </tr>
      <tr>

        <td><table >
          <!-- <tr>
            <td style="border-right:1px solid gray;padding:0px 3px 0px 3px">Nama</td>
            <td style="border-right:1px solid gray;padding:0px 3px 0px 3px;text-align:center">Jabatan / Devisi</td>
            <td style="border-right:1px solid gray;padding:0px 3px 0px 3px;text-align:center">Kontak satu</td>
            <td style="border-right:1px solid gray;padding:0px 3px 0px 3px;text-align:center">Kontak dua</td>
          <tr> -->
          @foreach($dt['kontakkunjungan'] as $kontaks)
            @if(isset($kontaks->kontak))
              @foreach($kontaks->kontak as $dtkontak)
                @if($dtdetail->fasyankes_id == $dtkontak->fasyankes_id)

                      <tr>
                        <td style="border-right:1px solid gray;padding:0px 3px 0px 3px">{{$dtkontak->nama_kontak}}</td>
                        <td style="border-right:1px solid gray;padding:0px 3px 0px 3px;text-align:center">{{$dtkontak->jabatan_kontak}}</td>
                        <td style="border-right:1px solid gray;padding:0px 3px 0px 3px;text-align:center">{{$dtkontak->kontak_satu}}</td>
                        <td style="border-right:1px solid gray;padding:0px 3px 0px 3px;text-align:center">{{$dtkontak->kontak_dua}}</td>
                      <tr>

                      <!-- -  -  - <br> -->
                @endif
              @endforeach
            @endif
          @endforeach
            </table>

        </td>

      </tr>

      <tr>
        <td><b>Hasil Kunjungan : </b></td>
      </tr>
      <tr>
        <td>
          @foreach($dt['kontakkunjungan'] as $kontaks)
            @if(isset($kontaks->hasilkunjungan))
              @if($dtdetail->fasyankes_id == $dtkontak->fasyankes_id)
              {{$kontaks->hasilkunjungan->hasil_dinas}}
              @endif

            @endif
          @endforeach
        </td>
      </tr>
      @endforeach
    </table>

    <table style="border:0px solid black;margin:auto;width:60%">
      <tr>
        <td><b>Petugas Dinas Luar :</b></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <?php $i=1?>
      @foreach($dt['pegawai'] as $pg)
      <tr>
        <td>{{$i++}}. {{$pg->pegawai->nama}}</td>
        <td>.....................</td>
        <td></td>
        <td></td>
      </tr>
      @endforeach


    <table>



  </body>
</html>
