@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">学生退费</li>
    <li class="breadcrumb-item active">选择退费学生</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-info">选择退费学生</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #fdd1da;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-danger">选择退费课程</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #fdd1da;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-danger">填写退费信息</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #fdd1da;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-danger">退费信息确认</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/market/refund/create2" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">一、选择退费学生</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>退费学生</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input1" data-toggle="select" required>
                    <option value=''>请选择学生...</option>
                    @foreach ($students as $student)
                      <option value="{{ $student->student_id }}">
                        @if($student->student_customer_status==0) 客户 @else 学生 @endif{{ $student->grade_name }} - {{ $student->student_name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="/refund" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
                <input type="submit" class="btn btn-primary btn-block" value="下一步">
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
  linkActive('operationRefundCreate');
</script>
@endsection
