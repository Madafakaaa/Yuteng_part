@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">用户转正</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">人事中心</li>
    <li class="breadcrumb-item"><a href="/humanResource/candidate">面试用户</a></li>
    <li class="breadcrumb-item active">用户转正</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card">
        <form action="/humanResource/candidate/upgrade/store" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">用户转正</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input_user_name" value="{{$candidate->candidate_name}}" readonly autocomplete='off' required maxlength="5">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">用户账号<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input_user_id" value="{{$candidate->candidate_id}}" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">性别<span style="color:red">*</span></label>
                  <select class="form-control" name="input_user_gender" data-toggle="select" required>
                    <option value=''>请选择性别...</option>
                    <option value='男' @if($candidate->candidate_gender=='男') selected @endif>男</option>
                    <option value='女' @if($candidate->candidate_gender=='女') selected @endif>女</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input_user_department" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">岗位<span style="color:red">*</span></label>
                  <select class="form-control" name="input_user_position" data-toggle="select" required>
                    <option value=''>请选择岗位...</option>
                    @foreach ($positions as $position)
                      <option value="{{ $position->position_id }}">{{ $position->section_name }}：{{ $position->position_name }} (等级 {{ $position->position_level }})</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">入职日期<span style="color:red">*</span></label>
                  <input class="form-control datepicker" name="input_user_entry_date" placeholder="Select date" type="text" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">跨校区上课<span style="color:red">*</span></label>
                  <select class="form-control" name="input_user_cross_teaching" data-toggle="select" required>
                    <option value=''>请选择是否可以跨校区上课...</option>
                    <option value='1'>是</option>
                    <option value='0' selected>否</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">手机号</label>
                  <input class="form-control" type="text" name="input_user_phone" value="{{$candidate->candidate_phone}}" autocomplete='off' maxlength="11">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">微信号</label>
                  <input class="form-control" type="text" name="input_user_wechat" value="{{$candidate->candidate_wechat}}"autocomplete='off' maxlength="20">
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
  linkActive('link-humanResource');
  navbarActive('navbar-humanResource');
  linkActive('humanResourceCandidate');
</script>
@endsection
