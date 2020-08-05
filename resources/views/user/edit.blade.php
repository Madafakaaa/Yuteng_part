@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">修改用户</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item active">修改用户</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card">
        <form action="/user/update?id={{encode($user->user_id,'user_id')}}" method="post">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">修改用户</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">账号</label>
                  <input class="form-control" type="text" value="{{ $user->user_id }}" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $user->user_name }}" autocomplete='off' required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">性别<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
                    <option value=''>请选择性别...</option>
                    <option value='男' @if($user->user_gender=="男") selected @endif>男</option>
                    <option value='女' @if($user->user_gender=="女") selected @endif>女</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}" @if($user->user_department==$department->department_id) selected @endif>{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">岗位<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择岗位...</option>
                    @foreach ($positions as $position)
                      <option value="{{ $position->position_id }}" @if($user->user_position==$position->position_id) selected @endif>{{ $position->section_name }}：{{ $position->position_name }} (等级 {{ $position->position_level }})</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">入职日期<span style="color:red">*</span></label>
                  <input class="form-control datepicker" name="input5" placeholder="Select date" type="text" value="{{ $user->user_entry_date }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">跨校区上课<span style="color:red">*</span></label>
                  <select class="form-control" name="input6" data-toggle="select" required>
                    <option value=''>请选择是否可以跨校区上课...</option>
                    <option value='1' @if($user->user_cross_teaching==1) selected @endif>是</option>
                    <option value='0' @if($user->user_cross_teaching==0) selected @endif>否</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">手机</label>
                  <input class="form-control" type="text" name="input7" value="{{ $user->user_phone }}" autocomplete='off' maxlength="11">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">微信</label>
                  <input class="form-control" type="text" name="input8" value="{{ $user->user_wechat }}" autocomplete='off' maxlength="20">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">添加时间</label>
                  <input class="form-control" type="text" value="{{ $user->user_createtime }}" readonly>
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
                <input type="submit" class="btn btn-warning btn-block" value="修改">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-human');
  navbarActive('navbar-human');
  linkActive('user');
</script>
@endsection
