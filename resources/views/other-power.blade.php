@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-capitalize">{{ $title }}</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row align-middle">

        {{-- Cards --}}
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body row" id="dpm-contents">

              {{-- Volt 1P --}}
              <div class="col-lg-3 m-5">
                <p class="text-bold text-lg">Volt 1P</p> 
                <ul class="list-group">
                  <li class="row">
                    <span class="list-group-item list-group-item-primary" style="width: 25%; font-family: 'Times New Roman', Times, serif">1-N</span> 
                    <span id="01V1N" class="list-group-item w-full" style="width: 75%">
                      {{ $data["01V1N"] }}
                    </span>
                  </li>
                  <li class="row">
                    <span class="list-group-item list-group-item-primary" style="width: 25%; font-family: 'Times New Roman', Times, serif">2-N</span> 
                    <span id="01V2N" class="list-group-item w-full" style="width: 75%">
                      {{ $data["01V2N"] }}
                    </span>
                  </li>
                  <li class="row">
                    <span class="list-group-item list-group-item-primary" style="width: 25%; font-family: 'Times New Roman', Times, serif">3-N</span> 
                    <span id="01V3N" class="list-group-item w-full" style="width: 75%">
                      {{ $data["01V3N"] }}
                    </span>
                  </li>
                </ul>
              </div>

              {{-- Volt 3P --}}
              <div class="col-lg-3 m-5">
                <p class="text-bold text-lg">Volt 3P</p> 
                <ul class="list-group">
                  <li class="row">
                    <span class="list-group-item list-group-item-primary" style="width: 25%; font-family: 'Times New Roman', Times, serif">R-S</span> 
                    <span id="09V12" class="list-group-item w-full" style="width: 75%">
                      {{ $data["09V12"] }}
                    </span>
                  </li>
                  <li class="row">
                    <span class="list-group-item list-group-item-primary" style="width: 25%; font-family: 'Times New Roman', Times, serif">S-T</span> 
                    <span id="01V23" class="list-group-item w-full" style="width: 75%">
                      {{ $data["01V23"] }}
                    </span>
                  </li>
                  <li class="row">
                    <span class="list-group-item list-group-item-primary" style="width: 25%; font-family: 'Times New Roman', Times, serif">T-R</span> 
                    <span id="01V31" class="list-group-item w-full" style="width: 75%">
                      {{ $data["01V31"] }}
                    </span>
                  </li>
                </ul>
              </div>

              {{-- Power Factor & Frequency --}}
              <div class="col-lg-3 m-5">
                <p class="text-bold text-lg">Power Factor (PF)</p> 
                <ul class="list-group">
                  <li class="row">
                    <span id="01PF" class="list-group-item w-full" style="width: 75%">
                      {{ $data["01PF"] }}
                    </span>
                  </li>
                </ul>

                <p class="text-bold text-lg">Frequency (FREQ)</p> 
                <ul class="list-group">
                  <li class="row">
                    <span id="01FREQ" class="list-group-item w-full" style="width: 75%">
                      {{ $data["01FREQ"] }}
                    </span>
                  </li>
                </ul>
              </div>

            </div>
          </div>
        </div>

        {{-- Table --}}
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">

              <!-- Date range -->
              <div class="form-group row col-lg-12">

                <button onclick="exportData()" class="btn btn-primary col-lg-2" >Export</button>

                <div class="input-group col-lg-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-clock"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control float-right" id="daterange">
                </div>
              </div>
              <!-- End Date range -->
  
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>1-N</th>
                    <th>2-N</th>
                    <th>3-N</th>
                    <th>R-S</th>
                    <th>S-T</th>
                    <th>T-R</th>
                    <th>Power Factor</th>
                    <th>Frequency</th>
                    <th>Terminal Time</th>
                    <th>Asia/Jakarta Time</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->


    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('bottom-script')
<script>

  // Cards
  function getOtherPowers(){
    $.ajax({
      url: "{{ route('latest_other_power') }}",
      success: function(result){
        (result.data?.["01V1N"]) && $('#01V1N').html(result.data?.["01V1N"] ? result.data["01V1N"] : 0 );
        (result.data?.["01V2N"]) && $('#01V2N').html(result.data?.["01V2N"] ? result.data["01V2N"] : 0 );
        (result.data?.["01V3N"]) && $('#01V3N').html(result.data?.["01V3N"] ? result.data["01V3N"] : 0 );
        (result.data?.["09V12"]) && $('#09V12').html(result.data?.["09V12"] ? result.data["09V12"] : 0 );
        (result.data?.["01V23"]) && $('#01V23').html(result.data?.["01V23"] ? result.data["01V23"] : 0 );
        (result.data?.["01V31"]) && $('#01V31').html(result.data?.["01V31"] ? result.data["01V31"] : 0 );
        (result.data?.["01PF"]) && $('#01PF').html(result.data?.["01PF"] ? result.data["01PF"] : 0 );
        (result.data?.["01FREQ"]) && $('#01FREQ').html(result.data?.["01FREQ"] ? result.data["01FREQ"] : 0 );
      }
    })
  }
  getOtherPowers()

  // Datatable
  var datatableElement = $("#datatable").DataTable({
    processing: true,
    serverSide: true,
    info: false,
    // lengthChange: ,
    autoWidth: true,
    // responsive: false,
    // scrollX: true,
    filter: false,
    ajax: {
        url: "{{ route('datatable.other_power') }}",
    },
    columns: [
        { data: '01V1N', name: '01V1N'},
        { data: '01V2N', name: '01V2N'},
        { data: '01V3N', name: '01V3N'},
        { data: '09V12', name: '09V12'},
        { data: '01V23', name: '01V23'},
        { data: '01V31', name: '01V31'},
        { data: '01PF', name: '01PF'},
        { data: '01FREQ', name: '01FREQ'},
        { data: 'terminal_time', name: 'terminal_time'},
        { data: 'created_at', name: 'created_at'},
    ],
    order: [[9, 'desc']]
  })

  // Datetime
  $('#daterange').daterangepicker({
    maxDate: new Date(),
    minDate: moment().subtract(3, 'months').format('YYYY-MM-DD'),
    locale: {
      format: 'YYYY-MM-DD'
    }
  })

  function exportData() {
    const dateRange = $('#daterange').val().split(" - ");
    startDate = dateRange[0];
    endDate = dateRange[1];

    return window.location = "{{ route('current_load.export') }}?" + $.param({
            start_date: startDate,
            end_date: endDate
        })
  }

  setInterval(() => {
    getOtherPowers()
    datatableElement.ajax.reload(false, false)
  }, 6000) // Refresh date after 60 sec

</script>
@endsection
