@extends('main')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">修改信息</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="javascript:history.go(-1)">学生详情</a></li>
              <li class="breadcrumb-item active">修改信息</li>
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
        <form action="/student/update" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
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
                  <label class="form-control-label">学生校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}" @if($student->student_department==$department->department_id) selected @endif>{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生性别<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    <option value=''>请选择性别...</option>
                    <option value='男' @if($student->student_gender=="男") selected @endif>男</option>
                    <option value='女' @if($student->student_gender=="女") selected @endif>女</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生年级<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择学生年级...</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->grade_id }}" @if($student->student_grade==$grade->grade_id) selected @endif>{{ $grade->grade_name }}</option>
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
                      <option value="{{ $school->school_id }}" @if($student->student_school==$school->school_id) selected @endif>{{ $school->school_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">监护人姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input6" value="{{ $student->student_guardian }}" autocomplete='off' required maxlength="5">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">监护人关系<span style="color:red">*</span></label>
                  <select class="form-control" name="input7" data-toggle="select" required>
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
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">联系电话<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input8" value="{{ $student->student_phone }}" autocomplete='off' required maxlength="11">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">微信号</label>
                  <input class="form-control" type="text" name="input9" value="{{ $student->student_wechat }}" autocomplete='off' maxlength="20">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">来源类型<span style="color:red">*</span></label>
                  <select class="form-control" name="input10" data-toggle="select" required>
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
                  <input class="form-control datepicker" name="input11" type="text" value="{{ $student->student_birthday }}" required>
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
                <input name="id" type="hidden" value="{{ $student->student_id }}">
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
  linkActive('link-3');
  navbarActive('navbar-3');
</script>
@endsection
