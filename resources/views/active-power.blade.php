@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-capitalize">Active Power &bull; {{ $title }}</h1>
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
                  <span id="total_active_power" class="text-bold text-lg">{{ $limited_active_power?->last()?->active_power ? $limited_active_power->last()->active_power . " kW" : "- kW" }}</span>
                </p>
              </div>
              <!-- /.d-flex -->

              <div class="position-relative mb-4">
                <canvas id="max-power-chart" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        {{-- End Total Voltage Chart --}}

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
                    <th>Active Power</th>
                    <th>Terminal Time</th>
                    <th>Asia/Jakarta Time</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        {{-- End Table --}}

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

  // Datatable
  var datatableElement = $("#datatable").DataTable({
    processing: true,
    serverSide: true,
    info: false,
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    filter: false,
    ajax: {
        url: "{{ route('datatable.one_active_power', $sensor_id) }}",
        data: {
        },
    },
    columns: [
        { data: 'active_power', name: 'active_power'},
        { data: 'terminal_time', name: 'terminal_time'},
        { data: 'created_at', name: 'created_at'},
    ],
    order: [[2, 'desc']]
  })
  // End Datatable

  // Chart
  var maxPowerChartElement = $('#max-power-chart')
  var maxPowerChart = new Chart(maxPowerChartElement, {
    type: 'line',
    data: {
      labels: @json($chart_labels),
      datasets: [{
        data: @json($chart_data),
        borderColor: 'transparent',
        pointBorderColor: 'transparent',
        pointBackgroundColor: 'transparent',
        backgroundColor: "#007bff",
        pointHoverBackgroundColor: 'transparent',
        pointHoverBorderColor    : 'transparent',
        fill: true
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
    },
    options: {
      legend: {
        display: false
      },
      elements: {
        line: {
          tension: 0.5,
        },
      },
      radius: 1000,
      maintainAspectRatio: false,
      tooltips: {
        enabled: false
      },
      scales: {
        x: {
          title: {
            display: false,
            text: 'Month'
          }
        },
        y: {
          stacked: true,
          title: {
            display: true,
            text: 'Value'
          }
        }
      },
    }
  })
  function getOneActivePowerData(){
    $.ajax({
      url: `{{ route('one_active_power', request('id')) }}` ,
      success: function(result){

        // Manipulate Chart Data
        maxPowerChart.data.datasets[0].data.shift()
        maxPowerChart.data.datasets[0].data.push(result.active_power.active_power)
        // End Manipulate Chart Data
        
        // Manipulate Chart Label
        maxPowerChart.data.labels.shift()
        maxPowerChart.data.labels.push(result.chart_labels)
        // End Manipulate Chart

        // Update Chart
        maxPowerChart.update()

        // $("#datatable_body").find('tr:last').remove();

        // // datatableElement.row.add([data[0]]))
        // $("#datatable_body").prepend(
        //   `
        //     <tr>
        //       <td>${result.active_power.active_power}</td>
        //       <td>${result.active_power.terminal_time}</td>
        //       <td>${result.active_power.created_at}</td>
        //     </tr>
        //   `
        // );

        $('#total_active_power').html(result.active_power.active_power ? `${result.active_power.active_power} kW` : "- kW" ) // Update Active Power
      }
    })
  }
  // End Chart

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
            id: {{ "$sensor_id" }},
            start_date: startDate,
            end_date: endDate
        })
  }

  setInterval(() => {
    getOneActivePowerData()
    datatableElement.ajax.reload(false, false)
  }, 5000) // Refresh date after 5 sec

</script>
@endsection
