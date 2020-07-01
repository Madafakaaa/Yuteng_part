@extends('main')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">课程安排点名</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">教学中心</li>
              <li class="breadcrumb-item active">课程安排点名</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/operation/schedule/attend/{{ $schedule->schedule_id }}/store" method="post" enctype="multipart/form-data">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">点名信息确认</h3>
          </div>
          <!-- Card body -->
          <div class="card-body py-2">
            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">学生 / 班级</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <label>{{ $schedule->student_name }}{{ $schedule->class_name }}</label>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">校区</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <label>{{ $schedule->department_name }}</label>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">日期</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <label>{{ $schedule_date }}，&nbsp;{{ dateToDay($schedule_date) }}</label>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">时间</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <label>{{ date('H:i', strtotime($schedule_start)) }} - {{ date('H:i', strtotime($schedule_end)) }}</label>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">教师</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <label>{{ $schedule_teacher_name }}</label>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">科目</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <label>{{ $schedule_subject_name }}</label>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">教室</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <div class="form-group mb-1">
                  <label>{{ $schedule_classroom_name }}</label>
                </div>
              </div>
            </div>
            @foreach ($student_courses as $student_course)
              <input type="hidden" name="input{{ $loop->iteration }}_0" value="{{ $student_course[0] }}">
              <input type="hidden" name="input{{ $loop->iteration }}_1" value="{{ $student_course[1] }}">
              <input type="hidden" name="input{{ $loop->iteration }}_2" value="{{ $student_course[2] }}">
              <input type="hidden" name="input{{ $loop->iteration }}_3" value="{{ $student_course[3] }}">
              <hr>
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                  <label class="form-control-label">上课学生{{ $loop->iteration }}</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                  <label>{{ $student_course[4] }}</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                  <label class="form-control-label">考勤状态</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                  @if($student_course[1]==0)
                    <label>请假</label>
                  @elseif($student_course[1]==1)
                    <label>正常</label>
                  @else
                    <label>旷课</label>
                  @endif
                </div>
              </div>
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                  <label class="form-control-label">扣除课程</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                  <label>{{ $student_course[5] }}</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                  <label class="form-control-label">扣除课时</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                  <label>{{ $student_course[3] }} 课时</label>
                </div>
              </div>
            @endforeach
            <hr>
            <div class="row">
              <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">上一步</button></a>
              </div>
              <div class="col-lg-6 col-md-4 col-sm-12 mb-1"></div>
              <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                <input type="hidden" name="input_date" value="{{ $schedule_date }}">
                <input type="hidden" name="input_start" value="{{ $schedule_start }}">
                <input type="hidden" name="input_end" value="{{ $schedule_end }}">
                <input type="hidden" name="input_teacher" value="{{ $schedule_teacher }}">
                <input type="hidden" name="input_subject" value="{{ $schedule_subject }}">
                <input type="hidden" name="input_classroom" value="{{ $schedule_classroom }}">
                <input type="hidden" name="input_student_num" value="{{ count($student_courses) }}">
                <input type="submit" class="btn btn-warning btn-block" value="确认">
              </div>
            </div>
          </div>
        <form>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <button type="button" class="btn btn-success btn-icon-only rounded-circle">
            <span class="btn-inner--icon">1</span>
          </button>
        </div>
        <div class="col-2 text-center">
          <button type="button" class="btn btn-success btn-icon-only rounded-circle">
            <span class="btn-inner--icon">2</span>
          </button>
        </div>
        <div class="col-2 text-center">
          <button type="button" class="btn btn-primary btn-icon-only rounded-circle">
            <span class="btn-inner--icon">3</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationScheduleMy');
</script>
@endsection
