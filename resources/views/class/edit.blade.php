@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item active">修改班级</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/class/{{ $class->class_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h4 class="mb-0">修改班级</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">班号<span style="color:red">*</span></label>
                  <input class="form-control" type="text" value="{{ $class->class_id }}" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $class->class_name }}" autocomplete='off' required maxlength="10">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">校区<span style="color:red">*</span></label>
                  <input class="form-control" type="text" value="{{ $class->department_name }}" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">年级<span style="color:red">*</span>@if($class->class_current_num>0) (已有学生，无法修改) @endif</label>
                  @if($class->class_current_num==0)
                    <select class="form-control" name="input2" data-toggle="select" required>
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
                  <label class="form-control-label">科目<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    @foreach ($subjects as $subject)
                      <option value="{{ $subject->subject_id }}" @if($class->class_subject==$subject->subject_id) selected @endif>{{ $subject->subject_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">负责教师<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    @foreach ($users as $user)
                      <option value="{{ $user->user_id }}">{{ $user->department_name }}: {{ $user->user_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">最大人数<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input5" value="{{ $class->class_max_num }}" autocomplete='off' @if($class->class_current_num==0) min="2" @else min="{{ $class->class_current_num }}" @endif>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">当前人数</label>
                  <input class="form-control" type="number" value="{{ $class->class_current_num }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">备注</label>
                  <textarea class="form-control" name="input6" rows="3" resize="none" spellcheck="false" autocomplete='off' maxlength="140"></textarea>
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
  linkActive('link-3');
  navbarActive('navbar-3');
</script>
@endsection
