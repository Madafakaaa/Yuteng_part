@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">课程管理</li>
    <li class="breadcrumb-item"><a href="/timeset">时间设置</a></li>
    <li class="breadcrumb-item active">添加时间</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/timeset" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">添加时间</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">上课时间<span style="color:red">*</span></label>
                  <input class="form-control" type="time" name="input1" value="{{ date('H:i') }}" autocomplete="off" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">下课时间<span style="color:red">*</span></label>
                  <input class="form-control" type="time" name="input2" value="{{ date('H:i') }}" autocomplete="off" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <input type="submit" class="btn btn-primary btn-block" value="添加">
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
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('link-1-3');
  navbarActive('navbar-1-3');
  linkActive('timeset');
</script>
@endsection
