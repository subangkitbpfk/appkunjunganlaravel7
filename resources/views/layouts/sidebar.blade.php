<div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('AdminLTE-3.0.5/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{URL::to('halamanutama')}}" class="d-block">{nama login}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <!-- admin -->
          <!-- end admin -->

          <!-- user -->
          <!-- end user -->


          <!-- kepegawaian -->

          <!-- end kepegawaian  -->


          <!-- kepala divisi  -->
          <!-- end kepala divisi -->


          <!-- kepala bpfk -->
          <!-- end kepala bpfk  -->

          <li class="nav-item has-treeview">
            <a href="{{URL::to('halamanutama')}}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <!-- cek kondisi admin -->
            <ul class="nav nav-treeview">
            @if(auth()->user()->hasRole('admin'))
              <li class="nav-item">
                <a href="{{URL::to('/form-input-dinas')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p><span class="fa fa-plus"></span> Inputan DL</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('/form-laporan-dinas')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p><span class="fa fa-plus"></span> Inputan Laporan DL</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('/view-input-dinas')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p><span class="fa fa-eye"></span> Tabel DL</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('/view-laporan-dinas')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p><span class="fa fa-eye"></span> Tabel Laporan DL</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('/home-fasyankes')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p><span class="fa fa-briefcase"></span> Data Fasyankes </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('/laporan')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p><span class="fa fa-print"></span> Cetak laporan</p>
                </a>
              </li>
            </ul>
            @elseif(auth()->user()->hasRole('pegawai'))
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link" >
                <i class="nav-icon fas fa-copy"></i>
                <p>
                  Layout Options
                  <i class="fas fa-angle-left right"></i>
                  <span class="badge badge-info right">6</span>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="../layout/boxed.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Boxed</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../layout/fixed-topnav.html" class="nav-link active" style="background-color: green;color: white">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Fixed Navbar</p>
                  </a>
                </li>
              </ul>
            </li>
            @elseif(auth()->user()->hasRole('kepaladivisi'))
              <li class="nav-item">
                <a href="../layout/fixed-topnav.html" class="nav-link active" style="background-color: green;color: white">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lihat Dinas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../layout/fixed-topnav.html" class="nav-link active" style="background-color: green;color: white">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan</p>
                </a>
              </li>

            @elseif(auth()->user()->hasRole('kepalabpfk'))
            <li class="nav-item">
              <a href="../layout/fixed-topnav.html" class="nav-link active" style="background-color: green;color: white">
                <i class="far fa-circle nav-icon"></i>
                <p>Lihat Laporan Dinas</p>
              </a>
            </li>
            @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
