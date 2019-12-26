@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="/schedule">课程安排</a></li>
    <li class="breadcrumb-item active">新建排课</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/schedule" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">三、确认课程信息</h3>
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
                      <input class="form-control form-control-sm" value="{{ Session::get('user_department_name') }}" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <label class="form-control-label">上课日期(<span style="color:red;">{{ count($schedule_dates) }}</span>天)</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    @foreach ($schedule_dates as $schedule_date)
                      <div class="col-3 pl-2 pr-2 mb-2">
                        <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_date }}">
                      </div>
                    @endforeach
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
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_start }} - {{ $schedule_end }}">
                    </div>
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_time }}分钟">
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
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_participant_name }}">
                    </div>
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_grade_name }}">
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
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_teacher_name }}">
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
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_subject_name }}">
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
                      <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_classroom_name }}">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">上一步</button></a>
              </div>
              <div class="col-6"></div>
              <div class="col-3">
                <input type="hidden" name="input1" value="{{ $schedule_participant }}">
                <input type="hidden" name="input2" value="{{ $schedule_teacher }}">
                <input type="hidden" name="input3" value="{{ $schedule_classroom }}">
                <input type="hidden" name="input4" value="{{ $schedule_subject }}">
                <input type="hidden" name="input5" value="{{ $schedule_dates_str }}">
                <input type="hidden" name="input6" value="{{ $schedule_start }}">
                <input type="hidden" name="input7" value="{{ $schedule_end }}">
                <input type="hidden" name="input8" value="{{ $schedule_time }}">
                <input type="hidden" name="input9" value="{{ $schedule_grade }}">
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
