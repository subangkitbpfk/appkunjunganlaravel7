@extends('layouts.master')
@section('content')

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
        <h5 class="modal-title">Isian Laporan Perjalanan Dinas</h5>
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
              <th><button class="btn btn-sm btn-default" style="background-color:#ff7675;color:white;padding:5px" onclick="cetaklaporanid()">Cetak Laporan Dinas</button></th>
            </tr>

            <tbody>

            </tbody>
          </thead>
        </table>
        <form action="#" method="post">
          {{csrf_field()}}


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
  var id = $("#kodeheader option:selected").val();
  // var id = $("#kodeheader option:selected").attr("attrstatus");
  alert(id);
  $.ajax({
    url: "{{ URL('laporan') }}" + '/' +id,
    type: 'GET',
    dataType: 'json',
        success:function(data){
          console.log(data);
        }
  });

}

</script>
@stop
