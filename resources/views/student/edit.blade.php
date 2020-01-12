@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="/student">学生管理</a></li>
    <li class="breadcrumb-item active">修改学生</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/student/{{ $student->student_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h4 class="mb-0">修改学生</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生学号<span style="color:red">*</span></label>
                  <input class="form-control" type="text" value="{{ $student->student_id }}" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $student->student_name }}" autocomplete='off' required maxlength="10">
                </div>
              </div>
            </div>
            <div class="row">
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
                  <label class="form-control-label">学生年级<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    <option value=''>请选择年级...</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->grade_id }}" @if($student->student_grade==$grade->grade_id) selected @endif>{{ $grade->grade_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生性别<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择性别...</option>
                    <option value='男' @if($student->student_gender=='男') selected @endif>男</option>
                    <option value='女' @if($student->student_gender=='女') selected @endif>女</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生生日<span style="color:red">*</span></label>
                  <input class="form-control datepicker" name="input5" type="text" value="{{ $student->student_birthday }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">公立学校</label>
                  <select class="form-control" name="input6" data-toggle="select">
                    <option value=''>请选择公立学校...</option>
                    @foreach ($schools as $school)
                      <option value="{{ $school->school_id }}" @if($student->student_school==$school->school_id) selected @endif>{{ $school->school_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">监护人姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input7" value="{{ $student->student_guardian }}" autocomplete='off' required maxlength="10">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">监护人关系<span style="color:red">*</span></label>
                  <select class="form-control" name="input8" data-toggle="select" required>
                    <option value=''>请选择监护人关系...</option>
                    <option value='父亲' @if($student->student_guardian_relationship=="父亲") selected @endif>父亲</option>
                    <option value='母亲' @if($student->student_guardian_relationship=="母亲") selected @endif>母亲</option>
                    <option value='叔叔' @if($student->student_guardian_relationship=="叔叔") selected @endif>叔叔</option>
                    <option value='阿姨' @if($student->student_guardian_relationship=="阿姨") selected @endif>阿姨</option>
                    <option value='爷爷' @if($student->student_guardian_relationship=="爷爷") selected @endif>爷爷</option>
                    <option value='奶奶' @if($student->student_guardian_relationship=="奶奶") selected @endif>奶奶</option>
                    <option value='外公' @if($student->student_guardian_relationship=="外公") selected @endif>外公</option>
                    <option value='外婆' @if($student->student_guardian_relationship=="外婆") selected @endif>外婆</option>
                    <option value='其他' @if($student->student_guardian_relationship=="其他") selected @endif>其他</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">联系电话<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input9" value="{{ $student->student_phone }}" autocomplete='off' maxlength="11" required>
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
  linkActive('student');
</script>
@endsection
