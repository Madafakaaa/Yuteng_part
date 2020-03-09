@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">个人信息</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
      <div class="card card-profile">
        <div class="card-body">
          <div class="text-center pb-2">
            <h1>{{ $user->user_name }}</h1>
            <div class="h5 font-weight-300">{{ $user->user_id }}</div>
            <hr>
            <div class="row text-left ml-2">
              <div class="col-6">
                <div class="h4">校区 - {{ $user->department_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">性别 - {{ $user->user_gender }}</div>
              </div>
              <div class="col-6">
                <div class="h4">部门 - {{ $user->position_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">岗位 - {{ $user->section_name }}</div>
              </div>
            </div>
            <hr>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-8 col-md-6 col-sm-12">
      <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-3 active" id="password-tab" data-toggle="tab" href="#password-card" role="tab" aria-selected="true"><i class="ni ni-badge mr-2"></i>密码修改</a>
          </li>
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="password-card" role="tabpanel">
          <div class="card main_card mb-4" style="display:none">
            <form action="/user/{{ $user->user_id }}/password" method="post" id="form1" name="form1">
              @csrf
              <div class="card-body">
                <div class="row justify-content-center">
                  <div class="col-8">
                    <div class="form-group">
                      <label class="form-control-label">原密码<span style="color:red">*</span></label>
                      <input class="form-control" type="password" name="input1" autocomplete='off' required minlength="6" maxlength="15">
                    </div>
                  </div>
                </div>
                <div class="row justify-content-center">
                  <div class="col-8">
                    <div class="form-group">
                      <label class="form-control-label">新密码<span style="color:red">*</span></label>
                      <input class="form-control" type="password" name="input2"  autocomplete='off' required minlength="6" maxlength="15">
                    </div>
                  </div>
                </div>
                <div class="row justify-content-center">
                  <div class="col-8">
                    <div class="form-group">
                      <label class="form-control-label">新密码确认<span style="color:red">*</span></label>
                      <input class="form-control" type="password" name="input3"  autocomplete='off' required minlength="6" maxlength="15">
                    </div>
                  </div>
                </div>
                <hr class="my-3">
                <div class="row">
                  <div class="col-lg-8 col-md-7 col-sm-12 my-2"></div>
                  <div class="col-lg-3 col-md-5 col-sm-12">
                    <input type="submit" class="btn btn-warning btn-block" value="修改">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
@endsection
