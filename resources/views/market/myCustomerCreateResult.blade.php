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
          <h6 class="h2 text-white d-inline-block mb-0">客户管理</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">招生中心</li>
              <li class="breadcrumb-item"><a href="/market/customer/my">我的客户</a></li>
              <li class="breadcrumb-item active">添加客户</li>
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
        <form action="/operation/studentSchedule/store" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">客户添加完成</h3>
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
                <h2 class="my-2 text-success">客户添加成功</h2>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/market/myCustomer/create" ><button type="button" class="btn btn-outline-primary btn-block">继续添加客户</button></a>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/student/{{ $student_id }}" ><button type="button" class="btn btn-outline-primary btn-block">查看客户详情</button></a>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/operation/customer/my" ><button type="button" class="btn btn-outline-primary btn-block">我的客户</button></a>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/market/contract/create?student_id={{$student_id}}" ><button type="button" class="btn btn-outline-primary btn-block">签约合同</button></a>
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
  linkActive('marketCustomerMy');
</script>
@endsection
