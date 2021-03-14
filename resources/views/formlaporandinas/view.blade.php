@extends('layouts.master')
@section('content')
<!-- modal kontak  -->
<div class="row">
  <div class="col-md-12">
    <div id="myModalkontak" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h5 class="modal-title">View Kontak Fasyankes</h5>
      </div>
      <div class="modal-body">
        <p id="namfasyankeskontak"></p>
        <!-- <div class="col-md-12"> -->
          <table class="table table-striped" style="width:100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Kontak 1</th>
                <th>Kontak 2</th>
              </tr>
            </thead>
            <tbody id="kontakkunjungan">

            </tbody>
            </table>
        <!-- </div> -->
      </div>
      <span class="badge" id="keterangan" style="float:left;padding-left:20px"></span>
      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

  </div>
</div>
<!-- end modal kontak -->




<!-- open modal -->
<div class="row">
  <div class="col-md-12">
    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h5 class="modal-title">View Berkas </h5>
      </div>
      <div class="modal-body">
        <span id="inframe"></span>
      </div>
      <span class="badge" id="keterangan" style="float:left;padding-left:20px"></span>
      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

  </div>
</div>
<!-- end modal -->
<div class="row" style="background-color: white;">
    <div class="col-md-12" style="">
       <label for="" style="font-size:1.3em;padding:2px;">Tabel (Laporan Dinas)</label>yang sudah di input
    </div>
</div>
<div class="row" style="background-color: white">
    <div class="col-md-12" style="margin-left:0%;">
       <table class="table table-striped table-bordered" id="myTable">
           <thead>
                <th>No</th>
                <th>Kode Dinas</th>
                <th>Nama Fasyankes</th>
                <th>Lihat Berkas</th>
                <th>Lihat Kontak</th>
                <th></th>

           </thead>
           <tbody>
             <?php $i=1;?>
             @foreach($data as $dt)
             <tr>
               <td><?php echo $i++?></td>
               <td>{{$dt->dinas_luar_id}}</td>
               <td>{{$dt->fasyankes['nama']}}</td>
               <td><button class="btn btn-sm btn-info" onclick="ambil_berkas({{$dt->fasyankes_id}})"><i class="fa fa-eye"> Berkas</i></button></td>
               <td><button class="btn btn-sm btn-info"><i class="fa fa-users" onclick="ambil_kontak({{$dt->fasyankes_id}})"> Kontak</i></button></td>
               <td><button class="btn btn-sm btn-warning"><i class="fa fa-info-circle"> Detail </i></button> <button class="btn btn-sm btn-danger"><i class="fa fa-trash"> Hapus </i></td>

             </tr>
             @endforeach
           </tbody>

       </table>
    </div>
</div>
{{-- modal --}}
<div class="row">
    <div class="col-md-12">
        {{-- open modal --}}
        <!-- Modal -->
            <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"> API CODE </h4>
                    </div>
                    <div class="modal-body" id="getCode" style="overflow-x: scroll;">
                    //ajax success content here.
                    </div>
                </div>
                </div>
            </div>
        {{-- end modal --}}
    </div>
</div>
<!-- lihat data berkas  -->
<div class="row" style="margin-top:10px">
  <div class="col-md-12">
    <!-- <span id="lihat berkas"> -->
    <ul class="nav nav-tabs" id="ulhtml">
      <!-- <span id="ulhtml">
      </span> -->
    <!-- <li style="padding:10px;margin:2px;background-color:#dfe6e9;color:black;text-decoration:none;font-color:black"><a href="#" style="padding-right:10px;color:black">Home</a></li> -->
  </ul>
  <br>
  <!-- <p id="inframe">
  </p> -->
    <!-- </span> -->

  </div>
</div>
<!-- end liat berkas  -->



@endsection
@section('custom-foot')
<script type="text/javascript">
    $(document).ready(function(){
        $('#myTable').DataTable();
    })

    function ambil_kontak(id){

      // alert(id)
      $.ajax({
          url: "{{ URL('ambil_kontak') }}" + '/' +id,
          type: 'GET',
          dataType: 'json',
              success:function(data){
              $("#kontakkunjungan").html("");
              var html ='';
              var namafasyankes = "Nama Fasyankes :  "+data['fasyankes'][0]['nama'];
              console.log(data);
              if (typeof data['detailkontak'] !== 'undefined' && data['detailkontak'].length > 0) {
                    console.log("cetak disini");
                    for(var i=0;i<data['detailkontak'].length;i++){
                        console.log(data['detailkontak'][i]['nama_kontak']);
                        var m = i+1;
                        html = '<tr>';
                          html += '<td>'+m+'</td>';
                          html += '<td>'+data['detailkontak'][i]['nama_kontak']+'</td>';
                          html += '<td>'+data['detailkontak'][i]['jabatan_kontak']+'</td>';
                          html += '<td>'+data['detailkontak'][i]['kontak_satu']+'</td>';
                          html += '<td>'+data['detailkontak'][i]['kontak_dua']+'</td>';
                        html += '<tr>';
                        $('#kontakkunjungan').append(html);
                    }


                    // $("#kontakkunjungan").html("");
                    $('#namfasyankeskontak').append(namafasyankes);
                    $('#myModalkontak').modal('show');


                    // the array is defined and has at least one element
                  }
                  else{
                    console.log("gak ada data");
                    alert("Data kontak kosong");
                  }

              // console.log(data.nama); /tampil_berkas/'+i+'/'+id+'"
              // console.log(data);
              // $("#ulhtml").html("");
              // $('#ulhtml').append(ulhtml);
              }
          });
    }

    function ubahpegawai(id){
        tanya = confirm("Apakah anda yakin akan mengubah Data Pegawai ?");
        if(tanya == true){
            //getdata
            window.open("/fasyankesdl_json/"+id, '_blank');
        }else{
            return false;
        }
    }

    function ubahfasyankes(id){
        tanya = confirm("Apakah anda yakin akan mengubah Data Fasyankes?");
        if(tanya == true){
            window.open("/fasyankesdl_json/"+id, '_blank');
            // window.location.replace("/fasyankesdl_json/"+id);
        }else{
            return false;
        }
    }

    function ambil_berkas(id){
      // alert(id);
      $.ajax({
          url: "{{ URL('ambil_berkas') }}" + '/' +id,
          type: 'GET',
          dataType: 'json',
              success:function(data){ // detailrs
              // console.log(data.nama); /tampil_berkas/'+i+'/'+id+'"
              // console.log(data);

              var ulhtml = '';
              var m = 1;
              for(var i=0;i<data.length;i++){
              var m = i+1;
              ulhtml += '<li style="padding:10px;margin:2px;background-color:#dfe6e9;color:black;text-decoration:none;font-color:black"><a href="#" onclick="tampilkan_berkas('+i+','+id+')" id="tberkas" style="padding-right:10px;color:black">Berkas ke-'+m+'</a></li>';
              }
              $("#ulhtml").html("");
              $('#ulhtml').append(ulhtml);
              }
          });

    }

    function tampilkan_berkas(i,id){
      // alert(i)
      // alert(id)
      var pointer = i;

      // aja
      $.ajax({
          url: "{{ URL('ambil_berkas') }}" + '/' +id,
          type: 'GET',
          dataType: 'json',
              success:function(data){ // detailrs
                var keterangan = '';
              // console.log(data.nama); /tampil_berkas/'+i+'/'+id+'"
              // console.log(data[0]);
              console.log(data[pointer]['path']);
              var pathdpkumen = data[pointer]['path'];
              var ulhtml = '';
                   ulhtml += '<iframe src="'+pathdpkumen+'" width="100%" height="500px"></iframe>';
                   console.log(ulhtml);
              keterangan = '<button class="btn btn-sm btn-info" type="button">keterangan berkas : <b>'+data[pointer]['keterangan']+'</b></button>';
              $("#keterangan").html("");
              $("#inframe").html("");
              $('#inframe').append(ulhtml);
              $('#keterangan').append(keterangan);
              $('#myModal').modal('show');
              }
          });

    }


</script>
@stop
