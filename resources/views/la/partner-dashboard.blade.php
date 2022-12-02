@extends('la.layouts.app')

@section('htmlheader_title') Partner Dashboard @endsection
@section('contentheader_title') Partner Dashboard @endsection
@section('contentheader_description') Organisation Overview @endsection

@section('main-content')
<style>
.todo-list > li .text {
  display: inline !important;
}
</style>
<!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            @include("flash.message")
          </div>
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua total-leads-today cursor-pointer">
                <div class="inner">
                  <h3>@if(isset($data['aRowTotalTodayProjects'])) {{$data['aRowTotalTodayProjects']}} @endif</h3>
                  <p>Total Leads Today</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green @if(!Auth::user()->canAccess()) show-coming-soon-popup @else total-out-message cursor-pointer @endif" data-box="Total Out Messages Today">
                <div class="inner">
                  @if(Auth::user()->canAccess())
                  <h3>@if(isset($data['aRowTotalTodayOutMessage'])) {{$data['aRowTotalTodayOutMessage']}} @endif</h3>
                  @else
                  <h3><i class="fa fa-lock"></i></h3>
                  @endif
                  <p>Total Out Messages Today</p>
                </div>
                <div class="icon">
                  <i class="fa fa-envelope"></i>
                </div>
                <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow @if(!Auth::user()->canAccess()) show-coming-soon-popup @else total-reply-message cursor-pointer @endif" data-box="Total Replies Today">
                <div class="inner">
                  @if(Auth::user()->canAccess())
                  <h3>@if(isset($data['aRowTotalTodayRepliesMessage'])) {{$data['aRowTotalTodayRepliesMessage']}} @endif</h3>
                  @else
                  <h3><i class="fa fa-lock"></i></h3>
                  @endif
                  <p>Total Replies Today</p>
                </div>
                <div class="icon">
                  <i class="fa fa-envelope"></i>
                </div>
                <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red cursor-pointer" onclick="window.location.href = '{{ url(config('laraadmin.adminRoute') . '/projects') }}';">
                <div class="inner">
                  <h3>@if(isset($data['aRowTotalProjects'])) {{$data['aRowTotalProjects']}} @endif</h3>
                  <p>Total Leads</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{url(config('laraadmin.adminRoute').'/projects')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-5 connectedSortable col-lg-push-7">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">What is a Partner Lead?</h3>
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                      A lead which is added by partners like you.
                    </div>
                  </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">What is a community lead?</h3>
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                      A lead which is added by the admins, which they got via ads or facebook or whatsapp groups or linkedin or any other source.
                    </div>
                  </div>
                </div>
              </div>
            </section><!-- right col -->
            <section class="col-lg-7 connectedSortable col-lg-pull-5">
              <!-- Custom tabs (Charts with tabs)-->
               <!-- TO DO List -->
              <div class="box box-primary">
                <div class="box-header">
                  <i class="ion ion-clipboard"></i>
                  <h3 class="box-title">Recent Leads</h3>
                  <div class="box-tools pull-right">
                    <ul class="pagination pagination-sm inline">
                      <li><a href="#">&laquo;</a></li>
                      <li><a href="#">1</a></li>
                      <li><a href="#">&raquo;</a></li>
                    </ul>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <ul class="todo-list">
                  @foreach( $data['aRowTotalProjectsrecents'] as $col )
                   <li class="cursor-pointer" onclick="window.location.href = '{{ url(config('laraadmin.adminRoute') . '/projects/'.$col->id) }}';">
                      <!-- drag handle -->
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                       <small class="label label-success">   {{$col->id}} </small>
                      <span class="text">{{$col->name}}</span>
                      <!-- Emphasis label -->
                      <small class="label label-danger"><i class="fa fa-clock-o"></i> {{$col->is_hourly}} </small>
                      <!-- General tools such as edit or delete categories-->
                      <!-- <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div> -->
                      <!-- <small class="label label-default"> {{$col->channel}} </small> -->
                       {{$col->categories}}
                    </li> 
                @endforeach
                  </ul>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix no-border">
                 <center> 
                  <a href="{{ url(config('laraadmin.adminRoute') . '/projects') }}">
                    <button class="btn btn-default"> See all</button>
                  </a>
                </center></div>
              </div><!-- /.box -->

            </section><!-- /.Left col -->
          </div><!-- /.row (main row) -->

        </section><!-- /.content -->
<div class="modal fade in" id="show-coming-soon-popup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Become a Premium Partner <svg width="20" fill="#48B0F7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M64 192c27.25 0 51.75-11.5 69.25-29.75c15 54 64 93.75 122.8 93.75c70.75 0 127.1-57.25 127.1-128s-57.25-128-127.1-128c-50.38 0-93.63 29.38-114.5 71.75C124.1 47.75 96 32 64 32c0 33.37 17.12 62.75 43.13 80C81.13 129.3 64 158.6 64 192zM208 96h95.1C321.7 96 336 110.3 336 128h-160C176 110.3 190.3 96 208 96zM337.8 306.9L256 416L174.2 306.9C93.36 321.6 32 392.2 32 477.3c0 19.14 15.52 34.67 34.66 34.67H445.3c19.14 0 34.66-15.52 34.66-34.67C480 392.2 418.6 321.6 337.8 306.9z"/></svg></h4>
      </div>
      <div class="modal-body">
        <p class="text-center">This feature is available for Premium Partners only. To Access All Features, Go Premium. Contact support for more information.</p>
        <div class="text-center">
          <button type="button" class="btn btn-primary btn-interested"> <i class="fa fa-hand-o-right"></i>  INTERESTED!  <i class="fa fa-hand-o-left"></i> </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<!-- Morris chart -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/morris/morris.css') }}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/daterangepicker/daterangepicker-bs3.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
<style>
.show-coming-soon-popup, .cursor-pointer{
  cursor: pointer;
}
</style>
@endpush


@push('scripts')
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset('la-assets/plugins/morris/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('la-assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('la-assets/plugins/knob/jquery.knob.js') }}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ asset('la-assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('la-assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('la-assets/plugins/fastclick/fastclick.js') }}"></script>
<!-- dashboard -->
<script src="{{ asset('la-assets/js/pages/dashboard.js') }}"></script>
@endpush

@push('scripts')
  <script type="text/javascript">
    $("div .total-leads-today").click(function (){
      var redirectUrl = '{{ url(config('laraadmin.adminRoute') . '/projects?today=1') }}';
      //var filterDate = $('#date_filter').val();
      var dateValue = '{{ date('d-m-Y') }}'; //filterDate.split('/').join('-');
      window.location.href = redirectUrl+"&date="+dateValue;
    });
    $("div .total-out-message").click(function (){
      var redirectUrl = '{{ url(config('laraadmin.adminRoute') . '/projects?today=2') }}';
      //var filterDate = $('#date_filter').val();
      var dateValue = '{{ date('d-m-Y') }}'; //filterDate.split('/').join('-');
      window.location.href = redirectUrl+"&date="+dateValue;
    });
    $("div .total-reply-message").click(function (){
      var redirectUrl = '{{ url(config('laraadmin.adminRoute') . '/projects?today=3') }}';
      //var filterDate = $('#date_filter').val();
      var dateValue = '{{ date('d-m-Y') }}'; //filterDate.split('/').join('-');
      window.location.href = redirectUrl+"&date="+dateValue;
    });
    $(".show-coming-soon-popup").click(function () {
      //$('.data-box-type').text($(this).attr('data-box'));
      $('#disable-feature-popup').modal('show');
    });
    $(".btn-interested").click(function () {
      var featureName = $('.data-box-type').text();
      $.ajax({
        url: "{{ url(config('laraadmin.adminRoute') . '/send-interested-email') }}",
        type: "post",
        data: { featureName: featureName, _token : $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
          if(response.status){
            location.reload();
          }
        }
      });
      $('#show-coming-soon-popup').modal('hide');
    });
  </script>
@endpush