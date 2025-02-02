@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{ $title }}</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row align-middle">

        {{-- Active Power Cards --}}
        <div id="total_active_power" class="col-lg-12 row">
          @if (count($data) > 1)
            @foreach ($data as $activePower)
            <div class="col-lg-2">
              <div class="card">
                  <div class="card-body">
                    <h1 class="text-lg text-center text-bold" style="color: {{ $sensor_colors[$loop->index] }} ">Sensor {{ $loop->iteration }}</h1>
                    <p class="card-text text-bold text-lg text-center" id="active_power_card_value_{{ $loop->index }}">{{ $activePower ? abs($activePower) . " kW" : "-" }} </p>
                  </div>
              </div>
            </div>
            @endforeach
          @endif
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
                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                  </div>
                  <input type="text" class="form-control float-right" id="daterange">
                </div>
              </div>
              <!-- End Date range -->
              
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    @for ($i = 1; $i < 12; $i++)
                      <th>Sensor {{ $i }}</th>
                    @endfor
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
  function getActivePowerData(){
    $.ajax({
      url: "{{ route('latest_active_power') }}" ,
      success: function(result){

        console.log(result.data);
        result.data.forEach((activePower, index) => {
          
          $(`#active_power_card_value_${index}`).html(activePower ? activePower + " kW" : '-' )
        });
      }
    })
  }

  // Datatable
  var datatableElement = $("#datatable").DataTable({
    processing: true,
    serverSide: true,
    info: false,
    responsive: false,
    lengthChange: false,
    autoWidth: true,
    scrollX: true,
    filter: false,
    ajax: {
        url: "{{ route('datatable.active_power') }}",
    },
    columns: [
        { data: '01P_tot', name: 'active_power_1', defaultContent: 0},
        { data: '02P_tot', name: 'active_power_2', defaultContent: 0},
        { data: '03P_tot', name: 'active_power_3', defaultContent: 0},
        { data: '04P_tot', name: 'active_power_4', defaultContent: 0},
        { data: '05P_tot', name: 'active_power_5', defaultContent: 0},
        { data: '06P_tot', name: 'active_power_6', defaultContent: 0},
        { data: '07P_tot', name: 'active_power_7', defaultContent: 0},
        { data: '08P_tot', name: 'active_power_8', defaultContent: 0},
        { data: '09P_tot', name: 'active_power_9', defaultContent: 0},
        { data: '10P_tot', name: 'active_power_10', defaultContent: 0},
        { data: '11P_tot', name: 'active_power_11', defaultContent: 0},
        { data: 'terminal_time', name: 'terminal_time', defaultContent: "-"},
        { data: 'created_at', name: 'created_at'},
    ],
    order: [[12, 'desc']]
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

    return window.location = "{{ route('active_power.export') }}?" + $.param({
            start_date: startDate,
            end_date: endDate
        })
  }

  setInterval(() => {
    getActivePowerData()
    datatableElement.ajax.reload(false, false)
  }, 60000) // Refresh date after 60 sec
  
</script>
@endsection
