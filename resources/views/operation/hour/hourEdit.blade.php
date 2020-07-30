@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">课时修改</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item"><a href="/operation/hour">学生课时</a></li>
    <li class="breadcrumb-item active">课时修改</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card">
        <form action="/operation/hour/update" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <!-- Card body -->
          <div class="card-header">
            <h3>课时修改</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">学生</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $student->student_name }}</label>
                  <input type="hidden" name="input1" value="{{ $student->student_id }}">
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">课程</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $course->course_name }}</label>
                  <input type="hidden" name="input2" value="{{ $course->course_id }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">已用课时</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $hour->hour_used }} 课时</label>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">剩余课时</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $hour->hour_remain }} 课时</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">课时单价</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $hour->hour_average_price }} 元/课时</label>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>修改课时</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" name="input3" type="number" autocomplete='off' min="0.0" value="{{ $hour->hour_remain }}" step="0.1" required>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>修改课时单价</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" name="input4" type="number" autocomplete='off' min="0.0" value="{{ $hour->hour_average_price }}" step="0.01" required>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">课时修改备注</label>
              </div>
              <div class="col-10 px-2 mb-2">
                <textarea class="form-control" name="input5" rows="3" resize="none" placeholder="备注..." maxlength="255"></textarea>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-4 col-md-5 col-sm-12">
                <a href="/operation/hour" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-4 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-4 col-md-5 col-sm-12">
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
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationHour');
</script>
@endsection
