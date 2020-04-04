@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">修改校区</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">公司管理</li>
              <li class="breadcrumb-item"><a href="/company/department">校区设置</a></li>
              <li class="breadcrumb-item active">修改校区</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/company/department/{{ $department->department_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $department->department_name }}" autocomplete='off' required maxlength="5">
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
                  <input class="form-control" type="text" name="input3" value="{{ $department->department_phone1 }}" autocomplete='off' required maxlength="11">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">电话2</label>
                  <input class="form-control" type="text" name="input4" value="{{ $department->department_phone2 }}" autocomplete='off' maxlength="11">
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('companyDepartment');
</script>
@endsection