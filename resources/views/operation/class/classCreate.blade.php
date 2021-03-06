@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">新建班级</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item"><a href="/operation/class">班级管理</a></li>
    <li class="breadcrumb-item active">新建班级</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card">
        <form action="/operation/class/store" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">班级名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" placeholder="请输入班级名称... " autocomplete='off' maxlength="20" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">班级校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
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
                <div class="form-group">
                  <label class="form-control-label">班级年级<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    <option value=''>请选择年级...</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->grade_id }}">{{ $grade->grade_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">班级科目<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择科目...</option>
                    @foreach ($subjects as $subject)
                      <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">负责教师<span style="color:red">*</span></label>
                  <select class="form-control" name="input5" data-toggle="select" required>
                    <option value=''>请选择用户...</option>
                    @foreach ($users as $user)
                      @if($user->user_department==Session::get('user_department'))
                        <option value="{{ $user->user_id }}">{{ $user->user_name }} ({{ $user->position_name }})</option>
                      @else
                        <option value="{{ $user->user_id }}">{{ $user->user_name }} ({{ $user->position_name }} {{ $user->department_name }})</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">最大人数<span style="color:red">*</span></label>
                  <select class="form-control" name="input6" data-toggle="select" required>
                    <option value="1" selected>1人</option>
                    <option value="3">3人</option>
                    <option value="6">6人</option>
                    <option value="15">15人</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">备注</label>
                  <textarea class="form-control" name="input7" rows="3" resize="none" spellcheck="false" autocomplete='off' maxlength="140"></textarea>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-3">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-6"></div>
              <div class="col-3">
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
  linkActive('operationClass');
</script>
@endsection
