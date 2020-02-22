@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">人事管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item active">用户权限</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="card main_card" style="display:none">
        <form action="/user/access/{{ $user->user_id }}" method="post">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">用户权限</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">用户</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $user->user_name }}" readonly>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">账号</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $user->user_id }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">校区</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $user->department_name }}" readonly>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">等级</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $user->position_level }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">部门</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $user->section_name }}" readonly>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">岗位</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $user->position_name }}" readonly>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">校区权限</label>
              </div>
              <div class="col-8 px-4 mb-2">
                <div class="form-group mb-1">
                  <div class="custom-control custom-checkbox">
                    <div class="row">
                      @foreach($department_array as $department)
                        <div class="col-3 pl-2 pr-2 mb-2">
                          <input type="checkbox" class="custom-control-input checkbox" id="department_{{ $department[0] }}" name="departments[]" value="{{ $department[0] }}" @if($department[2]==1) checked @endif>
                          <label class="custom-control-label" for="department_{{ $department[0] }}">{{ $department[1] }}</label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">页面权限</label>
              </div>
              <div class="col-8 px-4 mb-2">
                <div class="form-group mb-1">
                  <div class="custom-control custom-checkbox">
                    <div class="row">
                      @foreach($page_array as $page)
                        <div class="col-6 pl-2 pr-2 mb-2">
                          <input type="checkbox" class="custom-control-input checkbox" id="page_{{ $page[0] }}" name="pages[]" value="{{ $page[0] }}" @if($page[2]==1) checked @endif>
                          <label class="custom-control-label" for="page_{{ $page[0] }}">{{ $page[1] }}</label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
                <input type="submit" class="btn btn-warning btn-block" value="修改">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('user');
</script>
@endsection
