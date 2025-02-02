<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Energy Monitoring System
          @isset ($title)
            &bull; {{ Str::ucfirst($title) }}
          @endisset
        </title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <link rel="stylesheet" href="{{ url('vendor/plugins/fontawesome-free/css/all.min.css') }}">
        {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
        <link rel="stylesheet" href="{{ url('vendor/dist/css/adminlte.min.css') }}">
        
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ url('vendor/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ url('vendor/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ url('vendor/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

        <!-- daterange picker -->
        <link rel="stylesheet" href="{{ url('vendor/plugins/daterangepicker/daterangepicker.css') }}">

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
                    <a href="{{ route('dashboard') }}" class="nav-link {{ url()->current() == route('dashboard') ? 'active' : ''  }}">
                      <i class="nav-icon fas fa-home"></i>
                      <p>
                        Dashboard
                      </p>
                    </a>
                  </li>
                  <li class="nav-item {{ str_contains(request()->path(), 'max-power') ? 'menu-open' : '' }}">
                    <a href="" class="nav-link">
                      <i class="nav-icon fas fa-bolt"></i>
                      <p>
                        Data kWH
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      @for ($i = 1; $i <= 11; $i++)
                      <li class="nav-item">
                        <a href="{{ route('show.max_power', $i) }}" class="nav-link {{ url()->current() == route('show.max_power', $i) ? 'active' : ''  }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>DPM {{ $i }} - kWH</p>
                        </a>
                      </li>
                      @endfor
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('current_load') }}" class="nav-link {{ url()->current() == route('current_load') ? 'active' : ''  }}">
                      <i class="nav-icon fas fa-info"></i>
                      <p>
                        Current Load
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('active_power') }}" class="nav-link {{ url()->current() == route('active_power') ? 'active' : ''  }}">
                      <i class="nav-icon fas fa-info"></i>
                      <p>
                        Active Power
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('other_power') }}" class="nav-link {{ url()->current() == route('other_power') ? 'active' : ''  }}">
                      <i class="nav-icon fas fa-info"></i>
                      <p>
                        Other Power
                      </p>
                    </a>
                  </li>
                </ul>
              </nav>
              <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
          </aside>
          
          {{-- Main Content --}}
          @yield('content')
          {{-- End Main Content --}}
        
          <!-- Main Footer -->
          <footer class="main-footer">
            <strong>Energy Monitoring System</strong>
            <div class="float-right d-none d-sm-inline-block">
              <b>Version</b> 1.0
            </div>
          </footer>
        </div>
        <!-- ./wrapper -->
        
        <script src="{{ url('vendor/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ url('vendor/dist/js/adminlte.js') }}"></script>
        <script src="{{ url('vendor/plugins/chart.js/Chart.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/moment/moment.min.js') }}"></script>

        <!-- DataTables  & Plugins -->
        <script src="{{ url('vendor/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ url('vendor/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ url('vendor/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

        <!-- date-range-picker -->
        <script src="{{ url('vendor/plugins/daterangepicker/daterangepicker.js') }}"></script>

        {{-- Custom Script --}}
        @yield('bottom-script')

        </body>
</html>
