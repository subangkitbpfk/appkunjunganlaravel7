<html>
    <head>
        <title>
            Laporan per kunjungan
        </title>
    </head>
    <style>
        table td{
            padding: 5px;
        }

    </style>
    <body>
        <h3 style="text-align: center">LAPORAN PERJALANAN DINAS</h3>
        {{-- table header --}}
        
        <table style="margin-left: auto;margin-right: auto;">
            <tbody>
            <tr>
            <td>1. Nama yang memberangkatkan </td>
            <td>:</td>
            <td>R. WISNU DWI HARDYANTO, ST</td>
            </tr>
            <tr>
            <td style="width: 259px; vertical-align:top">2. Tempat yang dikunjungi </td>
            <td style="width: 10px; vertical-align:top">:</td>
            <td>
                {{-- {{dd($map[0]['get_header_detail_rs'])}} --}}
                @foreach( $map[0]['get_header_detail_rs'] as $gb)
                {{-- {{dd($gb)}} --}}
                @if(@gb != '')
                  <?
                  $pegawai = \App\FasyankesDt::where('id',intval($gb['fasyankes_dt_id']))->get();
                  foreach ($pegawai as $key => $value) {
                    echo $value['nama'].","."<br>";
                  }
                  ?>
                @else
                @endif
              @endforeach  
                
            </td>
            </tr>
            <tr>
            <td>3. Waktu Perjalanan Dinas </td>
            <td style="width: 10px; vertical-align:top">:</td>
            <?php $tgl = explode(" ",$mulai);?>
            <td>{{$tgl[0]}} {{$tgl[1]}} sampai {{$sampai}}</td>
            </tr>
            <tr>
            <td style="width: 500px; vertical-align:top">4. Nama yang melakukan perjalanan dinas :</td>
            <td style="width: 10px; vertical-align:top">:</td>
            <td style="">
                @foreach($pegawai_id as $gb)
                @if(@gb != '')
                  <?
                  $pegawai = \App\Pegawai::where('id',intval($gb))->get();
                  foreach ($pegawai as $key => $value) {
                    echo $value['nama'].","."<br>";
                  }
                  ?>
                @else
                @endif
              @endforeach  
            </td>
            </tr>
            <tr>
            <td>5. Tujuan perjalanan dinas </td>
            <td>: </td>
            <td> Penyelesaian Administrasi Keuangan</td>
            </tr>
            <tr>
            <td>6. Hasil Kunjungan / Pertemuan</td>
            <td>:</td>
            <td>&nbsp;</td>
            </tr>

            {{-- looping disini --}}
            {{-- tr awal --}}
            <?php
            $abjad = ['A','B','C','D','E','F','G','H','I','J'];
            $i=0;
            ?>
            
            @foreach ( $des as $ds )
                @foreach ($ds['fasyankes'] as $a )
                
            <tr style="">
              <td style="border-top:1px solid black;border-bottom:1px solid black;width: 500px; vertical-align:top;font-weight: bold"><?php echo $abjad[$i];?>. Nama Rumah Sakit :  {{$a['nama']}} </td>
              <?php $i++?>
              <td style="border-top:1px solid black;border-bottom:1px solid black"></td>
              <td style="border-top:1px solid black;border-bottom:1px solid black"></td>
          </tr>
          <tr>
            <td>Petugas ditemui </td>
            <td>:</td>
          <td>{{$ds['petugas']}}</td>
          </tr>
          <tr>
            <td>Nomor kontak </td>
            <td>:</td>
          <td>{{$ds['hp1']}} - ({{$ds['email']}})</td>
          </tr>
          <tr>
            <td>Detil penyelesaian transaksi terkait piutang </td>
            <td>:</td>
          <td>{{$ds['detail_penyelesaian']}}</td>
          </tr>
          <tr>
            <td>Informasi pemakaian aplikasi Simponi </td>
          <td>:</td>
            <td>{{$ds['informasi_simponi']}}</td>
          </tr>
          <tr>
            <td>Keterangan tambahan :</td>
            <td>:</td>
            <td>-</td>
          </tr>
          {{-- end tr detail --}}
            {{-- end looping --}}
              @endforeach
            @endforeach

            <tr>
              <td colspan="3"style="text-align: center;padding:30px">Catatan : Demikian seterusnya sampai semua RS yg dikunjungi dilaporkan</td>  
            </tr>

            <tr>
              <td>Petugas Dinas Luar 	:</td>
              <td>:</td>
              <td></td>
            </tr>

            {{-- looping pegawai --}}
            <tr>
              <td></td>
              <td></td>
              <td>1. Subangkit Achmat Husen</td>
            </tr>
            {{-- end pegawai --}}
           

            </tbody>
            </table>
            <table style="margin-left: auto;margin-right: auto;">
            
              
              

            </table>
            <!-- DivTable.com -->
        {{-- end tabel header --}}

    </body>
</html>

{{-- {{dd($data)}} --}}