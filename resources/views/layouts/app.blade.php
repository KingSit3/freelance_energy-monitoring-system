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
                    <a href="{{ route('dashboard') }}" class="nav-link">
                      <i class="nav-icon fas fa-home"></i>
                      <p>
                        Dashboard
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="" class="nav-link">
                      <i class="nav-icon fas fa-bolt"></i>
                      <p>
                        Active Power
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      @for ($i = 1; $i <= 13; $i++)
                      <li class="nav-item">
                        <a href="{{ route('show.active.power', $i) }}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Active Power {{ $i }}</p>
                        </a>
                      </li>
                      @endfor
                    </ul>
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

        {{-- Custom Script --}}
        @yield('bottom-script')

        </body>
</html>
