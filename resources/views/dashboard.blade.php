@extends('layouts.app')

@section('content')
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
                    <span>Total Active Power</span>
                    <span id="total_active_power" class="text-bold text-lg">{{ isset($active_powers[0]["total_active_power"]) ? $active_powers[0]["total_active_power"] . " kW" : "- kW" }}</span>
                </p>
              </div>
              <!-- /.d-flex -->

              <div class="position-relative mb-4">
                <canvas id="max-power-chart" height="600"></canvas>
              </div>
            </div>
          </div>
        </div>
        {{-- End Total Voltage Chart --}}

        {{-- Active Power Card Container --}}
        <div id="active_power_card_container" class="col-lg-12 row">
          @if (isset($active_powers[0]))
            @foreach ($active_powers[0]["data"] as $activePower)
            <div class="col-lg-2">
              <a href="{{ route('show.active.power', $loop->iteration) }}">
                <div class="card">
                    <div class="card-body">
                      <h1 class="text-lg text-center text-bold" style="color: {{ $sensor_colors[$loop->index] }} ">Sensor {{ $loop->iteration }}</h1>
                      <p class="card-text text-bold text-lg text-center" id="active_power_card_value_{{ $loop->index }}">{{ $activePower ? $activePower . " kW" : "-" }} </p>
                    </div>
                </div>
              </a>
            </div>
            @endforeach
          @endif
        </div>
        {{-- End Active Power Card Container --}}

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

  // Chart
  var sensorColors = @json($sensor_colors);
  var maxPowerChartElement = $('#max-power-chart');
  var maxPowerChart = new Chart(maxPowerChartElement, {
    type: 'line',
    data: {
      labels: @json($chart_labels),
      datasets: generateMaxPowerChartData(@json($active_powers))
    },
    options: {
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
  function generateMaxPowerChartData(maxPowerData){
    let result = []

    for (let maxPowerIndex = 0; maxPowerIndex < maxPowerData[0]["data"].length; maxPowerIndex++) {
      let chartData = []
      for (let dataIndex = 0; dataIndex < maxPowerData.length; dataIndex++) {
        chartData.push(maxPowerData[dataIndex]["data"][maxPowerIndex])
      }
      result.push(
        {
          data: chartData,
          label: `Sensor ${maxPowerIndex + 1}`,
          borderColor: 'transparent',
          pointBorderColor: 'transparent',
          pointBackgroundColor: 'transparent',
          backgroundColor: sensorColors[maxPowerIndex],
          pointHoverBackgroundColor: 'transparent',
          pointHoverBorderColor    : 'transparent',
          fill: true,
        }
      )
    }

    return result
  }
  function getActivePowerData(){
    $.ajax({
      url: "{{ route('active_power') }}" ,
      success: function(result){

        result.active_power.data.forEach((activePower, index) => {
          
          // Manipulate Active Power Cards
          $(`#active_power_card_value_${index}`).html(activePower ? activePower + " kW" : '-' )
          // End Manipulate Active Power Cards

          // Manipulate Chart Data
          maxPowerChart.data.datasets[index].data.shift()
          maxPowerChart.data.datasets[index].data.push(activePower)
          // End Manipulate Chart Data
        });
        
        // Manipulate Chart Label
        maxPowerChart.data.labels.shift()
        maxPowerChart.data.labels.push(result.chart_labels)
        // End Manipulate Chart

        // Update Chart
        maxPowerChart.update()

        $('#total_active_power').html(result.active_power.total_active_power ? `${result.active_power.total_active_power} kW` : "- kW" ) // Update Total
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
            start_date: startDate,
            end_date: endDate
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
        { data: 'active_power_1', name: 'active_power_1'},
        { data: 'active_power_2', name: 'active_power_2'},
        { data: 'active_power_3', name: 'active_power_3'},
        { data: 'active_power_4', name: 'active_power_4'},
        { data: 'active_power_5', name: 'active_power_5'},
        { data: 'active_power_6', name: 'active_power_6'},
        { data: 'active_power_7', name: 'active_power_7'},
        { data: 'active_power_8', name: 'active_power_8'},
        { data: 'active_power_9', name: 'active_power_9'},
        { data: 'active_power_10', name: 'active_power_10'},
        { data: 'active_power_11', name: 'active_power_11'},
        { data: 'terminal_time', name: 'terminal_time'},
        { data: 'created_at', name: 'created_at'},
    ],
    order: [[12, 'desc']]
  })
  // End Datatable

  setInterval(() => {
    getActivePowerData()
    datatableElement.ajax.reload(false, false)
  }, 60000) // Refresh date after 60 sec
  
</script>
@endsection
