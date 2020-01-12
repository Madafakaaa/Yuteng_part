@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">内部管理</li>
    <li class="breadcrumb-item active">校区管理</li>
    <li class="breadcrumb-item"><a href="/department">校区设置</a></li>
    <li class="breadcrumb-item active">校区详情</li>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/department/{{ $department->department_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h4 class="mb-0">修改校区</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $department->department_name }}" autocomplete='off' required maxlength="8">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">地址<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input2" value="{{ $department->department_location }}" autocomplete='off' required maxlength="30">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">电话1<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input3" value="{{ $department->department_phone1 }}" autocomplete='off' required maxlength="15">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">电话2</label>
                  <input class="form-control" type="text" name="input4" value="{{ $department->department_phone2 }}" autocomplete='off' maxlength="15">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">添加时间</label>
                  <input class="form-control" type="text" value="{{ $department->department_createtime }}" readonly>
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
  linkActive('link-1-1');
  navbarActive('navbar-1-1');
  linkActive('department');
</script>
@endsection
