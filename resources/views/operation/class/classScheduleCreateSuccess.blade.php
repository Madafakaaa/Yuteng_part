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
          <h6 class="h2 text-white d-inline-block mb-0">班级排课</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">运营中心</li>
              <li class="breadcrumb-item"><a href="/operation/class">班级管理</a></li>
              <li class="breadcrumb-item active">班级排课</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/operation/classSchedule/store" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">班级排课完成</h3>
          </div>
          <!-- Card body -->
          <div class="card-body pt-2">
            <div class="row justify-content-center">
              <div class="col-4 text-center">
                <img src="{{ asset(_ASSETS_.'/img/icons/success.png') }}" style="height:150px;">
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-3 text-center">
                <h2 class="my-2 text-success">班级课程安排成功</h2>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/operation/class/schedule/create?id={{ $id }}" ><button type="button" class="btn btn-outline-primary btn-block">再次排课</button></a>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/class?id={{ $id }}" ><button type="button" class="btn btn-outline-primary btn-block">查看班级详情</button></a>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/operation/class" ><button type="button" class="btn btn-outline-primary btn-block">班级管理</button></a>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/operation/mySchedule" ><button type="button" class="btn btn-outline-primary btn-block">我的学生课程安排</button></a>
              </div>
            </div>
          </div>
        <form>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <button type="button" class="btn btn-success btn-icon-only rounded-circle">
            <span class="btn-inner--icon">1</span>
          </button>
        </div>
        <div class="col-2 text-center">
          <button type="button" class="btn btn-success btn-icon-only rounded-circle">
            <span class="btn-inner--icon">2</span>
          </button>
        </div>
        <div class="col-2 text-center">
          <button type="button" class="btn btn-success btn-icon-only rounded-circle">
            <span class="btn-inner--icon">3</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationClass');
</script>
@endsection
