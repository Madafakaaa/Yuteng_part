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
          <h6 class="h2 text-white d-inline-block mb-0">学生排课</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">运营中心</li>
              <li class="breadcrumb-item active">学生排课</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
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
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/operation/studentSchedule/store" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">排课信息确认</h3>
          </div>
          <!-- Card body -->
          <div class="card-body pt-2">
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label">学生</label>
              </div>
              <div class="col-9">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <label>{{ $schedule_student->student_name }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label">校区</label>
              </div>
              <div class="col-9">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <label>{{ $schedule_student->department_name }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label">年级</label>
              </div>
              <div class="col-9">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <label>{{ $schedule_student->grade_name }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label">上课日期(<span style="color:red;">{{ count($schedule_dates) }}</span>天)</label>
              </div>
              <div class="col-9">
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
              <div class="col-3 text-right">
                <label class="form-control-label">上课时间</label>
              </div>
              <div class="col-9">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <label>{{ $schedule_start }} - {{ $schedule_end }} （{{ $schedule_time }}分钟）</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label">任课教师</label>
              </div>
              <div class="col-9">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <label>{{ $schedule_teacher->user_name }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label">课程</label>
              </div>
              <div class="col-9">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <label>{{ $schedule_course->course_name }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label">科目</label>
              </div>
              <div class="col-9">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <label>{{ $schedule_subject->subject_name }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label">教室</label>
              </div>
              <div class="col-9">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <label>{{ $schedule_classroom->classroom_name }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-3 text-right">
                <label class="form-control-label"><span style="color:red">*</span>自动生成班级名称</label>
              </div>
              <div class="col-9">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <div class="form-group">
                        <input class="form-control form-control-sm" type="text" name="input_class_name" value="{{ $class_name }}" required>
                      </div>
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
                <input type="hidden" name="input_department" value="{{ $schedule_student->student_department }}">
                <input type="hidden" name="input_student" value="{{ $schedule_student->student_id }}">
                <input type="hidden" name="input_teacher" value="{{ $schedule_teacher->user_id }}">
                <input type="hidden" name="input_classroom" value="{{ $schedule_classroom->classroom_id }}">
                <input type="hidden" name="input_subject" value="{{ $schedule_subject->subject_id }}">
                <input type="hidden" name="input_dates_str" value="{{ $schedule_dates_str }}">
                <input type="hidden" name="input_start" value="{{ $schedule_start }}">
                <input type="hidden" name="input_end" value="{{ $schedule_end }}">
                <input type="hidden" name="input_time" value="{{ $schedule_time }}">
                <input type="hidden" name="input_grade" value="{{ $schedule_student->student_grade }}">
                <input type="hidden" name="input_course" value="{{ $schedule_course->course_id }}">
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
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationStudentScheduleCreate');
</script>
@endsection
