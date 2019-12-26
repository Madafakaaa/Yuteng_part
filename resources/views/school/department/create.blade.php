@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item"><a href="/department">校区管理</a></li>
    <li class="breadcrumb-item active">校区设置</li>
    <li class="breadcrumb-item active">添加校区</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/department" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">添加校区</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">校区名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" placeholder="请输入校区名称..." autocomplete='off' required maxlength="10">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <input type="submit" class="btn btn-primary btn-block" value="提交">
                </div>
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
  linkActive('link-1-1');
  navbarActive('navbar-1-1');
  linkActive('department');
</script>
@endsection
