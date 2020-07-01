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
          <h6 class="h2 text-white d-inline-block mb-0">班级排课</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">运营中心</li>
              <li class="breadcrumb-item"><a href="/operation/class">班级管理</a></li>
              <li class="breadcrumb-item active">班级排课</li>
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
        <form action="/operation/class/schedule/create2" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">班级课程安排</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-center">
                <label class="form-control-label">班级</label>
              </div>
              <div class="col-2 pl-2 pr-2 mb-2">
                <label>{{ $class->class_name }}</label>
                <input type="hidden" name="input_class" value="{{ $class->class_id }}" required>
              </div>
              <div class="col-2 text-center">
                <label class="form-control-label">校区</label>
              </div>
              <div class="col-2 pl-2 pr-2 mb-2">
                <label>{{ $class->department_name }}</label>
              </div>
              <div class="col-2 text-center">
                <label class="form-control-label">年级</label>
              </div>
              <div class="col-2 pl-2 pr-2 mb-2">
                <label>{{ $class->grade_name }}</label>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-3 text-right">
                <div class="form-group">
                  <label class="form-control-label"><span style="color:red">*</span>上课日期</label>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <input class="form-control form-control-sm datepicker" type="text" name="input_date_start" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <div class="form-group">
                  <label class="form-control-label"><span style="color:red">*</span>至</label>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <input class="form-control form-control-sm datepicker" type="text" name="input_date_end" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label"><span style="color:red">*</span>排课规律</label>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <div class="row mb-2">
                      <div class="col-3">
                        <input type="checkbox" class="custom-control-input" id="checkAll" onchange="CheckAll();">
                        <label class="custom-control-label" for="checkAll"><strong>全选</strong></label>
                      </div>
                      <div class="col-3">
                        <input type="checkbox" class="custom-control-input checkbox" id="check0" name="input_days[]" value="0" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check0">周日</label>
                      </div>
                      <div class="col-3">
                        <input type="checkbox" class="custom-control-input checkbox" id="check6" name="input_days[]" value="6" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check6">周六</label>
                      </div>
                      <div class="col-3">
                        <input type="checkbox" class="custom-control-input checkbox" id="check5" name="input_days[]" value="5" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check5">周五</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                        <input type="checkbox" class="custom-control-input checkbox" id="check4" name="input_days[]" value="4" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check4">周四</label>
                      </div>
                      <div class="col-3">
                        <input type="checkbox" class="custom-control-input checkbox" id="check3" name="input_days[]" value="3" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check3">周三</label>
                      </div>
                      <div class="col-3">
                        <input type="checkbox" class="custom-control-input checkbox" id="check2" name="input_days[]" value="2" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check2">周二</label>
                      </div>
                      <div class="col-3">
                        <input type="checkbox" class="custom-control-input checkbox" id="check1" name="input_days[]" value="1" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check1">周一</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label"><span style="color:red">*</span>上课时间</label>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="input_start" data-toggle="select" required>
                    <option value=''>请选择上课时间...</option>
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
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label"><span style="color:red">*</span>下课时间</label>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="input_end" data-toggle="select" required>
                    <option value=''>请选择下课时间...</option>
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
            </div>
            <hr>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label"><span style="color:red">*</span>教师</label>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="input_teacher" data-toggle="select" required>
                    <option value=''>请选择教师...</option>
                    @foreach ($teachers as $teacher)
                      <option value="{{ $teacher->user_id }}">{{ $teacher->user_name }} ({{ $teacher->position_name }}@if($teacher->user_department!=$class->class_department) {{ $teacher->department_name }}@endif)</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label"><span style="color:red">*</span>教室</label>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="input_classroom" data-toggle="select" required>
                    <option value=''>请选择教室...</option>
                    @foreach ($classrooms as $classroom)
                      <option value="{{ $classroom->classroom_id }}">{{ $classroom->classroom_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label"><span style="color:red">*</span>课程</label>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="input_course" data-toggle="select" required>
                    <option value=''>请选择课程...</option>
                    @foreach ($courses as $course)
                      <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label"><span style="color:red">*</span>科目</label>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="input_subject" data-toggle="select" required>
                    <option value=''>请选择科目...</option>
                    @foreach ($subjects as $subject)
                      <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-4 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-4 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-4 col-md-5 col-sm-12">
                <input type="submit" class="btn btn-primary btn-block" value="下一步">
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
  linkActive('operationClass');

  function CheckAll(){
      // 判断是全选还是反选
      if($("#checkAll").is(':checked')){
          $(".checkbox").each(function(){
              $(this).prop('checked',true);
          });
      }else{
          $(".checkbox").each(function(){
              $(this).prop('checked',false);
          });
      }
  }

  function updateCheckAll(){
      // 判断是全选还是反选
      if($("#check0").is(':checked')
         &&$("#check1").is(':checked')
         &&$("#check2").is(':checked')
         &&$("#check3").is(':checked')
         &&$("#check4").is(':checked')
         &&$("#check5").is(':checked')
         &&$("#check6").is(':checked')){
          $("#checkAll").prop('checked',true);
      }else{
          $("#checkAll").prop('checked',false);
      }
  }
</script>
@endsection
