@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">校区管理</li>
    <li class="breadcrumb-item"><a href="/school">学校设置</a></li>
    <li class="breadcrumb-item active">修改学校</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/school/{{ $school->school_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h3 class="mb-0">修改学校</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="form-group">
              <label class="form-control-label">学校序号</label>
              <input class="form-control" type="text" value="{{ $school->school_id }}" readonly>
            </div>
            <div class="form-group">
              <label class="form-control-label">学校名称<span style="color:red">*</span></label>
              <input class="form-control" type="text" name="input1" value="{{ $school->school_name }}" autocomplete='off' required maxlength="10">
            </div>
            <div class="form-group">
              <label class="form-control-label">添加时间</label>
              <input class="form-control" type="text" value="{{ $school->school_createtime }}" readonly>
            </div>
            <input type="submit" class="btn btn-warning" value="修改">
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
  linkActive('school');
</script>
@endsection
