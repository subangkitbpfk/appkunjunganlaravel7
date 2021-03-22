@extends('layouts.master')
@section('content')


<!-- modal  -->
<div class="">

</div>
<!-- modal rekap fasyankes -->




<!-- modal laporan perjalanan dinas -->
<!-- modal open edit pegawai -->
<div class="row">
  <div class="col-md-12">
    <div id="modallaporankesatu" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h5 class="modal-title">Cek Laporan Perjalanan Dinas</h5>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>Masukkan Kode Yang Akan Dicetak : </th>
              <th>
                <select class="form-control" id="kodeheader" name="kodeheader">
                  @foreach($dtheader as $dt)
                  <option attrstatus="{{$dt->status}}" value="{{$dt->id}}">{{$dt->id}}</option>
                  @endforeach
                </select>

              </th>
              <th><button class="btn btn-sm btn-default" style="background-color:#ff7675;color:white;padding:5px" onclick="cetaklaporanid()">Cek Laporan Dinas</button></th>
            </tr>

            <tbody>

            </tbody>
          </thead>
        </table>
        <span id="sts"></span>
        <span id="belumdiinput">
        </span>
        <form action="{{url('/laporanrelease')}}" method="post" target="_blank">
          {{csrf_field()}}
          <span id="formpost"></span>


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
<!-- end laporan perjalanan dinas -->


<div class="row" style="border-bottom:0px solid black;padding:4px">
   <div class="col-md-3" style="border-bottom:20px solid #2d3436">
     <button class="btn btn-sm btn-default" style="padding:15px;background-color:#74b9ff;color:white" onclick="laporankesatu()"><i class="fa fa-file"></i> Perjalanan Dinas</button>
     <!-- <p>laporan pertama</p> -->

   </div>
   <div class="col-md-3" style="border-bottom:5px solid #2d3436">
     <button class="btn btn-sm btn-default" style="padding:15px;background-color:#55efc4;color:white"><i class="fa fa-file"></i> Perjalanan Dinas</button>
   </div>
   <div class="col-md-3" style="border-bottom:5px solid #2d3436">
     <button class="btn btn-sm btn-default" style="padding:15px;background-color:#81ecec;color:white"><i class="fa fa-file"></i> Perjalanan Dinas</button>
   </div>
   <div class="col-md-3" style="border-bottom:5px solid #2d3436">
     <button class="btn btn-sm btn-default" style="padding:15px;background-color:#636e72  ;color:white"><i class="fa fa-file"></i> Perjalanan Dinas</button>
   </div>

</div>

<div class="row">
  <div class="col-md-12">
    <span id="keterangan_perjalanan">
    </span>
  </div>
</div>

@endsection
@section('custom-foot')
<script type="text/javascript">

function laporankesatu(){
  $('#modallaporankesatu').modal('show');
}

function cetaklaporanid(){
  $('#belumdiinput').html('');
  $('#sts').html('');
  $('#formpost').html('');
  var form = '';
  var id = $("#kodeheader option:selected").val();
  // var id = $("#kodeheader option:selected").attr("attrstatus");
  // alert(id);
  var arrystatus = [];
  var arrystatusok = [];
  var tabel = '';
  $.ajax({
    url: "{{ URL('laporan') }}" + '/' +id,
    type: 'GET',
    dataType: 'json',
        success:function(data){
          console.log(data);
          console.log(data.cekstatus.length);
          // for untuk pengecekkan tombol cetak
          for(var i=0;i<data.cekstatus.length;i++){

            if(data.cekstatus[i].status == 0){//jika status 0 maka masukkan kesini dan tampilkan
              //mohon maaf data harus dilengkapi agar bisa di cetak
              // console.log("data masih ada yang kosong");
              arrystatus.push(data.cekstatus[i].namafasyankes.nama);
              console.log(arrystatus);
              // alert(arrystatus);
            }else if(data.cekstatus[i].status == 1){
              //data sudah lengkap
              // console.log("data lengkap bisa dicetak");
              arrystatusok.push(data.cekstatus[i].namafasyankes.nama);

            }





          }
          //end for cetak

          // validasi
          if(arrystatus.length > 0){
            $('#sts').append('<b style="color:gray">Lengkapi data dibawah ini</b>');
            console.log("tidak boleh dicetak");
            for(var a=0;a<arrystatus.length;a++){
              tabel = '<p style="background-color:#d63031;color:white;padding:5px">'+arrystatus[a]+'</p>'
              $('#belumdiinput').append(tabel);
            }
            alert("Mohon maaf laporan tidak bisa dicetak, dikarenakan data tidak lengkap!");
            //belumdiinput
          }else{
            console.log("boleh dicetak");

            // console.log(data)
            var formpost ='';
              formpost +='<input type="text" value="'+data.data.id+'" name="dinas_luar_id" readonly style="background-color:gray;color:white"/>';
              formpost +=' <button type="submit" class="btn btn-sm btn-info"> <i class="fa fa-print"></i> Silahkan dicetak</button> ';
              $('#formpost').append(formpost);



            // var id = 1;
            // window.open("/laporan/"+id,"_blank");
            // window.location = "/laporan/" + id
          }
          //end validasi

        }
  });

}

</script>
@stop
