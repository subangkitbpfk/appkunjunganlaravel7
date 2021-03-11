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