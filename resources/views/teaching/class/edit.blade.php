@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="/class">班级管理</a></li>
    <li class="breadcrumb-item active">修改班级</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/class/{{ $class->class_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h3 class="mb-0">修改班级</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">班级学号<span style="color:red">*</span></label>
                  <input class="form-control" type="text" value="{{ $class->class_id }}" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">班级名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $class->class_name }}" autocomplete='off' required maxlength="10">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">班级校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}" @if($class->class_department==$department->department_id) selected @endif>{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">班级年级<span style="color:red">*</span>@if($class->class_current_num>0) (当前班级已有学生，无法修改年级) @endif</label>
                  @if($class->class_current_num==0)
                    <select class="form-control" name="input3" data-toggle="select" required>
                      @foreach ($grades as $grade)
                        <option value="{{ $grade->grade_id }}" @if($class->class_grade==$grade->grade_id) selected @endif>{{ $grade->grade_name }}</option>
                      @endforeach
                    </select>
                  @else
                      <input class="form-control" type="text" value="{{ $class->grade_name }}" readonly>
                  @endif
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">班级科目<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value='0' @if($class->class_subject==0) selected @endif>全科目</option>
                    @foreach ($subjects as $subject)
                      <option value="{{ $subject->subject_id }}" @if($class->class_subject==$subject->subject_id) selected @endif>{{ $subject->subject_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">负责教师<span style="color:red">*</span></label>
                  <select class="form-control" name="input5" data-toggle="select" required>
                    @foreach ($users as $user)
                      <option value="{{ $user->user_id }}" @if($class->class_teacher==$user->user_id) selected @endif>{{ $user->user_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">最大人数<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input6" value="{{ $class->class_max_num }}" autocomplete='off' @if($class->class_current_num==0) min="2" @else min="{{ $class->class_current_num }}" @endif>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">当前人数</label>
                  <input class="form-control" type="number" value="{{ $class->class_current_num }}" readonly>
                </div>
              </div>
            </div>
            <input type="submit" class="btn btn-primary" value="修改">
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
  linkActive('class');
</script>
@endsection
