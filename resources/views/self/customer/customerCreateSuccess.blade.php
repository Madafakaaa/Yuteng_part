@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">添加学生</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">个人中心</li>
    <li class="breadcrumb-item"><a href="/self/customer">我的未签约学生</a></li>
    <li class="breadcrumb-item active">添加学生</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card">
          <div class="card-header">
            <h3 class="mb-0">学生添加完成</h3>
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
                <h2 class="my-2 text-success">学生添加成功</h2>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/self/customer/create"><button type="button" class="btn btn-outline-primary btn-block">继续添加学生</button></a>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/self/customer"><button type="button" class="btn btn-outline-primary btn-block">我的未签约学生</button></a>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/student?id={{ $id }}" ><button type="button" class="btn btn-outline-primary btn-block">查看学生详情</button></a>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-self');
  navbarActive('navbar-self');
  linkActive('selfCustomer');
</script>
@endsection
