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
    <div class="col-lg-10 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/operation/schedule/attend/store" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">课程安排点名</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课校区</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $schedule->department_name }}</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>上课日期</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm datepicker" type="text" name="input_date" value="{{ $schedule->schedule_date }}" required>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>上课时间</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input_start" data-toggle="select" required>
                    <option value='{{date('H:i', strtotime($schedule->schedule_start))}}' selected>{{date('H:i', strtotime($schedule->schedule_start))}}</option>
                    <option value='08:00'>08:00</option>
                    <option value='08:30'>08:30</option>
                    <option value='09:00'>09:00</option>
                    <option value='09:30'>09:30</option>
                    <option value='10:00'>10:00</option>
                    <option value='10:30'>10:30</option>
                    <option value='11:00'>11:00</option>
                    <option value='11:30'>11:30</option>
                    <option value='12:00'>12:00</option>
                    <option value='12:30'>12:30</option>
                    <option value='13:00'>13:00</option>
                    <option value='13:30'>13:30</option>
                    <option value='14:00'>14:00</option>
                    <option value='14:30'>14:30</option>
                    <option value='15:00'>15:00</option>
                    <option value='15:30'>15:30</option>
                    <option value='16:00'>16:00</option>
                    <option value='16:30'>16:30</option>
                    <option value='17:00'>17:00</option>
                    <option value='17:30'>17:30</option>
                    <option value='18:00'>18:00</option>
                    <option value='18:30'>18:30</option>
                    <option value='19:00'>19:00</option>
                    <option value='19:30'>19:30</option>
                    <option value='20:00'>20:00</option>
                    <option value='20:30'>20:30</option>
                    <option value='21:00'>21:00</option>
                    <option value='21:30'>21:30</option>
                  </select>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>下课时间</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input_end" data-toggle="select" required>
                    <option value='{{date('H:i', strtotime($schedule->schedule_end))}}' selected>{{date('H:i', strtotime($schedule->schedule_end))}}</option>
                    <option value='08:30'>08:30</option>
                    <option value='09:00'>09:00</option>
                    <option value='09:30'>09:30</option>
                    <option value='10:00'>10:00</option>
                    <option value='10:30'>10:30</option>
                    <option value='11:00'>11:00</option>
                    <option value='11:30'>11:30</option>
                    <option value='12:00'>12:00</option>
                    <option value='12:30'>12:30</option>
                    <option value='13:00'>13:00</option>
                    <option value='13:30'>13:30</option>
                    <option value='14:00'>14:00</option>
                    <option value='14:30'>14:30</option>
                    <option value='15:00'>15:00</option>
                    <option value='15:30'>15:30</option>
                    <option value='16:00'>16:00</option>
                    <option value='16:30'>16:30</option>
                    <option value='17:00'>17:00</option>
                    <option value='17:30'>17:30</option>
                    <option value='18:00'>18:00</option>
                    <option value='18:30'>18:30</option>
                    <option value='19:00'>19:00</option>
                    <option value='19:30'>19:30</option>
                    <option value='20:00'>20:00</option>
                    <option value='20:30'>20:30</option>
                    <option value='21:00'>21:00</option>
                    <option value='21:30'>21:30</option>
                    <option value='22:00'>22:00</option>
                  </select>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>任课教师</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input_teacher" data-toggle="select" required>
                    @foreach ($teachers as $teacher)
                      <option value="{{ $teacher->user_id }}" @if($schedule->user_id==$teacher->user_id) selected @endif>{{ $teacher->user_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>科目</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input_subject" data-toggle="select" required>
                    @foreach ($subjects as $subject)
                      <option value="{{ $subject->subject_id }}" @if($schedule->subject_id==$subject->subject_id) selected @endif>{{ $subject->subject_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>教室</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input_classroom" data-toggle="select" required>
                    @foreach ($classrooms as $classroom)
                      <option value="{{ $classroom->classroom_id }}" @if($schedule->classroom_id==$classroom->classroom_id) selected @endif>{{ $classroom->classroom_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <hr>
            @foreach ($student_courses as $student_course)
              <div class="row">
                <div class="col-2 text-right">
                  <label class="form-control-label">上课学生{{ $loop->iteration }}</label>
                </div>
                <div class="col-4 px-2 mb-2">
                  <div class="form-group mb-1">
                    <label>
                      {{ $student_course[0]->student_name }} (学号：{{ $student_course[0]->student_id }})&nbsp;
                      @if($student_course[0]->student_gender=="男")
                        <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                      @else
                        <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                      @endif
                    </label>
                    <input type="hidden" name="input{{ $loop->iteration }}_0" value="{{ $student_course[0]->student_id }}">
                  </div>
                </div>
                <div class="col-2 text-right">
                  <label class="form-control-label">
                    <span style="color:red">*</span>
                    <span class="btn-inner--icon" data-toggle="tooltip" data-original-title="正常和旷课扣除课时，请假不扣除课时。"><i class="fas fa-question-circle"></i></span>
                    点名
                  </label>
                </div>
                <div class="col-4 px-2 mb-2">
                  <div class="form-group mb-1">
                    <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                      <input type="radio" id="radio{{ $loop->iteration }}_1" name="input{{ $loop->iteration }}_1"  class="custom-control-input" value="1" checked onchange="disableInput({{ $loop->iteration }});">
                      <label class="custom-control-label" for="radio{{ $loop->iteration }}_1">正常</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                      <input type="radio" id="radio{{ $loop->iteration }}_2" name="input{{ $loop->iteration }}_1" class="custom-control-input" value="2" onchange="disableInput({{ $loop->iteration }});">
                      <label class="custom-control-label" for="radio{{ $loop->iteration }}_2">请假</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                      <input type="radio" id="radio{{ $loop->iteration }}_3" name="input{{ $loop->iteration }}_1" class="custom-control-input" value="3" onchange="disableInput({{ $loop->iteration }});">
                      <label class="custom-control-label" for="radio{{ $loop->iteration }}_3">旷课</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-2 text-right">
                  <label class="form-control-label"><span style="color:red">*</span>扣除课程</label>
                </div>
                <div class="col-4 px-2 mb-2">
                  <div class="form-group mb-1">
                    <select class="form-control form-control-sm" name="input{{ $loop->iteration }}_2" id="input{{ $loop->iteration }}_2" data-toggle="select" required>
                      <option value=''>请选择课程...</option>
                      @foreach ($student_course[1] as $course)
                        <option value="{{ $course->course_id }}">
                          {{ $course->course_name }} ({{ $course->hour_remain }} 课时)
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-2 text-right">
                  <label class="form-control-label"><span style="color:red">*</span>扣除课时</label>
                </div>
                <div class="col-4 px-2 mb-2">
                  <div class="form-group mb-1">
                    <select class="form-control form-control-sm" name="input{{ $loop->iteration }}_3" id="input{{ $loop->iteration }}_3" required>
                      <option value='0.5'>0.5 课时</option>
                      <option value='1'>1 课时</option>
                      <option value='1.5'>1.5 课时</option>
                      <option value='2'>2 课时</option>
                      <option value='2.5'>2.5 课时</option>
                      <option value='3' selected>3 课时</option>
                      <option value='3.5'>3.5 课时</option>
                      <option value='4'>4 课时</option>
                      <option value='4.5'>4.5 课时</option>
                      <option value='5'>5 课时</option>
                      <option value='5.5'>5.5 课时</option>
                      <option value='6'>6 课时</option>
                    </select>
                  </div>
                </div>
              </div>
              <hr>
            @endforeach
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)"><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
                <input type="hidden" name="input_student_num" value="{{ count($student_courses) }}">
                <input type="hidden" name="input_schedule" value="{{ $schedule->schedule_id }}">
                <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="提交">
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
          <button type="button" class="btn btn-primary btn-icon-only rounded-circle">
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
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationSchedule');

function disableInput(a){
  if($("input[name='input"+a+"_1']:checked").val()==2){
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
