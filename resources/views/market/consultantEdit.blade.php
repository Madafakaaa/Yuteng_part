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
          <h6 class="h2 text-white d-inline-block mb-0">修改课程顾问</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">招生中心</li>
              <li class="breadcrumb-item active"><a href="/market/customer/all">客户管理</a></li>
              <li class="breadcrumb-item active">修改课程顾问</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-success">选择学生</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #b0eed3;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-info">选择负责人</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="card main_card" style="display:none">
        <form action="/market/customer/consultant/store" method="post">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">修改课程顾问</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">学生姓名</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <label class="form-control-label">{{ $student->student_name }}</label>
                <input type="hidden" name="input1" value="{{ $student->student_id }}">
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">签约状态</label>
              </div>
              <div class="col-4 px-2 mb-2">
                @if($student->student_customer_status==0)
                  <label class="form-control-label"><span style="color:red;">未签约</span></label>
                @else
                  <label class="form-control-label"><span style="color:green;">已签约</span></label>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>课程顾问</label>
              </div>
              <div class="col-6 px-2 mb-2">
                <select class="form-control form-control-sm" name="input2" data-toggle="select" required>
                  <option value=''>请选择用户...</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->user_id }}" @if($student->student_consultant==$user->user_id) selected @endif>
                      {{ $user->user_name }} ({{ $user->position_name }})
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)"><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
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
  linkActive('link-market');
  navbarActive('navbar-market');
  linkActive('marketCustomerAll');
</script>
@endsection
