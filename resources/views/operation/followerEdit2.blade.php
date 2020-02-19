@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">修改负责人</li>
    <li class="breadcrumb-item active">选择负责人</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-success">选择学生</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #b0eed3;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-info">选择负责人</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="card main_card" style="display:none">
        <form action="/operation/follower/store" method="post">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">二、选择负责人</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>学生姓名</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $student->student_name }}" readonly>
                <input type="hidden" name="input1" value="{{ $student->student_id }}">
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">签约状态</label>
              </div>
              <div class="col-4 px-2 mb-2">
                @if($student->student_customer_status==0)
                  <input class="form-control form-control-sm" value="未签约" readonly>
                @else
                  <input class="form-control form-control-sm" value="已签约" readonly>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>课程顾问</label>
              </div>
              <div class="col-6 px-2 mb-2">
                @if($student->student_customer_status==0)
                <select class="form-control form-control-sm" name="input2" data-toggle="select" required>
                  <option value=''>请选择用户...</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->user_id }}" @if($student->student_consultant==$user->user_id) selected @endif>
                      {{ $user->user_name }} ({{ $user->position_name }})
                    </option>
                  @endforeach
                </select>
                @else
                  <input class="form-control form-control-sm" value="{{ $student->consultant_name }}" readonly>
                  <input type="hidden" name="input2" value="{{ $student->student_consultant }}">
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>班主任</label>
              </div>
              <div class="col-6 px-2 mb-2">
                @if($student->student_customer_status==1)
                <select class="form-control form-control-sm" name="input3" data-toggle="select" required>
                  <option value=''>请选择用户...</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->user_id }}" @if($student->student_class_adviser==$user->user_id) selected @endif>
                      {{ $user->user_name }} ({{ $user->position_name }})
                    </option>
                  @endforeach
                </select>
                @else
                  <input class="form-control form-control-sm" value="{{ $student->class_adviser_name }}" readonly>
                  <input type="hidden" name="input3" value="{{ $student->student_class_adviser }}">
                @endif
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
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationFollowerEdit');
</script>
@endsection
