@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">课程管理</li>
    <li class="breadcrumb-item"><a href="/course">课程设置</a></li>
    <li class="breadcrumb-item active">课程详情</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row">
    <div class="col-lg-4 col-md-8 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <div class="card-header">
          <h3 class="mb-0">课程详情</h3>
        </div>
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="form-control-label">课程名称</label>
                <input class="form-control" type="text" value="{{ $course->course_name }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">开课校区</label>
                <input class="form-control" type="text" value="@if($course->course_department==0) 全校区 @else{{ $course->department_name }}@endif" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课程季度</label>
                <input class="form-control" type="text" value="{{ $course->course_quarter }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课程年级</label>
                <input class="form-control" type="text" value="@if($course->course_grade==0) 全年级 @else{{ $course->grade_name }}@endif" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课程科目</label>
                <input class="form-control" type="text" value="@if($course->course_subject==0) 全科目 @else{{ $course->subject_name }}@endif" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课时单价</label>
                <input class="form-control" type="text" value="{{ $course->course_unit_price }}元" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">课时时长</label>
                <input class="form-control" type="text" value="{{ $course->course_time }}分钟" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">起始日期</label>
                <input class="form-control" type="text" value="{{ $course->course_start }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">截止日期</label>
                <input class="form-control" type="text" value="{{ $course->course_end }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="form-control-label">添加时间</label>
                <input class="form-control" type="text" value="{{ $course->course_createtime }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="form-control-label">备注</label>
                <h6>{{ $course->course_remark }}</h6>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <a href="/course/{{ $course->course_id }}/edit"><button class="btn btn-block btn-warning">修改</button></a>
            </div>
          </div>
        </div>
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
