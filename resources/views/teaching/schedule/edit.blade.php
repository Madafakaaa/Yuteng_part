@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="/schedule">课程安排</a></li>
    <li class="breadcrumb-item active">课程考勤</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/schedule" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">课程考勤</h3>
          </div>
          <!-- Card body -->
          <div class="card-body pt-2">
            <div class="row">
              <div class="col-2">
                <label class="form-control-label">上课校区</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" value="{{ $schedule->department_name }}" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <label class="form-control-label">上课日期</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" value="{{ $schedule->schedule_date }}" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <label class="form-control-label">上课时间</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" value="{{ $schedule->schedule_start }} - {{ $schedule->schedule_end }}" readonly>
                    </div>
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" value="{{ $schedule->schedule_time }}分钟" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <label class="form-control-label">学生/班级</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule->student_name }}{{ $schedule->class_name }}">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <label class="form-control-label">任课教师</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule->user_name }}">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <label class="form-control-label">科目</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule->subject_name }}">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <label class="form-control-label">教室</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule->classroom_name }}">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-9"></div>
              <div class="col-3">
                <input type="submit" class="btn btn-warning btn-block" value="确认">
              </div>
            </div>
          </div>
        <form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-3');
  navbarActive('navbar-3');
  linkActive('schedule');
</script>
@endsection
