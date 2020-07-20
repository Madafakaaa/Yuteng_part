@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">个人信息</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">个人信息</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
      <div class="card card-profile">
        <img src="{{ asset(_ASSETS_.'/img/theme/bg1.jpg') }}" alt="Image missing" class="card-img-top">
        <div class="row justify-content-center">
          <div class="col-lg-3 order-lg-2">
            <div class="card-profile-image">
              <img src="{{ asset(_ASSETS_.'/avatar/'.Session::get('user_photo')) }}" class="rounded-circle">
            </div>
          </div>
        </div>
        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
          <div class="d-flex justify-content-between">
            <a href="#" class="btn btn-sm btn-default mr-4">修改头像</a>
          </div>
        </div>
        <div class="card-body pt-0">
          <div class="text-center">
            <h5 class="h3">
              {{ $user->user_name }}
              @if($user->user_gender=="男")
                <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
              @else
                <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
              @endif
            </h5>
            <div class="h5 font-weight-300">
              <i class="ni location_pin mr-2"></i>{{ $user->user_id }}
            </div>
            <div class="h5 mt-4">
              <i class="ni business_briefcase-24 mr-2"></i>{{ $user->department_name }}
            </div>
            <div class="h5">
              <i class="ni business_briefcase-24 mr-2"></i>{{ $user->section_name }} - {{ $user->position_name }}
            </div>
            <div class="h5">
              <i class="ni business_briefcase-24 mr-2"></i>手机 - {{ $user->user_phone }}
            </div>
            <div class="h5">
              <i class="ni business_briefcase-24 mr-2"></i>微信 - {{ $user->user_wechat }}
            </div>
            <div class="h5">
              <i class="ni business_briefcase-24 mr-2"></i>入职日期 - {{ $user->user_entry_date }}
            </div>
          </div>
          <!-- <div class="row">
            <div class="col">
              <div class="card-profile-stats d-flex justify-content-center">
                <div>
                  <span class="heading">22</span>
                  <span class="description">Friends</span>
                </div>
                <div>
                  <span class="heading">10</span>
                  <span class="description">Photos</span>
                </div>
                <div>
                  <span class="heading">89</span>
                  <span class="description">Comments</span>
                </div>
              </div>
            </div>
          </div>-->
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
            <form action="/user/{{ $user->user_id }}/password" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
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
                    <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="修改">
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
<script>
  linkActive('profile');
</script>
@endsection
