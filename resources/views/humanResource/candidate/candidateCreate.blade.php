@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">添加面试用户</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">人事中心</li>
    <li class="breadcrumb-item"><a href="/humanResource/candidate">面试用户</a></li>
    <li class="breadcrumb-item active">添加面试用户</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card">
        <form action="/humanResource/candidate/store" method="post" id="form1" name="form1" enctype="multipart/form-data" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">添加面试用户</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input_candidate_name" placeholder="请输入用户姓名..." autocomplete='off' required maxlength="5">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">性别<span style="color:red">*</span></label>
                  <select class="form-control" name="input_candidate_gender" data-toggle="select" required>
                    <option value=''>请选择性别...</option>
                    <option value='男'>男</option>
                    <option value='女'>女</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">手机号<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input_candidate_phone" placeholder="请输入用户手机... " autocomplete='off' maxlength="11" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">微信号<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input_candidate_wechat" placeholder="请输入用户微信... " autocomplete='off' maxlength="20" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">求职岗位<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input_candidate_position" placeholder="求职岗位... " autocomplete='off' maxlength="20" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">面试官<span style="color:red">*</span></label>
                  <select class="form-control" name="input_candidate_interviewer" data-toggle="select" required>
                    <option value=''>请选择面试用户...</option>
                      @foreach ($users as $user)
                        <option value="{{ $user->user_id }}">{{ $user->user_name }} [{{ $user->department_name }} | {{ $user->position_name }} ]</option>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">备注<span style="color:red">*</span></label>
                  <textarea class="form-control" name="input_candidate_comment" rows="3" resize="none" spellcheck="false" autocomplete='off' maxlength="140" required></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">简历文件<span style="color:red">*</span></label>
                  <div class="input-group">
                    <input id='location' class="form-control" disabled aria-describedby="button-addon">
                    <div class="input-group-append">
                      <input type="button" id="i-check" value="浏览文件" class="btn btn-outline-primary" onClick="$('#i-file').click();" style="margin:0;" id="button-addon">
                      <input type="file" name='file' id='i-file' onChange="$('#location').val($('#i-file').val());" style="display: none" required accept=".pdf">
                    </div>
                  </div>
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
                <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="提交">
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
  linkActive('link-humanResource');
  navbarActive('navbar-humanResource');
  linkActive('humanResourceUser');
</script>
@endsection
