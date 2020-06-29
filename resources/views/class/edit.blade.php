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
              <li class="breadcrumb-item"><a href="javascript:history.go(-1)">班级详情</a></li>
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
        <form action="/class/update?id={{ encode($class->class_id, 'class_id') }}" method="post" id="form1" name="form1">
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
                      <input type="hidden" name="input2" value="{{ $class->class_grade }}">
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
                      @if($user->user_department==Session::get('user_department'))
                        <option value="{{ $user->user_id }}" @if($class->class_teacher==$user->user_id) selected @endif>{{ $user->user_name }} ({{ $user->position_name }})</option>
                      @else
                        <option value="{{ $user->user_id }}" @if($class->class_teacher==$user->user_id) selected @endif>{{ $user->user_name }} ({{ $user->position_name }} {{ $user->department_name }})</option>
                      @endif
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
                  <textarea class="form-control" name="input6" rows="3" resize="none" spellcheck="false" autocomplete='off' maxlength="140">{{ $class->class_remark }}</textarea>
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
