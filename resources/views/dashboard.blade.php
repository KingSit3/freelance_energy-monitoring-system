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

  var sensorColors = @json($sensor_colors)

  var maxPowerChartElement = $('#max-power-chart')
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

  // Get Active Power Data
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
  setInterval(() => getActivePowerData(), 5000) // Refresh date after 5 sec
  // End Get Active Power Data
  
</script>
@endsection
