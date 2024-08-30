<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Energy Monitoring System</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ url('vendor/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- IonIcons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ url('vendor/dist/css/adminlte.min.css') }}">

    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
          <!-- Navbar -->
          <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
              </li>
            </ul>
          </nav>
          <!-- /.navbar -->
        
          <!-- Main Sidebar Container -->
          <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
              <span class="brand-text font-weight-light">Energy Monitoring System</span>
            </a>
        
            <!-- Sidebar -->
            <div class="sidebar">
              <!-- Sidebar Menu -->
              <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                  <li class="nav-item">
                    <a href="pages/widgets.html" class="nav-link">
                      <i class="nav-icon fas fa-home"></i>
                      <p>
                        Dashboard
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-bolt"></i>
                      <p>
                        Max Voltage
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="pages/layout/top-nav.html" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Voltage 1</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Voltage 2</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </nav>
              <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
          </aside>
        
          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                  </div><!-- /.col -->
                </div><!-- /.row -->
              </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
        
            <!-- Main content -->
            <div class="content">
              <div class="container-fluid">
                <div class="row align-middle">
                {{-- Total Voltage Chart --}}
                <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex">
                          <p class="d-flex flex-column">
                              <span>Total Voltage</span>
                              <span class="text-bold text-lg">820 kW</span>
                          </p>
                        </div>
                        <!-- /.d-flex -->
        
                        <div class="position-relative mb-4">
                          <canvas id="visitors-chart" height="300"></canvas>
                        </div>
        
                        <div class="d-flex flex-row justify-content-end">
                          <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> This Week
                          </span>
        
                          <span>
                            <i class="fas fa-square text-gray"></i> Last Week
                          </span>
                        </div>
                      </div>
                    </div>
                </div>
                {{-- End Total Voltage Chart --}}
                  <!-- /.col-md-6 -->
                  @foreach ([1,2,3,4,5,6,7,8,9,10,11,12,13] as $item)
                  <div class="col-lg-2">
                    <div class="card">
                        <div class="card-body">
                          <h1 class="text-lg text-center">Voltage {{ $item }}</h1>
                          <p class="card-text text-bold text-lg text-center">{{ str_pad($item, 10)  }} kW</p>
                        </div>
                    </div>
                </div>
                    @endforeach
                  <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
          </div>
          <!-- /.content-wrapper -->
        
          <!-- Main Footer -->
          <footer class="main-footer">
            <strong>Energy Monitoring System</strong>
            <div class="float-right d-none d-sm-inline-block">
              <b>Version</b> 1.0
            </div>
          </footer>
        </div>
        <!-- ./wrapper -->
        
        <!-- REQUIRED SCRIPTS -->
        
        <!-- jQuery -->
        <script src="{{ url('vendor/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ url('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE -->
        <script src="{{ url('vendor/dist/js/adminlte.js') }}"></script>

        <!-- OPTIONAL SCRIPTS -->
        <script src="{{ url('vendor/plugins/chart.js/Chart.min.js') }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ url('vendor/dist/js/demo.js') }}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{ url('vendor/dist/js/pages/dashboard3.js') }}"></script>
        </body>
</html>
