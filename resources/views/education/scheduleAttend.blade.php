@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教学中心</li>
    <li class="breadcrumb-item active">课程考勤</li>
    <li class="breadcrumb-item active">修改上课信息</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-info">修改上课信息</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #fdd1da;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-danger">选择学生考勤</span>
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
        <form action="/education/schedule/attend/{{ $schedule->schedule_id }}/step2" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">一、修改上课信息</h4>
          </div>
          <!-- Card body -->
          <div class="card-body pt-2">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课校区</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->department_name }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课日期</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->schedule_date }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课时间</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}" readonly>
                </div>
              </div>
              <div class="col-2 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->schedule_time }}分钟" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">学生 / 班级</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule->student_name }}{{ $schedule->class_name }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>任课教师</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input1" data-toggle="select" required>
                    <option value=''>请选择教师...</option>
                    <option value="{{ $schedule->user_id }}" selected>{{ $schedule->user_name }}</option>
                    @foreach ($users as $user)
                      @if($user[2]==0)
                        <option value="{{ $user[0] }}">{{ $user[1] }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">科目</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule->subject_name }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>教室</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input2" data-toggle="select" required>
                    <option value=''>请选择教室...</option>
                    <option value="{{ $schedule->classroom_id }}" selected>{{ $schedule->classroom_name }}</option>
                    @foreach ($classrooms as $classroom)
                      @if($classroom[2]==0)
                        <option value="{{ $classroom[0] }}">{{ $classroom[1] }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)"><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
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
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationScheduleMy');
</script>
@endsection
