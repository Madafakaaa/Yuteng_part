@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">招生中心</li>
    <li class="breadcrumb-item"><a href="/customer">客户管理</a></li>
    <li class="breadcrumb-item active">修改客户</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/customer/{{ $student->student_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h4 class="mb-0">修改客户</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $student->student_name }}" autocomplete='off' maxlength="5" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生性别<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
                    <option value=''>请选择性别...</option>
                    <option value='男' @if($student->student_gender=="男") selected @endif>男</option>
                    <option value='女' @if($student->student_gender=="女") selected @endif>女</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生年级<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    <option value=''>请选择学生年级...</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->grade_id }}" @if($student->student_grade==$grade->grade_id) selected @endif>{{ $grade->grade_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">公立学校</label>
                  <select class="form-control" name="input4" data-toggle="select">
                    <option value='0'>请选择公立学校...</option>
                    @foreach ($schools as $school)
                      <option value="{{ $school->school_id }}" @if($student->student_school==$school->school_id) selected @endif>{{ $school->school_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">监护人姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input5" value="{{ $student->student_guardian }}" autocomplete='off' required maxlength="5">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">监护人关系<span style="color:red">*</span></label>
                  <select class="form-control" name="input6" data-toggle="select" required>
                    <option value=''>请选择监护人关系...</option>
                    <option value='爸爸' @if($student->student_guardian_relationship=="爸爸") selected @endif>爸爸</option>
                    <option value='妈妈' @if($student->student_guardian_relationship=="妈妈") selected @endif>妈妈</option>
                    <option value='爷爷' @if($student->student_guardian_relationship=="爷爷") selected @endif>爷爷</option>
                    <option value='奶奶' @if($student->student_guardian_relationship=="奶奶") selected @endif>奶奶</option>
                    <option value='叔叔' @if($student->student_guardian_relationship=="叔叔") selected @endif>叔叔</option>
                    <option value='阿姨' @if($student->student_guardian_relationship=="阿姨") selected @endif>阿姨</option>
                    <option value='其他' @if($student->student_guardian_relationship=="其他") selected @endif>其他</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">联系电话<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input7" value="{{ $student->student_phone }}" autocomplete='off' required maxlength="11">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">微信号</label>
                  <input class="form-control" type="text" name="input8" value="{{ $student->student_wechat }}" autocomplete='off' maxlength="20">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">来源类型<span style="color:red">*</span></label>
                  <select class="form-control" name="input9" data-toggle="select" required>
                    <option value=''>请选择来源...</option>
                    @foreach ($sources as $source)
                      <option value="{{ $source->source_name }}" @if($student->student_source==$source->source_name) selected @endif>{{ $source->source_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生生日<span style="color:red">*</span></label>
                  <input class="form-control datepicker" name="input10" type="text" value="{{ $student->student_birthday }}" required>
                </div>
              </div>
            </div>
            <hr>
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
        <form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-2');
  navbarActive('navbar-2');
  linkActive('customer');
</script>
@endsection