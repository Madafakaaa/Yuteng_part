@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">添加客户</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">个人中心</li>
    <li class="breadcrumb-item"><a href="/self/customer">我的未签约学生</a></li>
    <li class="breadcrumb-item active">添加客户</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="card">
        <form action="/self/customer/store" method="post" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">添加客户</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">客户校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input0" data-toggle="select" required>
                    <option value=''>请选择客户校区...</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}" selected>{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程顾问<span style="color:red">*</span></label>
                  <input class="form-control" type="text" value="{{Session::get('user_name')}}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input2" placeholder="请输入学生姓名..." autocomplete='off' maxlength="5" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生性别<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
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
                  <label class="form-control-label">学生年级<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择学生年级...</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->grade_id }}">{{ $grade->grade_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">所属大区</label>
                  <select class="form-control" name="input5" data-toggle="select">
                    <option value='0'>请选择大区...</option>
                    @foreach ($schools as $school)
                      <option value="{{ $school->school_id }}">{{ $school->school_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">监护人姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input6" placeholder="请输入监护人姓名... " autocomplete='off' required maxlength="5">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">监护人关系<span style="color:red">*</span></label>
                  <select class="form-control" name="input7" data-toggle="select" required>
                    <option value=''>请选择监护人关系...</option>
                    <option value='爸爸'>爸爸</option>
                    <option value='妈妈'>妈妈</option>
                    <option value='爷爷'>爷爷</option>
                    <option value='奶奶'>奶奶</option>
                    <option value='叔叔'>叔叔</option>
                    <option value='阿姨'>阿姨</option>
                    <option value='其他'>其他</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">联系电话<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input8" placeholder="请输入联系电话..." autocomplete='off' required maxlength="11">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">微信号</label>
                  <input class="form-control" type="text" name="input9" placeholder="请输入微信号..." autocomplete='off' maxlength="20">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">来源类型<span style="color:red">*</span></label>
                  <select class="form-control" name="input10" data-toggle="select" required>
                    <option value=''>请选择来源...</option>
                    @foreach ($sources as $source)
                      <option value="{{ $source->source_name }}">{{ $source->source_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生生日<span style="color:red">*</span></label>
                  <input class="form-control datepicker" name="input11" type="text" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">跟进优先级<span style="color:red">*</span></label>
                  <select class="form-control" name="input12" data-toggle="select" required>
                    <option value='1' selected>低</option>
                    <option value='2'>中</option>
                    <option value='3'>高</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">备注</label>
                  <textarea class="form-control" name="input13" rows="3" resize="none" spellcheck="false" autocomplete='off' maxlength="140"></textarea>
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
  linkActive('link-self');
  navbarActive('navbar-self');
  linkActive('selfCustomer');
</script>
@endsection
