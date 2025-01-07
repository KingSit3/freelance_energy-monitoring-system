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

        {{-- DPM List --}}
        <div class="col-lg-12">

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

          <div class="card">
            <div class="card-body row" id="dpm-contents">
              <div class="text-center col-lg-12">Data Belum tersedia</div>
            </div>
          </div>
        </div>
        {{-- End DPM List --}}

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
  }, 5000) // Refresh date after 5 sec

</script>
@endsection
