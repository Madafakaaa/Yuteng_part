@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">校区管理</li>
    <li class="breadcrumb-item"><a href="/school">学校设置</a></li>
    <li class="breadcrumb-item active">学校详情</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-8 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <div class="card-header">
          <h4 class="mb-0">学校详情</h4>
        </div>
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">学校名称</label>
                <input class="form-control" type="text" value="{{ $school->school_name }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">所属校区</label>
                <input class="form-control" type="text" value="{{ $school->department_name }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="form-control-label">添加时间</label>
                <input class="form-control" type="text" value="{{ $school->school_createtime }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <a href="/school/{{ $school->school_id }}/edit"><button class="btn btn-block btn-warning">修改</button></a>
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('school');
</script>
@endsection
