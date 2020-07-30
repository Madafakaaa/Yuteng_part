@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">插入班级</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item"><a href="/operation/student">学生管理</a></li>
    <li class="breadcrumb-item active">插入班级</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-7 col-sm-12 card-wrapper ct-example">
      <div class="card">
        <form action="/operation/student/joinClass/store" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <!-- Card body -->
          <div class="card-header">
            <h3>插入班级</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">学生</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $student->student_name }}</label>
                  <input type="hidden" name="input1" value="{{ $student->student_id }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>使用课程</label>
              </div>
              <div class="col-8 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input2" data-toggle="select" required>
                    <option value=''>请选择课程...</option>
                    @foreach ($hours as $hour)
                      <option value="{{ $hour->course_id }}" @if($course_id==$hour->course_id) selected @endif>{{ $hour->course_name }}  （ 剩余{{ $hour->hour_remain }}课时）</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>插入班级</label>
              </div>
              <div class="col-8 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input3" data-toggle="select" required>
                    <option value=''>请选择班级...</option>
                    @foreach ($classes as $class)
                      <option value="{{ $class->class_id }}">{{ $class->class_name }}  （ {{ $class->subject_name }}，{{ $class->class_current_num }}/{{ $class->class_max_num }}人，教师：{{ $class->user_name }}，已安排课程{{ $class->class_schedule_num }}节，已上{{ $class->class_attended_num }}节 ）</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-4 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-4 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-4 col-md-5 col-sm-12">
                <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="提交">
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
  linkActive('operationStudent');
</script>
@endsection
