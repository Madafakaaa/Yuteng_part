@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">校区管理</li>
    <li class="breadcrumb-item"><a href="/classroom">教室设置</a></li>
    <li class="breadcrumb-item active">添加教室</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/classroom" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">添加教室</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">教室名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" placeholder="请输入教室名称..." autocomplete='off' required maxlength="10">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">所属校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">容纳人数<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input3" placeholder="请输入容纳人数..." autocomplete='off' min="1" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">教室类型<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择教室类型...</option>
                    <option value="一对一教室">一对一教室</option>
                    <option value="小班教室">小班教室</option>
                    <option value="大教室">大教室</option>
                    <option value="多媒体教室">多媒体教室</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-6"></div>
              <div class="col-3">
                <input type="submit" class="btn btn-warning btn-block" value="提交">
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
  linkActive('classroom');
</script>
@endsection
