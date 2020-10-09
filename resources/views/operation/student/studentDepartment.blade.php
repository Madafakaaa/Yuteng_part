@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">转校区</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item"><a href="/operation/student">学生管理</a></li>
    <li class="breadcrumb-item active">转校区</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row justify-content-center">
    <div class="col-8">
      <div class="card">
        <form action="/operation/student/user" method="get" onsubmit="submitButtonDisable('submitButton1')">
          <div class="card-header">
            <h3 class="mb-0">转校区</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生姓名</label>
                  <input class="form-control" type="text" value="{{$student->student_name}}" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">转至校区<span style="color:red">*</span></label>
                  <select class="form-control" name="student_department" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group mb-0">
                  <label class="form-control-label"><span style="color:red">*</span>转校区后学生将退出当前所在班级</label>
                </div>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
                <input type="hidden" name="student_id" value="{{$student->student_id}}">
                <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="下一步">
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
