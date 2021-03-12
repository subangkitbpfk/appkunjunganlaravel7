@extends('layouts.master')
@section('content')
<div class="row" style="background-color: white">
    <div class="col-md-12" style="margin-left:80%">
       <label for="" >status : {status}</label>
    </div>
</div>
<div class="row" style="background-color: white">
    <div class="col-md-6" style="margin-left:5%">
       <label for="" style="padding:10px;background-color:#dfe6e9"><i class="fa fa-plus"></i> INPUTAN DINAS LUAR </label>
    </div>
                         @if (\Session::has('success'))
						    <div class="alert alert-info" style="margin-left:5%;background-color:#81ecec">
						        <ul>
						            <li style="list-style-type: none;"><b> {!! \Session::get('success') !!}</b></li>
						        </ul>
						    </div>
						@endif
</div>
<form method="post" action="{{url('form-input-dinas')}}" >
{{csrf_field()}}
{{-- layout1 --}}
<div class="row" style="">
    <div class="col-md-6" style="margin-left:5%;padding-bottom:5px">
        <label for="">Mulai :</label>
        <input type="text" class="form-control datepickermulai" id="mulaitanggal" name="mulaitanggal" placeholder="Mulai" required style="margin-bottom: 5px">
        <label>Sampai :</label>
        <input type="text" class="form-control datepickersampai" id="mulaitanggal" name="sampaitanggal" placeholder="Sampai" required>
    </div>
    <div class="col-md-6">
    </div>
</div>
{{-- end layout 1 --}}
{{-- layout 2 --}}
<div class="row" style="margin-top:0px">
    <div class="col-md-12" style="text-align: left;margin:0;background-color:white;padding-top:10px">
        <table style="margin-left:5%">
            <tbody id="form-body">
            <tr>
                <td colspan="3">
                    <b>Petugas yang diberangkatkan : </b>
                </td>
            </tr>
            </tbody>

        </table>
        <button type="button" onclick="add_form()" class="btn btn-sm btn-success" style="margin-left:5%"><i class="fa fa-plus"></i> Petugas</button>
    </div>
</div>
{{-- end layout 2 --}}
<div class="row">
    {{-- <div class="col-md-12"> --}}
        <div class="col-md-12" style="text-align: left;margin:0;background-color:white;padding-top:10px">
            <table style="margin-left:5%;width:80%" >
                <tbody id="form-fasyankes">
                <tr>
                    <td colspan="3">
                        <b>Nama Fasyankes yang akan dikunjungi : </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <select class="form-control fasyankes" name="fasyankes" id="fasyankes" style="background-color:gray;color:white;width:100%;font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;font-size:12pt">
                            {{-- <option value="-" selected="selected">-Pilih- </option>     --}}
                            @foreach ($datas as $data )
                            <option value="{{$data->id}}" >{{Str::limit($data->nama,50)}}</option>
                            @endforeach
                          </select>
                    </td><td><button style="margin-left:3px;margin-bottom:5px" class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal">+ Fasyankes</button></td></tr>
                <tr style="background-color:gray;color:white;">
                    <td style="border-right:1px solid white">
                        Kode Faskes
                    </td>
                    <td style="border-right:1px solid white">
                        Nama Faskes
                    </td>
                    <td style="border-right:1px solid white">
                        Alamat Faskes
                    </td>
                    <td style="border-right:1px solid white">
                        Kota
                    </td>
                    <td>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>

    {{-- </div> --}}
</div>
{{-- layout button --}}
<div class="row" >
    <div class="col-md-12" style="text-align: left;margin:0;background-color:white;padding-top:20px;">
        <table style="margin-left:5%">
            <tr>
                <td>
                    <button type="submit" class="btn btn-md btn-info" id="btnsimpan" name="btnsimpan" style="padding: 18px;margin-right:10px;margin-bottom:40px">SIMPAN</button>
                </td>
                <td>
                    <button type="button" class="btn btn-md btn-warning" id="btnposting" style="padding: 18px;margin-bottom:40px">POSTING</button>
                </td>
                <td>
                    <button type="button" class="btn btn-md btn-danger" id="btndeaktif" style="padding: 18px;margin-left:10px;margin-bottom:40px">DEAKTIF</button>
                </td>
            </tr>

        </table>
    </div>
</div>
</form>
{{-- end button --}}

{{-- modal --}}
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
          <h4 class="modal-title">Form Tambah Fasyankes</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
        </div>
      </div>

    </div>
  </div>

  {{-- <button type="button" class="btn btn-info btn-lg" >Open Modal</button> --}}

{{-- end modal --}}


@endsection
@section('custom-foot')
<script type="text/javascript">
$(document).ready(function(){

    $('#fasyankes').select2();
    $('.pegawai').select2();
    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
    // alert("test");
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

    $("select.fasyankes").change(function(){
        var id = $(this).children("option:selected").val();
        // alert("id" + id);

        $.ajax({
            url: "{{ URL('fasyankes_json') }}" + '/' +id,
            type: 'GET',
            dataType: 'json',
                success:function(data){ // detailrs
                // console.log(data.nama);
                console.log(data.id);
                var html = '';
                var index;
                html += '<tr style="border-bottom:1px solid gray">';
                html += '<td><input type="hidden" class="form-control" name="fasyankes_id[]" value="'+data.id+'">'+data.id+'</td>';
                html += '<td><input type="hidden" class="form-control" >'+data.nama+'</td>';
                html += '<td>'+data.alamat+'</td>';
                html += '<td>'+data.kota+'</td>';
                html += '<td><button type="button" class="btn btn-xs btn-danger" onclick="del_form(this)"><i class="fa fa-trash"></i></button></td>';
                html += '</tr>';
                $('#form-fasyankes').append(html);
                }
            });

    });
});

function add_form()
        {

            $.ajax({
            url: "{{ URL('get-pegawai') }}",
            type: 'GET',
            dataType: 'json',
                success:function(data){ // detailrs
                // console.log(data.nama);

                console.log(data);
                console.log(data[0].nama);
                console.log(data.length);
                var html = '';
                var index;
                html += '<tr>';
                html += '<td>';
                    html += '<select class="form-control pegawai" name="pegawai[]" id="pegawaiget">';
                        for (index = 0; index < data.length; ++index) {
                        html +='<option value="'+data[index].id+'">'+data[index].nama+'</option>';
                        }
                        html +='</select>';
                    html +='</td>';
                html += '<td><button type="button" class="btn btn-xs btn-danger" onclick="del_form(this)"><i class="fa fa-trash"></i></button></td>';
                html += '</tr>';
                $('#form-body').append(html);
                // $('#form-body').select2();
                // $('.pegawai').select2();
                }

            });



        }

        function del_form(id)
        {
            id.closest('tr').remove();
        }
        function add_faskes(){
            var id = $('#fasyankes');
            alert(id);

        }


</script>
@stop
