@extends('layouts.master')
@section('content')
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


</script>
@stop
