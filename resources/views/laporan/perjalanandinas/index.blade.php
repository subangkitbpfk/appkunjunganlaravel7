<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <center><h3>LAPORAN PERJALANAN DINAS</h3></center>

    <table style="border:1px solid black;margin:auto;width:60%">
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

    <table style="border:1px solid black;margin:auto;width:60%">
      @foreach($dt['fasyankes'] as $dtdetail)
      <tr>
        <td>A. Nama Fasyankes : {{$dtdetail->fasyankes->nama}}</td>
      </tr>

      <tr>
        <td>- Petugas yang ditemui :</td>
      </tr>
      <tr>

        <td>
          @foreach($dt['kontakkunjungan'] as $kontaks)
            @if(isset($kontaks->kontak))
              @foreach($kontaks->kontak as $dtkontak)
                @if($dtdetail->fasyankes_id == $dtkontak->fasyankes_id)
                    {{$dtkontak->nama_kontak}}
                @endif
              @endforeach
            @endif
          @endforeach

        </td>

      </tr>

      <tr>
        <td>- Hasil Kunjungan : </td>
      </tr>
      <tr>
        <td>{Hasil Kunjungan}</td>
      </tr>
      @endforeach
    </table>



  </body>
</html>
