@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="/schedule">课程安排</a></li>
    <li class="breadcrumb-item active">课程考勤</li>
    <li class="breadcrumb-item active">填写考勤信息</li>
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
          <span class="badge badge-pill badge-info">选择学生考勤</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #fdd1da;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-danger">上传教案文件</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="card main_card" style="display:none">
        <form action="/schedule/attend/{{ $schedule->schedule_id }}/step3" method="post">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">二、选择学生考勤</h4>
          </div>
          <!-- Card body -->
          <div class="card-body pt-2">
            @foreach ($student_courses as $student_course)
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label class="form-control-label">上课学生{{ $loop->iteration }}</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                  <div class="form-group mb-1">
                    <input class="form-control form-control-sm" type="text" value="{{ $student_course[0]->student_name }}" readonly>
                    <input type="hidden" name="input{{ $loop->iteration }}_0" value="{{ $student_course[0]->student_id }}">
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group mb-1">
                    <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                      <input type="radio" id="radio{{ $loop->iteration }}_1" name="input{{ $loop->iteration }}_1"  class="custom-control-input" value="1" checked onchange="disableInput({{ $loop->iteration }});">
                      <label class="custom-control-label" for="radio{{ $loop->iteration }}_1">正常</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                      <input type="radio" id="radio{{ $loop->iteration }}_2" name="input{{ $loop->iteration }}_1" class="custom-control-input" value="0" onchange="disableInput({{ $loop->iteration }});">
                      <label class="custom-control-label" for="radio{{ $loop->iteration }}_2">请假</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                      <input type="radio" id="radio{{ $loop->iteration }}_3" name="input{{ $loop->iteration }}_1" class="custom-control-input" value="2" onchange="disableInput({{ $loop->iteration }});">
                      <label class="custom-control-label" for="radio{{ $loop->iteration }}_3">旷课</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label class="form-control-label"><span style="color:red">*</span>扣除课程</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                  <div class="form-group mb-1">
                    <select class="form-control form-control-sm" name="input{{ $loop->iteration }}_2" id="input{{ $loop->iteration }}_2" data-toggle="select" required>
                      <option value=''>请选择课程...</option>
                      @foreach ($student_course[1] as $course)
                        <option value="{{ $course->hour_id }}" @if($schedule->schedule_course==$course->course_id) selected @endif>{{ $course->course_name }} (剩余: {{ $course->hour_remain }} 课时)@if($course->hour_type==1) (赠) @endif</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4">
                  <label class="form-control-label"><span style="color:red">*</span>扣除课时</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                  <div class="form-group mb-1">
                    <select class="form-control form-control-sm" name="input{{ $loop->iteration }}_3" id="input{{ $loop->iteration }}_3" data-toggle="select" required>
                      <option value='1'>1 课时</option>
                      <option value='3' selected>3 课时</option>
                    </select>
                  </div>
                </div>
              </div>
              <hr>
            @endforeach
            <div class="row">
              <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">上一步</button></a>
              </div>
              <div class="col-lg-6 col-md-4 col-sm-12 mb-1"></div>
              <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                <input type="hidden" name="input1" value="{{ $schedule_teacher }}">
                <input type="hidden" name="input2" value="{{ $schedule_classroom }}">
                <input type="hidden" name="input3" value="{{ count($student_courses) }}">
                <input type="submit" class="btn btn-primary btn-block" value="下一步">
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

  function disableInput(a){
    if($("input[name='input"+a+"_1']:checked").val()==0){
      $("#input"+a+"_2").attr("disabled","disabled");
      $("#input"+a+"_3").attr("disabled","disabled");
      // $("#input"+a+"_2 option[index='0']").attr("selected", true);
    }else{
      $("#input"+a+"_2").removeAttr("disabled");
      $("#input"+a+"_3").removeAttr("disabled");
    }
  }
</script>
@endsection
