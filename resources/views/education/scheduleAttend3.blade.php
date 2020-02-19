@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教学中心</li>
    <li class="breadcrumb-item active">课程考勤</li>
    <li class="breadcrumb-item active">确认考勤信息</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-success">修改上课信息</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #b0eed3;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-success">选择学生考勤</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #b0eed3;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-info">确认上课信息</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/education/schedule/attend/{{ $schedule->schedule_id }}/store" method="post" enctype="multipart/form-data">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">三、确认上课信息</h4>
          </div>
          <!-- Card body -->
          <div class="card-body pt-2">
            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">上课校区</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->department_name }}" readonly>
                </div>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">上课日期</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->schedule_date }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">上课时间</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}" readonly>
                </div>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">学生 / 班级</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule->student_name }}{{ $schedule->class_name }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">任课教师</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_teacher_name }}">
                </div>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">科目</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule->subject_name }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                <label class="form-control-label">教室</label>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-8">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule_classroom_name }}">
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
                  <div class="form-group mb-1">
                    <input class="form-control form-control-sm" type="text" value="{{ $student_course[4] }}" readonly>
                  </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                  <label class="form-control-label">考勤状态</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                  <div class="form-group mb-1">
                    @if($student_course[1]==0)
                      <input class="form-control form-control-sm" type="text" value="请假" readonly>
                    @elseif($student_course[1]==1)
                      <input class="form-control form-control-sm" type="text" value="正常" readonly>
                    @else
                      <input class="form-control form-control-sm" type="text" value="旷课" readonly>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                  <label class="form-control-label">扣除课程</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                  <div class="form-group mb-1">
                    <input class="form-control form-control-sm" type="text" value="{{ $student_course[5] }}" readonly>
                  </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 text-right">
                  <label class="form-control-label">扣除课时</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                  <div class="form-group mb-1">
                    <input class="form-control form-control-sm" type="text" value="{{ $student_course[3] }}" readonly>
                  </div>
                </div>
              </div>
            @endforeach
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">上一步</button></a>
              </div>
              <div class="col-lg-6 col-md-4 col-sm-12 mb-1"></div>
              <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                <input type="hidden" name="input1" value="{{ $schedule_teacher }}">
                <input type="hidden" name="input2" value="{{ $schedule_classroom }}">
                <input type="hidden" name="input3" value="{{ count($student_courses) }}">
                <input type="submit" class="btn btn-warning btn-block" value="提交">
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
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationScheduleMy');
</script>
@endsection
