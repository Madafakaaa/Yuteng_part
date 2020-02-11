@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="/schedule">课程安排</a></li>
    <li class="breadcrumb-item active">选择课程信息</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-success">选择上课时间</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #b0eed3;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-info">选择课程信息</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #fdd1da;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-danger">确认上课信息</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/schedule/create/step3" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">二、选择课程信息</h4>
          </div>
          <!-- Card body -->
          <div class="card-body pt-2">
            <div class="row">
              <div class="col-2 text-right">
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
              <div class="col-2 text-right">
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
              <div class="col-2 text-right">
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
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>可选学生/班级</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <select class="form-control form-control-sm" name="input1" data-toggle="select" required>
                        <option value=''>请选择学生/班级...</option>
                        @foreach ($students as $student)
                          @if($student[2]==0)
                            <option value="{{ $student[0] }}">学生: {{ $student[1] }}</option>
                          @endif
                        @endforeach
                        @foreach ($classes as $class)
                          @if($class[2]==0)
                            <option value="{{ $class[0] }}">班级: {{ $class[1] }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>可选教师</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <select class="form-control form-control-sm" name="input2" data-toggle="select" required>
                        <option value=''>请选择教师...</option>
                        @foreach ($users as $user)
                          @if($user[2]==0)
                            <option value="{{ $user[0] }}">@if($user[4]!=Session::get('user_department')){{ $user[3] }}：@endif{{ $user[1] }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>可选教室</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <select class="form-control form-control-sm" name="input3" data-toggle="select" required>
                        <option value=''>请选择教室...</option>
                        @foreach ($classrooms as $classroom)
                          @if($classroom[2]==0)
                            <option value="{{ $classroom[0] }}">{{ $classroom[1] }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>课程</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <select class="form-control form-control-sm" name="input4" data-toggle="select" required>
                        <option value=''>请选择课程...</option>
                        @foreach ($courses as $course)
                          <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>科目</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <select class="form-control form-control-sm" name="input5" data-toggle="select" required>
                        <option value=''>请选择科目...</option>
                        @foreach ($subjects as $subject)
                          <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">上一步</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
                <input type="hidden" name="input6" value="{{ $schedule_dates_str }}">
                <input type="hidden" name="input7" value="{{ $schedule_start }}">
                <input type="hidden" name="input8" value="{{ $schedule_end }}">
                <input type="hidden" name="input9" value="{{ $schedule_time }}">
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
</script>
@endsection
