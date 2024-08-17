<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="navbar-brand d-flex align-items-center justify-content-center" href="http://localhost:8080/home/beranda">
        <img src="<?= base_url('images/' . (isset($jes[0]->logo) ? $jes[0]->logo : 'default_logo.png')) ?>" alt="Logo" style="width: 100px; height: auto;" class="img-profile rounded-circle">
        <div style="font-size: 20px; color: #333; font-weight: bold;">
          <?= isset($jes[0]->nama_toko) ? $jes[0]->nama_toko : 'Default Name' ?>
        </div>

      </a>
      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="http://localhost:8080/home/beranda">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
        </li>

        <?php
        if(session()->get('level')==2 || session()->get('level') == 1){
          ?>
          <hr class="sidebar-divider">
          
          <!-- Heading -->
          <div class="sidebar-heading">
            Interface
          </div>

          <!-- bagian user dan manager(crud) -->
          
          <li class="nav-item">
            <a class="nav-link" href="http://localhost:8080/home/Keranjang">
              <i class="fas fa-fw fa-book"></i>
              <span>Menu</span></a>
            </li>
          <?php } ?>






          <!-- bagian admin -->
          <?php
          if(session()->get('level')==2){
            ?>
            <li class="nav-item">
              <a class="nav-link" href="http://localhost:8080/home/user">
                <i class="fas fa-fw fa-users"></i>
                <span>Data Users</span>
              </a>
            </li>
          <?php } ?>


          <!-- Divider -->
          <hr class="sidebar-divider">

          <!-- Heading -->

          <?php
          if(session()->get('level')==2 ){
            ?>

            <li class="nav-item">
              <a class="nav-link " href="http://localhost:8080/home/setting" >
                <i class="fas fa-fw fa-cogs"></i>
                <span>Setting</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="http://localhost:8080/home/laporan" >
                <i class="fas fa-fw fa-file-alt"></i>
                <span>Laporan</span>
              </a>
            </li>
          <?php } ?>
          <?php
          if(session()->get('level')==2 || session()->get('level') == 1){
            ?>
            <li class="nav-item">
              <a class="nav-link " href="http://localhost:8080/home/history" >
                <i class="fas fa-fw fa-receipt"></i>
                <span>History</span>
              </a>
            </li>
          <?php } ?>
          <?php
          if(session()->get('level')==2 || session()->get('level') == 3){
            ?>
            <li class="nav-item">
              <a class="nav-link " href="http://localhost:8080/home/pesanan" >
                <i class="fas fa-fw fa-receipt"></i>
                <span>Pesanan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="http://localhost:8080/home/buktinota" >
                <i class="fas fa-fw fa-receipt"></i>
                <span>Bukti nota</span>
              </a>
            </li>
          <?php } ?>
          <?php
          if(session()->get('level')==2){
            ?>
          <li class="nav-item">
            <a class="nav-link" href="http://localhost:8080/home/activity_log">
              <i class="fas fa-fw fa-sign-out-alt"></i>
              <span>Log Activity</span></a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="http://localhost:8080/home/restore">
              <i class="fas fa-fw fa-sign-out-alt"></i>
              <span>Restoring Data</span></a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="http://localhost:8080/home/history_edit">
              <i class="fas fa-fw fa-sign-out-alt"></i>
              <span>History Edit</span></a>
            </li>
             <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="http://localhost:8080/home/logout">
              <i class="fas fa-fw fa-sign-out-alt"></i>
              <span>Log out</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">




          </ul>
          <!-- End of Sidebar -->

          <!-- Content Wrapper -->
          <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

              <!-- Topbar -->
              <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">


                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                  <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                  <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-search fa-fw"></i>
                  </a>
                  <!-- Dropdown - Messages -->
                  <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                  aria-labelledby="searchDropdown">
                  <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                      <input type="text" class="form-control bg-light border-0 small"
                      placeholder="Search for..." aria-label="Search"
                      aria-describedby="basic-addon2">
                      <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                          <i class="fas fa-search fa-sm"></i>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </li>
            </ul>

          </nav>