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
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/course/{{ $course->course_id }}" method="post">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h3 class="mb-0">修改课程</h3>
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
                    <option value='0' @if($course->course_grade==0) selected @endif>全年级</option>
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
                  <label class="form-control-label">课时时长(分钟)<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input7" value="{{ $course->course_time }}" autocomplete='off' required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">有效日期<span style="color:red">*</span></label>
                  <div class="row">
                    <div class="col-5">
                      <input class="form-control datepicker" name="input8" type="text" value="{{ $course->course_start }}" required>
                    </div>
                    <div class="col-2 p-0 m-0">
                      <h1 class="text-center">-</h1>
                    </div>
                    <div class="col-5">
                      <input class="form-control datepicker" name="input9" type="text" value="{{ $course->course_end }}" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">课程备注</label>
                  <textarea class="form-control" name="input10" autocomplete='off'  maxlength="255">{{ $course->course_remark }}</textarea>
                </div>
              </div>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="添加课程">
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
