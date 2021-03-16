@extends('layouts.master')
@section('content')
<!-- modal open edit pegawai -->
<div class="row">
  <div class="col-md-12">
    <div id="modaleditpegawai" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h5 class="modal-title">View Edit Pegawai <button class="btn btn-xs btn-success" onclick="tambahpegawai()"><i class="fa fa-plus-circle"></i> Pegawai</buttn></h5>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nik</th>
              <th>Nama</th>
              <th>Aksi</th>
            </tr>

            <tbody id="pegawai_ubah_data">

            </tbody>
          </thead>
        </table>
        <form action="#" method="post">
          {{csrf_field()}}
        <span id="form_ubah_data"></span>

      </form>
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
<!-- end modal open -->

<div class="row" style="background-color: white;">
    <div class="col-md-12" style="">
       <label for="" style="font-size:1.3em;padding:2px;">Tabel Dinas</label>
    </div>
</div>
<div class="row" style="background-color: white">
    <div class="col-md-12" style="margin-left:0%;">
       <table class="table table-striped table-bordered" id="myTable">
           <thead>
                <th>No</th>
                <th>Kode Dinas</th>
                <th>Tanggal Berangkat</th>
                <th>Tanggal Pulang</th>
                <th>Pegawai</th>
                <th>Fasyankes Dituju</th>
                <th>Status</th>
                {{-- <th></th> --}}
           </thead>
           <tbody>
               <?php $i=0?>
               @foreach ($data as $dt)
               <tr>
                   <td><?php echo $i = $i + 1;?></td>
                   <td>{{$dt->id}}</td>
                   <td>{{$dt->tanggal_berangkat}}</td>
                   <td>{{$dt->tanggal_pulang}}</td>
                    <?php $pegawais = \App\Detailpegawaidinasluar::where('dinas_luar_id',$dt->id)->get(); ?>
                    <td>
                        @foreach ( $pegawais as $p )
                        {{$p->pegawai['nama'].","}}
                        @endforeach
                        <button class="btn btn-xs btn-warning" onclick="ubahpegawai({{$dt->id}})"><i class="fa fa-edit"></i></button>
                    </td>
                   <td>
                    <?php $tujuandinas = \App\Detailtujuandinasluar::where('dinas_luar_id',$dt->id)->get(); ?>
                        @foreach ( $tujuandinas as $p )
                        {{$p->fasyankes['nama'].","}}
                        @endforeach
                        <button class="btn btn-xs btn-warning" onclick="ubahfasyankes({{$dt->id}})"><i class="fa fa-edit"></i></button>

                   </td>
                   <td>
                       <?php
                       if($dt->status == 0){
                           $sts = 'Simpan';
                       }else{
                           $sts = 'Posting';
                       }
                       ?>
                       {{$sts}}
                    </td>
                   {{-- <td><button type="submit" class="btn btn-xs btn-warning">Edit</button></td> --}}
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
@endsection
@section('custom-foot')
<script type="text/javascript">
    $(document).ready(function(){
        $('#myTable').DataTable();
    })
    function ubahpegawai(id){
        tanya = confirm("Apakah anda yakin akan mengubah Data Pegawai ?");
        // clear INPUTAN


        if(tanya == true){
          $('#form_ubah_data').html('');
            // modal open
            $.ajax({
                url: "{{ URL('edit_pegawai') }}" + '/' +id,
                type: 'GET',
                dataType: 'json',
                    success:function(data){


                      console.log(data);
                      var dinas_id='';
                      var pegawai_id='';


                      var html ='';
                          for(var i=0;i<data.length;i++){
                            var m=i+1;
                          html +='<tr>';
                          html +='<td>'+m+'</td>';
                          html +='<td>'+data[i].nik+'</td>';
                          html +='<td>'+data[i].namapegawai+'</td>';
                          html +='<td><btn class="btn btn-sm btn-info" id="pilihupdate" onclick="pilihpegawai('+data[i].dinas_luar_id+','+data[i].nip+')"> Pilih </btn</td>';
                          html +='</tr>';
                          $('#pegawai_ubah_data').html('');
                          $('#pegawai_ubah_data').append(html);
                        }

                          // $('#pegawai_ubah_data').append(html);
                          $('#modaleditpegawai').modal('show');
                    }
                });
            // window.open("/fasyankesdl_json/"+id, '_blank');
        }else{
            return false;
        }
    }

    function pilihpegawai(i,nip){
      var fasyankes = i;
      var pin = nip;
      var form='';
      // alert("test");
      $('#form_ubah_data').html('');
      $.ajax({
        url: "{{ URL('get-pegawai-selected') }}"+'/'+fasyankes+'/get/'+pin,
        type: 'GET',
        dataType: 'json',
          success:function(data){
            // console.log(data);
            // pegawai
                    form += '<div class="form-group"><label for="usr">Pegawai lama:</label><input type="hidden" name="pin" value="'+data.pegawai.nip+'"/><input type="text" class="form-control" id="usr" value="'+data.pegawai.nama+'" readonly></div>';
                    form += ' <div class="form-group">';
                    form +=  '<label for="sel1">Pilih diganti Pegawai:</label>';
                    form +=   '<select class="form-control" id="sel1">';
                    for(var i=0;i<data['allpegawai'].length;i++){
                    form +=   '<option value="'+data['allpegawai'][i]['id']+'">'+data['allpegawai'][i]['nama']+'</option>';
                    }
                    form +=   '</select>';
                    form +=   '</div> ';
                    form += '<button class="btn btn-sm btn-success">Ubah data</button>';
            console.log("data masuk");
            $('#form_ubah_data').html('');
            $('#form_ubah_data').append(form);

            // end pegawai selected
          }

      });

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

    function tambahpegawai(){
      tanya = confirm("Apakah anda yakin akan Menambah Data Pegawai?");
      if(tanya == true){
        $('#form_ubah_data').html('');
          alert("tambah data pegawai");
          // document.getElementById("pilihupdate").disabled = true;
          // ajax search pegawai && view
          $.ajax({
            url: "{{ URL('get-pegawai') }}",
            type: 'GET',
            dataType: 'json',
              success:function(data){
                console.log(data);
                console.log(data[0]['nama']);
                var form='';

                        form += ' <div class="form-group">';
                        form +=  '<label for="sel1">Pilih Pegawai:</label>';
                        form +=   '<select class="form-control" id="sel1">';
                        for(var i =0;i<data.length;i++){
                        form +=   '<option value="'+data[i]['id']+'">'+data[i]['nama']+'</option>';
                        }
                        form +=   '</select>';

                        form +=   '</div> ';
                        form += '<button class="btn btn-sm btn-success">Save data</button>';
                        $('#form_ubah_data').append(form);

                        //btn pilih disable
              }

          });

          // window.location.replace("/fasyankesdl_json/"+id);
      }else{
          return false;
      }

    }


</script>
@stop
