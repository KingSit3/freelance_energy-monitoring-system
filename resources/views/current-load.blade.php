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

        {{-- DPM Card List --}}
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body row" id="dpm-contents">
              <div class="text-center col-lg-12">Data Belum tersedia</div>
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
                    <th colspan="3">DPM 1</th>
                    <th colspan="3">DPM 2</th>
                    <th colspan="3">DPM 3</th>
                    <th colspan="3">DPM 4</th>
                    <th colspan="3">DPM 5</th>
                    <th colspan="3">DPM 6</th>
                    <th colspan="3">DPM 7</th>
                    <th colspan="3">DPM 8</th>
                    <th colspan="3">DPM 9</th>
                    <th colspan="3">DPM 10</th>
                    <th colspan="3">DPM 11</th>
                    <th rowspan="2">Terminal Time</th>
                    <th rowspan="2">Asia/Jakarta Time</th>
                  </tr>
                  <tr>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
                    <th>I1</th>
                    <th>I2</th>
                    <th>I3</th>
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

  // Cards
  function getCurrentLoad(){
    $.ajax({
      url: "{{ route('latest_current_load') }}",
      success: function(result){
        if (result.dpm_list.length > 0) {
          $("#dpm-contents").html("") // Reset Element
          for (let dpmIndex = result.dpm_list.length - 1; dpmIndex >= 0; dpmIndex--) {
  
            // Create DPM Group Elements
            $("#dpm-contents").prepend(
              `
                <div class="col-lg-3 mb-5">
                  <p class="text-bold text-lg">DPM ${dpmIndex + 1} </p> 
                  <ul class="list-group" id="dpm-${dpmIndex}"></ul>
                </div>
              `
            );
            
            
            // Create Current Load List Elements
            const currentLoads = Object.values(result.dpm_list[dpmIndex]) // Object into Arrays
            for (let index = currentLoads.length - 1; index >= 0; index--) {
              $(`#dpm-${dpmIndex}`).prepend(
              `<li class="row">
                <em class="list-group-item list-group-item-primary" style="width: 25%; font-family: 'Times New Roman', Times, serif">I ${index + 1} </em> 
                <span class="list-group-item w-full" style="width: 75%">
                  ${currentLoads[index]}
                </span>
              </li>`
              )
            }
          }
        }
      }
    })
  }
  getCurrentLoad()

  // Datatable
  var datatableElement = $("#datatable").DataTable({
    processing: true,
    serverSide: true,
    info: false,
    lengthChange: false,
    autoWidth: true,
    scrollX: true,
    filter: false,
    ajax: {
        url: "{{ route('datatable.one_current_load') }}",
    },
    columns: [
        { data: '01I1', name: '01I1'},
        { data: '01I2', name: '01I2'},
        { data: '01I3', name: '01I3'},
        { data: '02I1', name: '02I1'},
        { data: '02I2', name: '02I2'},
        { data: '02I3', name: '02I3'},
        { data: '03I1', name: '03I1'},
        { data: '03I2', name: '03I2'},
        { data: '03I3', name: '03I3'},
        { data: '04I1', name: '04I1'},
        { data: '04I2', name: '04I2'},
        { data: '04I3', name: '04I3'},
        { data: '05I1', name: '05I1'},
        { data: '05I2', name: '05I2'},
        { data: '05I3', name: '05I3'},
        { data: '06I1', name: '06I1'},
        { data: '06I2', name: '06I2'},
        { data: '06I3', name: '06I3'},
        { data: '07I1', name: '07I1'},
        { data: '07I2', name: '07I2'},
        { data: '07I3', name: '07I3'},
        { data: '08I1', name: '08I1'},
        { data: '08I2', name: '08I2'},
        { data: '08I3', name: '08I3'},
        { data: '09I1', name: '09I1'},
        { data: '09I2', name: '09I2'},
        { data: '09I3', name: '09I3'},
        { data: '10I1', name: '10I1'},
        { data: '10I2', name: '10I2'},
        { data: '10I3', name: '10I3'},
        { data: '11I1', name: '11I1'},
        { data: '11I2', name: '11I2'},
        { data: '11I3', name: '11I3'},
        { data: 'terminal_time', name: 'terminal_time'},
        { data: 'created_at', name: 'created_at'},
    ],
    order: [[34, 'desc']]
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
    getCurrentLoad()
    datatableElement.ajax.reload(false, false)
  }, 60000) // Refresh date after 60 sec

</script>
@endsection
