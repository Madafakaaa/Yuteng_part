@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">修改负责人</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item"><a href="/operation/student">学生管理</a></li>
    <li class="breadcrumb-item active">修改负责人</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12">
      <div class="card">
        <form action="/operation/student/follower/update" method="post" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">修改负责人</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">学生姓名</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <label class="form-control-label">{{ $student->student_name }}</label>
                <input type="hidden" name="input1" value="{{encode($student->student_id, 'student_id')}}">
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">签约状态</label>
              </div>
              <div class="col-4 px-2 mb-2">
                @if($student->student_contract_num==0)
                  <label class="form-control-label"><span style="color:red;">未签约</span></label>
                @else
                  <label class="form-control-label"><span style="color:green;">已签约</span></label>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>课程顾问</label>
              </div>
              <div class="col-6 px-2 mb-2">
                <select class="form-control form-control-sm" name="input2" data-toggle="select" required>
                  <option value=''>请选择用户...</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->user_id }}" @if($student->student_consultant==$user->user_id) selected @endif>
                      {{ $user->user_name }} ({{ $user->position_name }})
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>班主任</label>
              </div>
              <div class="col-6 px-2 mb-2">
                <select class="form-control form-control-sm" name="input3" data-toggle="select" required>
                  <option value=''>请选择用户...</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->user_id }}" @if($student->student_class_adviser==$user->user_id) selected @endif>
                      {{ $user->user_name }} ({{ $user->position_name }})
                    </option>
                  @endforeach
                </select>
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
