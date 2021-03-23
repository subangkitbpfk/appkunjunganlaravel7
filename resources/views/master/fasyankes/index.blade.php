@extends('layouts.master')
@section('content')

<!-- modal -->
<div class="row">
  <div class="col-md-12">
    <!-- open modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <!-- <h4 class="modal-title">Modal Header</h4> -->
          </div>
          <div class="modal-body">
            <!-- form -->
            <form class="" action="{{URL('post-fasyankes')}}" method="post">
              {{csrf_field()}}
              <!-- open -->
              <form>
                <div class="form-group">
                  <label for="nama">Nama Fasyankes </label>
                  <input type="text" class="form-control" id="nama" placeholder="RS Siloam" name="namafasyankes">
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat </label>
                  <input type="text" class="form-control" id="alamat" placeholder="Jl....." name="alamat">
                </div>

                <div class="form-group">
                  <label for="alamat">Provinsi </label>
                  <select class="form-control" id="keyprovinsi" name="provinsi">
                    <option value="-" selected>Pilih</option>
                    @foreach($provinsi as $key => $node)
                    <option value="{{$key}}">{{$key}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label for="alamat">Kota </label>
                  <span id="cbbkota"></span>
                </div>
                <div class="form-group">
                  <label for="alamat">Telp </label>
                  <input type="text" class="form-control" id="telp" placeholder="031-56554434" name="telp">
                </div>
                <div class="form-group">
                  <label for="alamat">Email </label>
                  <input type="text" class="form-control" id="email" placeholder="subangkit@ymail.com" name="email">
                </div>
                <button type="submit" class="btn btn-default">Simpan</button>
              </form>
              <!-- close -->


            </form>
            <!-- end form -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
      </div>
          <!-- close modal -->
        </div>
      </div>

<!-- endmodal -->
<div class="row">
  <div class="col-md-12">
  <h5>Tabel Fasyankes <button class="btn btn-md btn-success" id="btnfasyankes" onclick="tambahfasyankes()"><i class="fa fa-plus"></i> Fasyankes</button></h5>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <table class="table table-striped table-bordered" id="myTable">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama fasyankes</th>
          <th>Alamat</th>
          <th>Provinsi</th>
          <th>Kota</th>
          <th>Telp</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1;?>
        @foreach($data as $dt)
        <tr>
          <td>{{$i++}}</td>
          <td>{{$dt->nama}}</td>
          <td>{{$dt->alamat}}</td>
          <td>{{@$dt->provinsi == '' ? '-' : $dt->provinsi}}</td>
          <td>{{@$dt->kota == '' ? '-' : $dt->kota}}</td>
          <td>{{@$dt->telepon == '' ? '-' : $dt->telepon}}</td>
          <td><button class="btn btn-xs btn-default" onclick="hapusfasyankes()"><i class="fa fa-trash" style="color:red"></i></td>
        </tr>
        @endforeach
      </tbody>

    </table>

  </div>
</div>


@endsection
@section('custom-foot')
<script type="text/javascript">
  $(document).ready(function(){
    //select onclick Provinsi
    $('#keyprovinsi').change(function(){
        var value = $(this).val();
        // alert(value);
        console.log(value);
        // ajax
        $.ajax({
        url: "{{ URL('jsonprovinsi') }}" + '/' +value,
        type: 'GET',
        dataType: 'json',
            success:function(data){ // detailrs
            $("#cbbkota").html('');
            var html = '';
            console.log(data);
            html = '<select class="form-control" name="kota" id="kota">';
              for (var i = 0; i < data[0].length; i++) {
                html += '<option value="'+data[0][i]+'">'+data[0][i]+'</option>';
              }
            html += '</select>';
            $("#cbbkota").append(html);
            console.log(html);


            }
        });
        //end ajax
    });
    //endselect
      $('#myTable').DataTable();
  });
  function tambahfasyankes(){
    // alert("test");
    $("#myModal").modal('show');
  }
</script>
@stop
