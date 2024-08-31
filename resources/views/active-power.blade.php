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
                  <span id="total_active_power" class="text-bold text-lg">{{ $limited_active_power->last()->active_power ? $limited_active_power->last()->active_power . " kW" : "- kW" }}</span>
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
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Active Power</th>
                    <th>Terminal Time</th>
                    <th>Asia/Jakarta Time</th>
                  </tr>
                </thead>

                <tbody id="datatable_body">
                  @foreach ($active_powers as $item)
                    <tr>
                      <td>{{ $item->active_power }}</td>
                      <td>{{ $item->terminal_time }}</td>
                      <td>{{ $item->created_at }}</td>
                    </tr>
                  @endforeach
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
    info: false,
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    // "buttons": ["excel", "pdf"]
  })
  // End Datatable

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var maxPowerChartElement = $('#max-power-chart')
  var maxPowerChart = new Chart(maxPowerChartElement, {
    data: {
      labels: @json($chart_labels),
      datasets: [{
        type: 'line',
        data: @json($chart_data),
        backgroundColor: 'transparent',
        borderColor: "#ef4444",
        pointBorderColor: "#ef4444",
        pointBackgroundColor: "#ef4444",
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
      
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: "index",
        intersect: true
      },
      hover: {
        mode: "index",
        intersect: true
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

  // Get Active Power Data
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
  setInterval(() => getOneActivePowerData(), 5000) // Refresh date after 5 sec
  // End Get Active Power Data
</script>
@endsection
