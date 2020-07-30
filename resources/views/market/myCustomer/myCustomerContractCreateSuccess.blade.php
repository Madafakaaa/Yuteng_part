@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">签约合同</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">招生中心</li>
    <li class="breadcrumb-item"><a href="/market/myCustomer">我的客户</a></li>
    <li class="breadcrumb-item active">签约合同</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card">
        <form action="/operation/studentSchedule/store" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">合同添加完成</h3>
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
                <h2 class="my-2 text-success">合同添加成功</h2>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/market/myCustomer/contract/create?id={{ $student_id }}"><button type="button" class="btn btn-outline-primary btn-block">继续签约</button></a>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/market/myStudent"><button type="button" class="btn btn-outline-primary btn-block">我的学生</button></a>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/market/myContract"><button type="button" class="btn btn-outline-primary btn-block">我的签约</button></a>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="/contract?id={{ $contract_id }}" target="_blank"><button type="button" class="btn btn-outline-primary btn-block">查看合同</button></a>
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
  linkActive('marketMyCustomer');
</script>
@endsection
