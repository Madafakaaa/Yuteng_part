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
          <h6 class="h2 text-white d-inline-block mb-0">用户权限</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">公司管理</li>
              <li class="breadcrumb-item"><a href="/company/user">用户管理</a></li>
              <li class="breadcrumb-item active">用户权限</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-8 col-sm-12">
      <div class="card main_card" style="display:none">
        <form action="/company/user/access/update" method="post" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
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
                          <input type="checkbox" class="custom-control-input department" id="department_{{ $loop->iteration }}" name="departments[]" value="{{ $department[0] }}" @if($department[2]==1) checked @endif>
                          <label class="custom-control-label" for="department_{{ $loop->iteration }}">{{ $department[1] }}</label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-2 px-4 mb-2">
                <div class="row">
                  <div class="col-12 mb-2">
                    <button type="button" class="btn btn-primary btn-block btn-sm" style="white-space:nowrap; overflow:hidden;" onclick="checkAll('department');">全选</button>
                  </div>
                  <div class="col-12 mb-2">
                    <button type="button" class="btn btn-outline-primary btn-block btn-sm" style="white-space:nowrap; overflow:hidden;" onclick="uncheckAll('department');">全不选</button>
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
                    @foreach($categories as $category)
                      <div class="row">
                        <div class="col-4 mb-2">
                          {{ $category[0] }}
                        </div>
                        <div class="col-3 mb-2">
                          <button type="button" class="btn btn-primary btn-block btn-sm" style="white-space:nowrap; overflow:hidden;" onclick="checkAll('{{ $category[0] }}');">全选</button>
                        </div>
                        <div class="col-3 mb-2">
                          <button type="button" class="btn btn-outline-primary btn-block btn-sm" style="white-space:nowrap; overflow:hidden;" onclick="uncheckAll('{{ $category[0] }}');">全不选</button>
                        </div>
                      </div>
                      <div class="row">
                        @foreach($category[1] as $page)
                          <div class="col-6 mb-2">
                            <input type="checkbox" class="custom-control-input {{ $category[0] }}" id="{{$category[0]}}__{{ $loop->iteration }}" name="pages[]" value="{{ $page[0] }}" @if($pages[$page[0]][2]==1) checked @endif>
                            <label class="custom-control-label" for="{{$category[0]}}__{{ $loop->iteration }}">{{ $page[1] }}</label>
                          </div>
                        @endforeach
                      </div>
                      <hr>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-4 col-md-5 col-sm-12">
                <a href="/company/user" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-4 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-4 col-md-5 col-sm-12">
                <input type="hidden" name="id" value="{{ encode($user->user_id, 'user_id') }}" readonly>
                <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="修改">
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('companyUser');

  function checkAll(id){
      $("."+id).each(function(){
          $(this).prop('checked',true);
      });
  }

  function uncheckAll(id){
      $("."+id).each(function(){
          $(this).prop('checked',false);
      });
  }
</script>
@endsection
