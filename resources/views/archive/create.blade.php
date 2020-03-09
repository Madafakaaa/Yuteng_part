@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item"><a href="/archive">档案管理</a></li>
    <li class="breadcrumb-item active">添加档案</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12">
      <div class="card main_card" style="display:none">
        <form action="/archive" method="post" id="form1" name="form1" enctype="multipart/form-data">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">添加档案</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">档案用户<span style="color:red">*</span></label>
                  <select class="form-control" name="input1" data-toggle="select" required>
                    <option value=''>请选择用户...</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->user_id }}" @if($user->user_id==$user_id) selected @endif>{{ $user->user_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">档案名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input2" placeholder="请输入档案名称... " autocomplete='off' required maxlength="255">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">档案文件<span style="color:red">*</span></label>
                  <div class="input-group">
                    <input id='location' class="form-control" disabled aria-describedby="button-addon">
                    <div class="input-group-append">
                      <input type="button" id="i-check" value="浏览文件" class="btn btn-outline-primary" onClick="$('#i-file').click();" style="margin:0;" id="button-addon">
                      <input type="file" name='file' id='i-file' onChange="$('#location').val($('#i-file').val());" style="display: none">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
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
  linkActive('link-human');
  navbarActive('navbar-human');
  linkActive('archive');
</script>
@endsection
