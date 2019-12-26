@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="/schedule">课程管理</a></li>
    <li class="breadcrumb-item active">课程详情</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row">
    <div class="col-lg-4 col-md-8 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <div class="card-header">
          <h3 class="mb-0">课程详情</h3>
        </div>
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课程名称</label>
                <input class="form-control" type="text" value="{{ $schedule->schedule_name }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课程校区</label>
                <input class="form-control" type="text" value="{{ $schedule->department_name }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课程年级</label>
                <input class="form-control" type="text" value="{{ $schedule->grade_name }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课程性别</label>
                <input class="form-control" type="text" value="{{ $schedule->schedule_gender }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课程生日</label>
                <input class="form-control" type="text" value="{{ $schedule->schedule_birthday }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课程学校</label>
                <input class="form-control" type="text" value="{{ $schedule->school_name }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">联系电话</label>
                <input class="form-control" type="text" value="{{ $schedule->schedule_phone }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">添加时间</label>
                <input class="form-control" type="text" value="{{ $schedule->schedule_createtime }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <a href="/schedule/{{ $schedule->schedule_id }}/edit"><button class="btn btn-block btn-warning">修改</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-3');
  navbarActive('navbar-3');
  linkActive('schedule');
</script>
@endsection
