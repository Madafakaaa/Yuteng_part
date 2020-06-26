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
          <h6 class="h2 text-white d-inline-block mb-0">学生课时</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">运营中心</li>
              <li class="breadcrumb-item active">学生课时</li>
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
        <form action="/student/cleanHour/{{ $hour->hour_id }}" method="post" id="form1" name="form1">
          @csrf
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生</label>
                  <label>{{ $hour->student_name }}</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">课程</label>
                  <label>{{ $hour->course_name }}</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">购买课时</label>
                  <label>{{ $hour->hour_remain+$hour->hour_used }} 课时</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">赠送课时</label>
                  <label>{{ $hour->hour_remain_free+$hour->hour_used_free }} 课时</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">剩余课时</label>
                  <label>{{ $hour->hour_remain }} 课时</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">剩余赠送课时</label>
                  <label>{{ $hour->hour_remain_free }} 课时</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">清理课时<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input_cleaned_record_amount" value="0" autocomplete='off' required min="0" max="{{ $hour->hour_remain+$hour->hour_remain_free }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">清理原因<span style="color:red">*</span></label>
                  <textarea class="form-control" name="input_remark" rows="6" resize="none" spellcheck="false" autocomplete='off' maxlength="140" required></textarea>
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
