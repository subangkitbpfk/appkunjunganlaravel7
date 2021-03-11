 <nav class="main-header navbar navbar-expand navbar-white navbar-light bg-success">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item" >
        <a class="nav-link" style="color: white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link" style="color: white">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link" style="color: white">Contact</a>
      </li> -->
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
        </div>
      </li>

    </ul>
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>



        </a>
         @if (Route::has('login'))
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <a href="" class="dropdown-item" style="background-color:#dfe6e9">
            <button type="submit" value="logout" class="btn btn-info" style="width:100%;background-color:#dfe6e9;color:black">Logout</button>
            <span class="float-right text-muted text-sm"><a href=""></span>
          </a>
        </form>
            @else

          <!-- <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> Masuk
            <span class="float-right text-muted text-sm">Sign Out</span>
          </a>
        </div> -->
         @endif
      </li>

    </ul>
  </nav>
