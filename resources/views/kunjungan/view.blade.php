@extends('layouts.master')
@section('content')
<div class="row">
  <div class="col-2">
    <div class="card">
      <a href="{{URL::to('/tambah-kunjungan')}}" class="btn btn-success">+ Kunjungan</a>
      <!-- <p>Tambah Kunjungan</p> -->
    </div>
  </div>
</div>
<div class="row">
  <!-- modal upload file -->
  <div id="Modalfile" class="modal fade" role="dialog">
  <div class="modal-dialog">

<!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <!-- <p>Some text in the modal.</p> -->
          <span id="formuploadfile"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

  </div>
</div>
  <!-- end upload file -->
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Data Kunjungan Pelanggan</h3>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
               <div class="row">
                  <div class="col-sm-12 col-md-6">

                     <div class="dataTables_length" id="example1_length">
                        <!-- <label>
                           Show
                           <select name="example1_length" aria-controls="example1" class="custom-select custom-select-sm form-control form-control-sm">
                              <option value="10">10</option>
                              <option value="25">25</option>
                              <option value="50">50</option>
                              <option value="100">100</option>
                           </select>
                           entries
                        </label> -->
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                     <!-- <div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1"></label></div> -->
                  </div>
               </div>

               <div class="row">
                  <div class="col-sm-12">
                     <table id="datakunjungan" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="example1_info">
                       <thead>
                         <th>No</th>
                         <th>Nama Pegawai yg Berangkat</th>
                         <th>RS yang didatangi</th>
                         <th>Mulai</th>
                         <th>Selesai</th>
                         <th></th>
                       </thead>
                        <tbody>
                          
                        <? $gabungan = array(); ?>
                          @foreach($rumat as $dt)
                          {{-- {{dd($dt)}} --}}
                          @if($dt['status']!== 1)
                              <tr role="row" class="odd">
                                  <td>{{$dt['id']}}</td>
                                  <td>
                                    @foreach($dt['gabungan'] as $gb)
                                      @if(@gb != '')
                                        <?php
                                        $pegawai = \App\Pegawai::where('id',intval($gb))->get();
                                        foreach ($pegawai as $key => $value) {
                                          echo $value['nama'].",";
                                        }
                                        ?>
                                      @else
                                      @endif

                                    @endforeach
                                  </td>
                                  <td>
                                    @foreach($dt['get_header_detail_rs'] as $gh )
                                      @foreach($gh['get_fasyankes_dt'] as $gf)
                                        {{$gf['nama'].","}}
                                      @endforeach
                                    @endforeach

                                  </td>
                                  <td>{{$dt['mulai']}}</td>
                                  <td>{{$dt['sampai']}}</td>
                                  <!-- onclick="(uploadgambar({{$dt['id']}}))" -->
                                <td width="7%"><a href="#" onclick="hapuskunjungan({{$dt['id']}})"id="uploadfile"><span class="fa fa fa-trash" style="color:#d63031" ></span></a> <a href="#" onclick="uploadId({{$dt['id']}})"><span class="fa fa fa-upload" style="color:green"></span></a> <a href="{{URL::to('cetak-kunjungan/')}}/{{$dt['id']}}" ><span class="fa fa fa-print" style="color:grey"></span></a><a href="{{URL::to('cetak-kunjungan/')}}/{{$dt['id']}}" ><span class="fa fa fa-copy" style="color:#0984e3"></span></a></td>
                              </tr>
                            @else
                            {{-- status == 1 --}}

                            @endif

                           @endforeach
                           <!-- <tr role="row" class="even">
                              <td tabindex="0" class="sorting_1">Gecko</td>
                              <td>Firefox 1.5</td>
                              <td>Win 98+ / OSX.2+</td>
                              <td>1.8</td>
                              <td>A</td>
                           </tr> -->
                           {{-- @endforeach --}}

                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 col-md-5">
                     <!-- <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div> -->
                  </div>
               </div>
            </div>
         </div>
         <!-- /.card-body -->
      </div>
      <!-- /.card -->
   </div>
   <!-- /.col -->
</div>
@endsection
@section('custom-foot')

<script type="text/javascript">

$('a[href$="#modalfile"]').on( "click", function() {
  $("#formuploadfile").html("");
  // formuploadfile
  var buatForm ='';
          buatForm += "<form action='#' method='POST' enctype='multipart/form-data'>";
          buatForm += '<table>';
          buatForm += '<tbody>';
            buatForm += '<tr>';
              buatForm += '<td>Upload Gambar</td>';
              buatForm += "<td><input type='file' class='button' value='Upload' nama='upload' id='but_upload'></td>";
            buatForm += '</tr>';
            buatForm += '</tbody>';
          buatForm += '</table>';
          buatForm += '</form>';

   $("#formuploadfile").append(buatForm);
   $('#Modalfile').modal('show');

});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// open

// $("#fileupload").on('click',function(event) {
//   event.preventDefault();
//   alert("test");
//   this.blur(); // Manually remove focus from clicked link.
//   $.get(this.href, function(html) {
//     $(html).appendTo('body').modal();
//   });
// });
// cloase
$("#uploadfile").on('click',function(event) {
  // console.log("test");
  event.preventDefault();
  console.log("test");
  this.blur(); // Manually remove focus from clicked link.
  $.get(this.href, function(html) {
    $(html).appendTo('body').modal();
  });
});

$(document).ready(function(){
  // alert("test");
  $('#datakunjungan').DataTable();
});

function uploadId(id){ //upload file
// ambil rs nya 
  $("#formuploadfile").html("");
  var buatForm ='';
          buatForm += "<form action='{{URL('upload-foto')}}' method='POST' enctype='multipart/form-data'>";
          buatForm += '{{ csrf_field() }}';
          buatForm += '<table>';
          buatForm += '<tbody>';
            buatForm += '<tr>RS :<td></td><td></td></tr>';
            buatForm += '<tr>';
              buatForm += '<td>Upload Gambar</td>';
              buatForm += "<td>";
              buatForm += "<input type='hidden' name='id' value='"+id+"'>";
              buatForm += "<input type='file' class='button' value='Upload' name='upload' id='but_upload'></td>";
            buatForm += '</tr>';
            buatForm += '</tbody>';
          buatForm += '</table>';
          buatForm += "<input type='submit' name='uploaddata' id='uploadgambar' value='upload gambar' class='btn btn-success'>";
          buatForm += '</form>';
   $("#formuploadfile").append(buatForm);
   $('#Modalfile').modal('show');

}

function hapuskunjungan(id){
  var mss = confirm("Apakah anda yakin untuk menghapus data ini?");
  if(mss == true){
    alert(id);
    $.ajax({
      url: '{{ URL('simpan-detail-deskripsi') }}',
      type: 'POST',
      dataType: 'json',
      data: formdata,
      success:function(data){
        console.log(data)
        // refresh
      }
    });
  }

}

</script>
@stop
