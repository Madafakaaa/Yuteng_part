@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item"><a href="/section">部门设置</a></li>
    <li class="breadcrumb-item active">修改部门</li>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/section/{{ $section->section_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h4 class="mb-0">修改部门</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">部门名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $section->section_name }}" autocomplete='off' required maxlength="10">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">添加时间</label>
                  <input class="form-control" type="text" value="{{ $section->section_createtime }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-6"></div>
              <div class="col-3">
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
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('link-1-2');
  navbarActive('navbar-1-2');
  linkActive('section');
</script>
@endsection
