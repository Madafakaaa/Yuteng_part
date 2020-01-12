@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item"><a href="/source">来源设置</a></li>
    <li class="breadcrumb-item active">添加来源</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/source" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">添加来源</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="form-group">
              <label class="form-control-label">来源名称<span style="color:red">*</span></label>
              <input class="form-control" type="text" name="input1" placeholder="请输入来源名称..." autocomplete='off' required maxlength="10">
            </div>
            <input type="submit" class="btn btn-primary" value="添加来源">
          </div>
        <form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-2');
  navbarActive('navbar-2');
  linkActive('source');
</script>
@endsection
