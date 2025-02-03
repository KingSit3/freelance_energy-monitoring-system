@extends('layouts.app')

@section('content')
<div class="content-wrapper">

  <!-- Title -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data KWH</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row align-middle">

        {{-- Max Power Total & Chart --}}
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex">
                <p class="d-flex flex-column">
                    <span>Total KWH</span>
                    <span id="total" class="text-bold text-lg">{{ isset($data[0]["total"]) ? $data[0]["total"] . " kW" : "- kW" }}</span>
                </p>
              </div>
              <div class="position-relative mb-4">
                <canvas id="max-power-chart" height="600"></canvas>
              </div>
            </div>
          </div>
        </div>

        {{-- Card Container --}}
        <div id="max_power_card_container" class="col-lg-12 row">
          @if (isset($data[0]))
            @foreach ($data[0]["data"] as $totalPower)
            <div class="col-lg-2">
              <a href="{{ route('show.max_power', $loop->iteration) }}">
                <div class="card">
                    <div class="card-body">
                      <h1 class="text-lg text-center text-bold" style="color: {{ $sensor_colors[$loop->index] }} ">Sensor {{ $loop->iteration }}</h1>
                      <p class="card-text text-bold text-lg text-center" id="max-power-card-value-{{ $loop->index }}">{{ $totalPower ? $totalPower . " kWH" : "-" }} </p>
                    </div>
                </div>
              </a>
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

  // Chart
  var sensorColors = @json($sensor_colors);
  var maxPowerChartElement = $('#max-power-chart');
  var maxPowerChart = new Chart(maxPowerChartElement, {
    type: 'bar',
    data: {
      labels: @json($chart_labels),
      datasets: generateMaxPowerChartData(@json($data))
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      scales: {
        xAxes: [{
          stacked: true,
          title: {
            display: true,
            text: 'Month'
          }
        }],
        yAxes: [{
          stacked: true,
          title: {
            display: true,
            text: 'Value'
          }
        }]
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
  function getMaxPowerData(){
    $.ajax({
      url: "{{ route('max_power') }}" ,
      success: function(result){

        result.max_power.data.forEach((maxPower, index) => {
          
          // Manipulate Cards
          $(`#max-power-card-value-${index}`).html(maxPower ? maxPower + " kW" : '-' )
          // End Manipulate Cards

          // Manipulate Chart Data
          maxPowerChart.data.datasets[index].data.shift()
          maxPowerChart.data.datasets[index].data.push(maxPower)
          // End Manipulate Chart Data
        });
        
        // Manipulate Chart Label
        maxPowerChart.data.labels.shift()
        maxPowerChart.data.labels.push(result.chart_labels)
        // End Manipulate Chart

        // Update Chart
        maxPowerChart.update()

        $('#total').html(result.max_power.total ? `${result.max_power.total} kW` : "- kW" ) // Update Total
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

    return window.location = "{{ route('max_power.export') }}?" + $.param({
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
    autoWidth: false,
    scrollX: true,
    filter: false,
    ajax: {
        url: "{{ route('datatable.max_power') }}",
    },
    columns: [
        { data: '01kWH', name: '01kWH', defaultContent: 0},
        { data: '02kWH', name: '02kWH', defaultContent: 0},
        { data: '03kWH', name: '03kWH', defaultContent: 0},
        { data: '04kWH', name: '04kWH', defaultContent: 0},
        { data: '05kWH', name: '05kWH', defaultContent: 0},
        { data: '06kWH', name: '06kWH', defaultContent: 0},
        { data: '07kWH', name: '07kWH', defaultContent: 0},
        { data: '08kWH', name: '08kWH', defaultContent: 0},
        { data: '09kWH', name: '09kWH', defaultContent: 0},
        { data: '10kWH', name: '10kWH', defaultContent: 0},
        { data: '11kWH', name: '11kWH', defaultContent: 0},
        { data: 'terminal_time', name: 'terminal_time', defaultContent: "-"},
        { data: 'created_at', name: 'created_at'},
    ],
    order: [[12, 'desc']]
  })
  // End Datatable

  setInterval(() => {
    getMaxPowerData()
    datatableElement.ajax.reload(false, false)
  }, 60000) // Refresh date after 60 sec
  
</script>
@endsection
