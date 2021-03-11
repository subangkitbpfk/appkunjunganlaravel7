@extends('layouts.master')
@section('content')
{{-- status --}}
<div class="row" style="background-color: white">
    <div class="col-md-12" style="margin-left:48%">
       <label for="" >status : {status}</label>
    </div>
</div>
{{-- end status --}}
<div class="row" style="background-color: white">
    <div class="col-md-12" style="margin-left:5%">
       <label for="" style="padding:10px;background-color:#dfe6e9"><i class="fa fa-plus"></i> INPUTAN LAPORAN DINAS LUAR </label>
    </div>
</div>

{{--  --}}
<form method="post" action="{{url('form-laporan-dinas')}}">
  {{csrf_field()}}
<div class="row">
    <div class="col-md-6" style="margin-left:5%;padding-bottom:5px">
        <div class="form-group">
            <label for="sel1">Kode Dinas Luar:</label>
            <select class="form-control kodedinasluar" id="kodedinasluar" name="kodedinasluar">
                @foreach ( $dtinputandinas as $dt)
                <option value="{{$dt->id}}">{{$dt->id}}</option>
                @endforeach

            </select>
        </div>
    </div>
    {{-- button --}}
    <div class="col-md-2" style="background-color:white">


    </div>
</div>
<div class="row" style="">
    <div class="col-md-6" style="margin-left:5%;padding-bottom:5px">
        <label for="">Mulai :</label>
        <input type="text" class="form-control datepickermulai" id="mulaitanggal" name="mulaitanggal" placeholder="Mulai" required style="margin-bottom: 5px "readonly>
        <label>Sampai :</label>
        <input type="text" class="form-control datepickersampai" id="sampaitanggal" name="sampaitanggal" placeholder="Sampai" required readonly>
    </div>
    <div class="col-md-6">
    </div>
</div>

<div class="row">
    <div class="col-md-6" style="margin-left:5%;padding-bottom:5px">
        <div class="form-group">
            <label for="sel1">Kode Faskes:</label>
            <span id="html-faskes"></span>
        </div>
        {{-- <label for="">{nama faskes}</label>  --}}
    </div>
</div>

<div class="row">
    <div class="col-md-6" style="margin-left:5%;padding-bottom:5px">
        <label for="Hasil Dinas Luar"></label>
        <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">Hasil Dinas Luar</span>
            </div>
            <textarea class="form-control" aria-label="With textarea" name="hasildinasluar"></textarea>
          </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6" style="margin-left:5%;padding-bottom:5px">
        <table>
            <button type="button" onclick="add_form_berkas()" class="btn btn-sm btn-warning" style="margin-left:0%"><i class="fa fa-plus"></i> Berkas</button>
            <tbody id="form-berkas">
            <tr>
                <td>Berkas</td>
                <td>Keterangan / note </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
{{-- kontak --}}
<div class="row" style="margin-bottom:10px">
    <div class="col-md-12" style="margin-left:5%;padding-bottom:5px">
        <label for="">Kontak </label><button type="button" onclick="add_form()" class="btn btn-sm btn-success" style="margin-left:42%"><i class="fa fa-plus"></i> Petugas</button>
        <table style="margin-left:0%;width:80%;color:black;border-bottom:1px solid gray;padding:10px">
            <tbody id="form-kontak">
            <tr>
                <td style="background-color: gray;color:white">Nama Kontak</td>
                <td style="background-color: gray;color:white">Nama Jabatan</td>
                <td style="background-color: gray;color:white">Nama Kontak 1(satu)</td>
                <td style="background-color: gray;color:white">Nama Kontak 2(dua)</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
{{-- end kontak --}}

{{-- persetujuan --}}
<div class="row">

    <div class="col-md-12" style="margin-left:5%;padding-bottom:5px">
        Persetujuan :
        <table style="margin-left:0%;width:80%;background-color:gray;color:white;border-bottom:1px solid gray">
            <thead>
            <tr>
                <th>NAMA</th>
                <th>NIK</th>
            </tr>
            </thead>
            <tbody id="persetujuan">
            </tbody>

                      {{-- <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                        <label class="form-check-label" for="inlineRadio1">1</label>
                      </div>

                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                        <label class="form-check-label" for="inlineRadio1">1</label>
                      </div> --}}





        </table>

    </div>

</div>
{{-- persetujuan --}}
<div class="row">
    <div class="col-md-12">
        <button type="submit" class="btn btn-sm btn-info" style="margin-left:5%;padding:20px"><i class="fa fa-save"></i>  SIMPAN</button>
        <button type="button" class="btn btn-sm btn-success" style="padding:20px;color:white;width:100px"><i class="fa fa-file" style="color: white"></i>  FINAL</button>

    </div>

</div>
</form>




@endsection
@section('custom-foot')
<script type="text/javascript">
$(document).ready(function(){
    $('.kodefaskes').select2();
    $('.fasyankes').select2();
    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
    $(".datepickermulai").datepicker({
            clearButton: true,
            autoclose: true,

            position:"left",
            Placement:'bottom'
    });
    $(".datepickersampai").datepicker({
            clearButton: true,
            autoclose: true,
            Placement:'bottom',
            position:"left",
    });

    // select untuk kode dinas luar
    $("select.kodedinasluar").change(function(){
        var id = $(this).children("option:selected").val();
        $.ajax({
            url: "{{ URL('getfasyankesfromdinas') }}" + '/' +id,
            type: 'GET',
            dataType: 'json',
                success:function(data){ // detailrs
                // console.log(data[0].pegawai);
                // console.log(data.length);
                // console.log(data[0].dinasluar.tanggal_berangkat);
                // console.log(data[0].dinasluar.tanggal_pulang);
                var tanggal_berangkat = data[0].dinasluar.tanggal_berangkat;
                var tanggal_pulang = data[0].dinasluar.tanggal_pulang;
                // console.log(data);
                // str.substring
                $('#mulaitanggal').val(tanggal_berangkat.substring(0,10));
                $('#sampaitanggal').val(tanggal_pulang.substring(0,10));

                var html = '';
                var table= '';
                var index;
                var hitung;
                // html += '<tr>';
                // html += '<td>';
                    html += '<select class="form-control pegawai" name="faskes_id[]" id="pegawaiget">';
                        for (index = 0; index < data.length; index++) {
                            // console.log(data[index]);
                            // console.log(data[index].length);
                            // console.log(data[index].nama.id);
                            // console.log(data[index].nama.nama);
                            html +='<option value="'+data[index].nama.id+'">'+data[index].nama.nama+'</option>';
                            }

                        // }
                    html +='</select>';
                     //untuk persetujuan
                     for(var a=0;a<data[0].pegawai.length;a++){
                        // console.log(data[0].pegawai[a]['pegawai']);
                        // console.log(data[0].pegawai[a]['pegawai']['nama']);
                     table += '<tr style="border-bottom:1px solid gray">';
                        table += '<td><input type="hidden" class="form-control" name="pegawai_id[]" value="'+data[0].pegawai[a]['pegawai']['id']+'">'+data[0].pegawai[a]['pegawai']['nama']+'</td>';
                        table += '<td>'+data[0].pegawai[a]['pegawai']['nik']+'</td>';
                        table += '<td><select class="form-control" name="pilihan[]"><option value="ya">ya</option><option value="tidak">tidak</option></select></td>';
                    table += '</tr>';
                    }
                    //end persetujuan

                    $("#html-faskes").html("");
                    $("#persetujuan").html("");
                    $('#html-faskes').append(html);
                    $('#persetujuan').append(table);
                //     html +='</td>';
                // html += '<td><button type="button" class="btn btn-xs btn-danger" onclick="del_form(this)"><i class="fa fa-trash"></i></button></td>';
                // html += '</tr>';

                }
            });

        // alert(id);
    })
    //end


});

function add_form(){
                // console.log(data.nama);
                var html = '';
                var index;
                html += '<tr style="border-bottom:1px dotted gray">';
                    html += '<td style="border-radius:0"><input type="text" class="form-control" name="id[]" value="" placeholder="contoh:Subangkit"></td>';
                    html += '<td><input type="text" class="form-control" name="namajabatan[]" value="" placeholder="Divisi/Staff Keuangan"></td>';
                    html += '<td><input type="text" class="form-control" name="namakontaksatu[]" value="" placeholder="08213991127"></td>';
                    html += '<td><input type="text" class="form-control" name="namakontakdua[]" value="" placeholder="subangkit.bpfk@gmail.com"></td>';
                    html +='</td>';
                html += '<td><button type="button" class="btn btn-xs btn-danger" onclick="del_form(this)"><i class="fa fa-trash"></i></button></td>';
                html += '</tr>';
                $('#form-kontak').append(html);
                // $('#form-body').select2();
                // $('.pegawai').select2();



        }
        function add_form_berkas(){
                // console.log(data.nama);
                var html = '';
                var index;
                html += '<tr style="border-bottom:1px dotted gray">';
                    html += '<td><input type="file" class="" id="" name="berkas[]" required></td>';
                    html += '<td style="border-radius:0"><input type="text" class="form-control" name="keteranganberkas[]" value=""></td>';
                html += '<td><button type="button" class="btn btn-sm btn-danger" onclick="del_form(this)"><i class="fa fa-window-close"></i></button></td>';
                html += '</tr>';
                $('#form-berkas').append(html);
                // $('#form-body').select2();
                // $('.pegawai').select2();
        }
        function del_form(id)
        {
            id.closest('tr').remove();
        }





</script>
@stop
