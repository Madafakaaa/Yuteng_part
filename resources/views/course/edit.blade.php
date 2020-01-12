@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">课程管理</li>
    <li class="breadcrumb-item"><a href="/course">课程设置</a></li>
    <li class="breadcrumb-item active">修改课程</li>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/course/{{ $course->course_id }}" method="post">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h4 class="mb-0">修改课程</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $course->course_name }}" autocomplete='off' required maxlength="255">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">开课校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    <option value='0' @if($course->course_department==0) selected @endif>全校区</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}" @if($course->course_department==$department->department_id) selected @endif>{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程季度<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    <option value=''>请选择季度...</option>
                    <option value='全年' @if($course->course_quarter=='全年') selected @endif>全年</option>
                    <option value='春季' @if($course->course_quarter=='春季') selected @endif>春季</option>
                    <option value='暑假' @if($course->course_quarter=='暑假') selected @endif>暑假</option>
                    <option value='秋季' @if($course->course_quarter=='秋季') selected @endif>秋季</option>
                    <option value='寒假' @if($course->course_quarter=='寒假') selected @endif>寒假</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程年级<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择年级...</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->grade_id }}" @if($course->course_grade==$grade->grade_id) selected @endif>{{ $grade->grade_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程科目<span style="color:red">*</span></label>
                  <select class="form-control" name="input5" data-toggle="select" required>
                    <option value=''>请选择科目...</option>
                    <option value='0' @if($course->course_subject==0) selected @endif>全科目</option>
                    @foreach ($subjects as $subject)
                      <option value="{{ $subject->subject_id }}" @if($course->course_subject==$subject->subject_id) selected @endif>{{ $subject->subject_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课时单价(元/课时)<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input6" value="{{ $course->course_unit_price }}" autocomplete='off' required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程类型<span style="color:red">*</span></label>
                  <select class="form-control" name="input7" data-toggle="select" required>
                    <option value=''>请选择课程类型...</option>
                    <option value='一对一课程' @if($course->course_type=="一对一课程") selected @endif>一对一课程</option>
                    <option value='班级课程' @if($course->course_type=="班级课程") selected @endif>班级课程</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程时长(分钟)<span style="color:red">*</span></label>
                  <select class="form-control" name="input8" data-toggle="select" required>
                    <option value=''>请选择课程时长...</option>
                    <option value='30' @if($course->course_time==30) selected @endif>30分钟</option>
                    <option value='60' @if($course->course_time==60) selected @endif>60分钟</option>
                    <option value='90' @if($course->course_time==90) selected @endif>90分钟</option>
                    <option value='120' @if($course->course_time==120) selected @endif>120分钟</option>
                    <option value='150' @if($course->course_time==150) selected @endif>150分钟</option>
                    <option value='180' @if($course->course_time==180) selected @endif>180分钟</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">课程备注</label>
                  <textarea class="form-control" name="input9" autocomplete='off'  maxlength="255">{{ $course->course_remark }}</textarea>
                </div>
              </div>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="修改">
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
  linkActive('link-1-3');
  navbarActive('navbar-1-3');
  linkActive('course');
</script>
@endsection
