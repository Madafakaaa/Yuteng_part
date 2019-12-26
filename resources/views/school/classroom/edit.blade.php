@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">校区管理</li>
    <li class="breadcrumb-item"><a href="/classroom">教室设置</a></li>
    <li class="breadcrumb-item active">修改教室</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/classroom/{{ $classroom->classroom_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h3 class="mb-0">修改教室</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">教室序号</label>
                  <input class="form-control" type="text" value="{{ $classroom->classroom_id }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">教室名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $classroom->classroom_name }}" autocomplete='off' required maxlength="10">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">添加时间</label>
                  <input class="form-control" type="text" value="{{ $classroom->classroom_createtime }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
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
  linkActive('classroom');
</script>
@endsection
