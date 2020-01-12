@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">课程管理</li>
    <li class="breadcrumb-item"><a href="/subject">科目设置</a></li>
    <li class="breadcrumb-item active">添加科目</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/subject" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">添加科目</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="form-group">
              <label class="form-control-label">科目名称<span style="color:red">*</span></label>
              <input class="form-control" type="text" name="input1" placeholder="请输入科目名称..." autocomplete='off' required maxlength="10">
            </div>
            <input type="submit" class="btn btn-primary" value="添加科目">
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
  linkActive('subject');
</script>
@endsection
