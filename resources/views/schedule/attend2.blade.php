@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="/schedule">课程安排</a></li>
    <li class="breadcrumb-item active">课程考勤</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/schedule/attend/{{ $schedule->schedule_id }}/step3" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">二、选择扣除课程</h4>
          </div>
          <!-- Card body -->
          <div class="card-body pt-2">
            @foreach ($student_courses as $student_course)
              <div class="row">
                <div class="col-2">
                  <label class="form-control-label">上课学生{{ $loop->iteration }}</label>
                </div>
                <div class="col-10">
                  <div class="form-group mb-1">
                    <div class="row">
                      <div class="col-6 pl-2 pr-2 mb-2">
                        <input class="form-control form-control-sm" type="text" value="{{ $student_course[0]->student_name }}" readonly>
                      </div>
                      <div class="col-6 pl-6 pr-2 mb-2">
                        <div class="form-group">
                          <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                            <input type="radio" id="radio{{ $loop->iteration }}_1" name="input{{ $loop->iteration }}_1"  class="custom-control-input" value="1" checked onchange="disableInput({{ $loop->iteration }});">
                            <label class="custom-control-label" for="radio{{ $loop->iteration }}_1">正常</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                            <input type="radio" id="radio{{ $loop->iteration }}_2" name="input{{ $loop->iteration }}_1" class="custom-control-input" value="0" onchange="disableInput({{ $loop->iteration }});">
                            <label class="custom-control-label" for="radio{{ $loop->iteration }}_2">请假</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-2">
                  <label class="form-control-label">扣除课时<span style="color:red">*</span></label>
                </div>
                <div class="col-10">
                  <div class="form-group mb-1">
                    <div class="row">
                      <div class="col-6 pl-2 pr-2 mb-2">
                        <select class="form-control form-control-sm" name="input{{ $loop->iteration }}_2" id="input{{ $loop->iteration }}_2" data-toggle="select" required>
                          <option value=''>请选择课程...</option>
                          @foreach ($student_course[1] as $course)
                            <option value="{{ $course->course_id }}" @if($schedule->schedule_course==$course->course_id) selected @endif>{{ $course->course_name }} (剩余: {{ $course->hour_remain }} 课时)</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
            @endforeach
            <div class="row">
              <div class="col-3">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">上一步</button></a>
              </div>
              <div class="col-6"></div>
              <div class="col-3">
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
      // $("#input"+a+"_2 option[index='0']").attr("selected", true);
    }else{
      $("#input"+a+"_2").removeAttr("disabled");
    }
  }
</script>
@endsection
