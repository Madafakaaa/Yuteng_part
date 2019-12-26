@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">课程管理</li>
    <li class="breadcrumb-item"><a href="/course">课程设置</a></li>
    <li class="breadcrumb-item active">添加课程</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/course" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">添加课程</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" placeholder="请输入课程名称..." autocomplete='off' required maxlength="255">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">开课校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    <option value='0'>全校区</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
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
                    <option value='全年'>全年</option>
                    <option value='春季'>春季</option>
                    <option value='暑假'>暑假</option>
                    <option value='秋季'>秋季</option>
                    <option value='寒假'>寒假</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程年级<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择年级...</option>
                    <option value='0'>全年级</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->grade_id }}">{{ $grade->grade_name }}</option>
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
                    <option value='0'>全科目</option>
                    @foreach ($subjects as $subject)
                      <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课时单价(元/课时)<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input6" placeholder="请输入课时单价... " autocomplete='off' required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">结课日期<span style="color:red">*</span></label>
                  <input class="form-control datepicker" name="input7" type="text" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程时长<span style="color:red">*</span></label>
                  <select class="form-control" name="input8" data-toggle="select" required>
                    <option value=''>请选择课程时长...</option>
                    <option value='30'>30分钟</option>
                    <option value='60'>60分钟</option>
                    <option value='90'>90分钟</option>
                    <option value='120'>120分钟</option>
                    <option value='150'>150分钟</option>
                    <option value='180'>180分钟</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">课程备注</label>
                  <textarea class="form-control" name="input9" placeholder="请输入课程备注... " autocomplete='off'  maxlength="255"></textarea>
                </div>
              </div>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="添加课程">
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
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('link-1-3');
  navbarActive('navbar-1-3');
  linkActive('course');
</script>
@endsection
