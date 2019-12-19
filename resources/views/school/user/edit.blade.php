@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item"><a href="/user">用户列表</a></li>
    <li class="breadcrumb-item active">修改用户</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/user/{{ $user->user_id }}" method="post">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h3 class="mb-0">修改用户</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">用户账号</label>
                  <input class="form-control" type="text" value="{{ $user->user_id }}" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">用户名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $user->user_name }}" autocomplete='off' required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group mb-2">
                  <label class="form-control-label">用户性别<span style="color:red">*</span></label>
                </div>
                <div class="form-group">
                  <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                    <input type="radio" id="input2_1" name="input2"  class="custom-control-input" value="男" @if($user->user_gender=="男") checked @endif>
                    <label class="custom-control-label" for="input2_1">男</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                    <input type="radio" id="input2_2" name="input2" class="custom-control-input" value="女" @if($user->user_gender=="女") checked @endif>
                    <label class="custom-control-label" for="input2_2">女</label>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">用户校区<span style="color:red">*</span></label>
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
                  <label class="form-control-label">用户岗位<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择岗位...</option>
                    @foreach ($positions as $position)
                      <option value="{{ $position->position_id }}" @if($user->user_position==$position->position_id) selected @endif>{{ $position->position_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">用户级别<span style="color:red">*</span></label>
                  <select class="form-control" name="input5" data-toggle="select" required>
                    <option value=''>请选择级别...</option>
                    @foreach ($levels as $level)
                      <option value="{{ $level->level_id }}" @if($user->user_level==$level->level_id) selected @endif>{{ $level->level_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">入职日期<span style="color:red">*</span></label>
                  <input class="form-control datepicker" name="input6" placeholder="Select date" type="text" value="{{ $user->user_dateOfEntry }}" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">用户手机</label>
                  <input class="form-control" type="text" name="input7" value="{{ $user->user_phone }}" autocomplete='off' maxlength="11">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">用户微信</label>
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
            <input type="submit" class="btn btn-warning" value="修改">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('link-1-2');
  navbarActive('navbar-1-2');
  linkActive('user');
</script>
@endsection
