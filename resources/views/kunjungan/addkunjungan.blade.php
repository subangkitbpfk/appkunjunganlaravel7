@extends('layouts.master')
@section('content')
<div class="row">
  <div class="col-2">
    <div class="card">
      <a href="{{URL::to('/tambah-kunjungan')}}" class="btn btn-success"><span class="fa fa-plus"></span> Kunjungan</a>
      <!-- <p>Tambah Kunjungan</p> -->
    </div>
  </div>
  <div class="col-2">
    <div class="card">
      <a href="{{URL::to('/kunjungan')}}" class="btn btn-info"><span class="fa fa-eye"></span> Lihat </a>
      <!-- <a href="{{URL::to('/tambahkunjungan')}}" class="btn btn-success">+ Kunjungan</a> -->
      <!-- <p>Tambah Kunjungan</p> -->
    </div>
  </div>
  <div class="col-3">
    <div class="card">
      <!-- <a href="{{URL::to('/tambahkunjungan')}}" class="btn btn-success">+ Kunjungan</a> -->
      <!-- <p>Tambah Kunjungan</p> -->
    </div>
  </div>
  <div class="col-3">
    <div class="card">
      <!-- <a href="{{URL::to('/tambahkunjungan')}}" class="btn btn-success">+ Kunjungan</a> -->
      <!-- <p>Tambah Kunjungan</p> -->
    </div>
  </div>
</div>
<!-- form start -->
<form method="POST" id="formheader">
  {{csrf_field()}}
<div class="row" >

  <div class="col-6">
    <div class="card" style="padding:10px">
      <div class="form-group">
          <label for="tempat">Nama Yang Akan Memberangkatkan : </label>
          <input type="text" class="form-control" id="tempatrs" placeholder="Nama" value="R Wisnu Dwi Hardyanto,ST (Kasubag TU)" required>
      </div>
      <div class="form-group">
          <label for="tempat">Tempat Yang Dikunjungi : </label>
          <!-- multiple select -->
          <select class="form-control js-example-basic-single" name="nmrumahsakit" id="nmrumahsakit" required>
            <option value="0">- pilih Rumah Sakit -</option>
            @foreach($fasyankesdt as $fs)
              <option value="{{ $fs->id }}">{{ $fs->nama }}</option>
            @endforeach
          </select>
          <!-- end multiple select -->
      </div>
      <div class="form-group" id="datepicker">
          <label for="tempat">Mulai : </label>
          <input type="text" class="form-control datepicker" id="mulaitanggal" name="mulaitanggal" placeholder="Mulai" required>
      </div>
      <div class="form-group">
          <label for="tempat">Sampai : </label>
          <input type="text" class="form-control datepicker" id="akhirtanggal" name="akhirtanggal" placeholder="Akhir" required>
      </div>

      <div class="form-group">
          <label for="tempat">Nama Yang Melakukkan Perjalanan Dinas : </label><br>
          <select class="form-group js-example-basic-multiple" name="states[]" multiple="multiple" id="states" style="width:100%;color:black;padding:2px" required>
            @foreach($pegawai as $p)
              <option value="{{ $p->id }}">{{ $p->nama }}</option>
            @endforeach
          </select>
      </div>
      <div class="form-group">
        <button class="btn btn-success" id="apply"><span class="fa fa-save"></span> Apply</button>
      </div>
    </div>
  </div>
  <div class="col-6">
      <div class="card" style="padding-left:0px;padding-right:0px">
        <p style="background-color:grey;color:white;padding-left:5px">Detail Rumah Sakit</p>
        <!-- detail untuk rumah sakit -->
        <span id=detailrs></span>
        <button class="btn btn-danger" id="btnhapusde"><span class="fa fa-trash"></span> Hapus</button>
      </div>
  </div>

</div>
</form>

<!-- batas nya -->
<form action="{{url('simpan-detail-deskripsi')}}"  method="post" enctype="multipart/form-data">
  {{-- id="detail_laporan" --}}
  {{csrf_field()}}
<!-- looping row -->
<div class="row" >
  <span class="row" style="background-color:white;padding-left:15px;padding-top:5px;padding-right:15px" id="detailformlaporan"></span>
  <!-- <input type='text' class='form-control' id='namars' placeholder='Nama RS' value='' style='margin-bottom:5px'>
  <input type='text' class='form-control' id='petugas' placeholder='petugas' value='' style='margin-bottom:5px'>
  <input type='text' class='form-control' id='hp' placeholder='hp' value='' style='margin-bottom:5px'>
  <input type='text' class='form-control' id='email' placeholder='email' value='' style='margin-bottom:5px'>
  <textarea class='form-control' rows='5' style='margin-bottom:5px' placeholder='Detail Penyelesaian'></textarea>
  <textarea class='form-control' rows='5'style='margin-bottom:5px' placeholder='Informasi Simponi'></textarea>
  <hr> -->
  <button type="submit" class="form-group btn btn-success" style="margin-top:10px" id=""><span class="fa fa-save"></span> Simpan</button>
  {{-- submitdetail --}}
</form>
  
</div>
@endsection
@section('custom-foot')
<script type="text/javascript">
  function refreshPage(){
        window.location.reload();
    }
  function clearheader(){//clear form
    $("#detailrs").html("");
    $("#nmrumahsakit").val("");
    $("#mulaitanggal").val("");
    $("#akhirtanggal").val("");
    $("#states").val("");

    // $("#states").select2("val", "");
    // $(".js-example-basic-single").select2("val", "");
  }
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  // function appliy untuk save ke detain
  // end function

  $("#btnhapusde").on('click',function(e){
      $("#detailrs").html("");
      e.preventDefault();
    });

  $(document).ready(function() {
    $('#apply').prop('disabled', false);
    $('.js-example-basic-multiple').select2();
    $('.js-example-basic-single').select2();
    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
    // alert("test");
    $("#mulaitanggal").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            
    });

    // hapus clear detailr
    var actionOptrs = $('#nmrumahsakit');
    $(actionOptrs).change(function(event){ //untuk combobox
      var id = actionOptrs.val();
      var buatTabel = '';
      $.ajax({
          url: "{{ URL('fasyankes_json') }}" + '/' + id,
          type: 'GET',
          dataType: 'json',
          success:function(data){ // detailrs
            // console.log(data.nama);
            buatTabel = '';
            buatTabel += "<li style='background-color:#f1f2f6;list-style-type:none;font-size:11pt;padding-left:5px'>" + "#) "+data.nama +" - "+data.alamat+" - "+data.telepon+"</li>";
            buatTabel +="<input type='hidden' name='rsdetail[]' value="+data.id+" id='tampungarrys'>";
            $("#detailrs").append(buatTabel);
          }
      });
    });

    $("#submitdetail").on('click',function(e){
      var formdata = $("#detail_laporan").serialize();
      e.preventDefault();
      console.log(formdata);
      var mss = confirm("Apakah anda yakin untuk menyimpan datanya");
      if(mss == true){
        console.log("benar");
        $.ajax({
          url: '{{ URL('simpan-detail-deskripsi') }}',
          type: 'POST',
          dataType: 'json',
          data: formdata,
          success:function(data){
            if(data['msg']=='success'){
              alert("Data berhasil disimpan");
              refreshPage();
            }
          }
        });
      }else{
        console.log("gagal/cancel");
      }
    });



    // insert kedatabase
    $("#apply").on('click',function(e){
      var formdata = $("form").serialize();
      e.preventDefault()
      var buatHtml= '';
      var mss = confirm("Apakah anda akan Menyimpan data dan melanjutkan proses selanjutanya ?")
      if(mss==true){
        // clear textboc
        clearheader();
        $('#apply').prop('disabled', true);
        $(".js-example-basic-multiple").prop("disabled", true);
        $(".js-example-basic-single").prop("disabled", true);
        $("#detailformlaporan").html("");
        $.ajax({
            url: '{{ URL('simpan-kunjungan-header') }}',
            type: 'POST',
            dataType: 'json',
            data: formdata,
            // contentType: false,
            // cache: false,
            // processData:true,
            success:function(data){
              console.log(data)
              var jum = data['mapRs'].length;
              var headerkunj_id = data['headerkunjungan'];
              console.log(headerkunj_id)
              // loping form
              for(i=0;i<jum;i++){
                console.log(data['mapRs'][i]['nama']);
                
                buatHtml += "#) Nama Rumah Sakit : <b> "+data['mapRs'][i]['nama']+"</b>";
                buatHtml += "<input class='form-control' type='file' name='fileupload[]' value='fileupload' id='fileupload' style='background-color:white;padding-left:5px;margin-bottom:8px'>";
                buatHtml += "<input type='hidden' class='form-control' id='headerkunj_id' name='headerkunj_id[]' placeholder='Nama RS' value="+headerkunj_id+" style='margin-bottom:5px'>";
                buatHtml += "<input type='hidden' class='form-control' id='namars' name='namars[]' placeholder='Nama RS' value="+data['mapRs'][i]['id']+" style='margin-bottom:5px'>";
                buatHtml += "<input type='text' class='form-control' id='petugas' name='petugas[]' placeholder='petugas' value='' style='margin-bottom:5px'>";
                buatHtml += "<input type='text' class='form-control' id='hp' name='hp[]' placeholder='hp' value='' style='margin-bottom:5px'>";
                buatHtml += "<input type='text' class='form-control' id='email' name='email[]' placeholder='email' value='' style='margin-bottom:5px'>";
                buatHtml += "<textarea class='form-control' rows='5' style='margin-bottom:5px' name='detailpenyelesaian[]' placeholder='Detail Penyelesaian'></textarea>";
                buatHtml += "<textarea class='form-control' rows='5'style='margin-bottom:5px' name='informasisimponi[]' placeholder='Informasi Simponi'></textarea>";
                buatHtml += "<hr>";
              }// end looping form
              $("#detailformlaporan").append(buatHtml);
              // append ke page
            }
        });
      }else{
        console.log("cancel");
      }
      // message
      // console.log(data);
      $("#isifile").html("");
      // kirim ke database
    });
    // end insert kedatabase
  });
</script>
@stop
