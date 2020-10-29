@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">面试用户 @if (Session::get('user_access_self')==1) （个人） @endif</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">人事中心</li>
    <li class="breadcrumb-item active">面试用户</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="candidate mb-3">
    <div class="col-auto">
      <a href="/humanResource/candidate/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加面试用户">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">添加候选人</span>
      </a>
      <a href="?">
        <button class="btn btn-sm btn-outline-primary btn-round btn-icon">
          <span class="btn-inner--icon"><i class="fas fa-redo"></i></span>
          <span class="btn-inner--text">重置搜索</span>
        </button>
      </a>
      <button class="btn btn-sm btn-outline-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="批量删除" onclick="batchDeleteConfirm('/humanResource/candidate/delete', '确认批量删除所选面试用户？')">
        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
        <span class="btn-inner--text">批量删除</span>
      </button>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header p-1" style="border-bottom:0px;">
          <form action="" method="get" id="filterForm">
            <div class="row m-2">
              <div class="col-7"></div>
              <div class="col-2 text-right"><small class="text-muted font-weight-bold px-3">创建日期：</small></div>
              <div class="col-3">
                <input class="form-control form-control-sm datepicker" name="filter_date" placeholder="创建日期..." autocomplete="off" type="text" @if(isset($filters['filter_date']))) value="{{ $filters['filter_date'] }}" @endif onChange="form_submit('filterForm')">
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:50px;'>序号</th>
                <th style='width:80px;'>候选人姓名</th>
                <th style='width:120px;'>求职岗位</th>
                <th style='width:100px;'>手机</th>
                <th style='width:100px;'>微信</th>
                <th style='width:100px;'>面试官</th>
                <th style='width:100px;'>创建用户</th>
                <th style='width:100px;'>创建日期</th>
                <th style='width:140px;'>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($candidates)==0)
              <tr class="text-center"><td colspan="9">当前没有记录</td></tr>
              @endif
              @foreach ($candidates as $candidate)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($candidate['candidate_id'], 'candidate_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <a href="/candidate?id={{encode($candidate['candidate_id'], 'candidate_id')}}">{{ $candidate['candidate_name'] }}</a>&nbsp;
                  @if($candidate['candidate_gender']=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td>{{ $candidate['candidate_position'] }}</td>
                <td>{{ $candidate['candidate_phone'] }}</td>
                <td>{{ $candidate['candidate_wechat'] }}</td>
                <td><a href="/user?id={{encode($candidate['user_id'],'user_id')}}">{{ $candidate['user_name'] }}</a> [ {{ $candidate['position_name'] }} ]</td>
                <td><a href="/user?id={{encode($candidate['create_user_id'],'user_id')}}">{{ $candidate['create_user_name'] }}</a> [ {{ $candidate['create_user_position_name'] }} ]</td>
                <td>{{ $candidate['candidate_create_time'] }}</td>
                <td>
                  <a href='/files/archive/{{$candidate['archive_path']}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">查看简历</button></a>
                  <a href='/humanResource/candidate/upgrade?id={{encode($candidate['candidate_id'], 'candidate_id')}}'><button type="button" class="btn btn-primary btn-sm">入职</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/humanResource/candidate/delete?id={{encode($candidate['candidate_id'], 'candidate_id')}}', '确认删除该候选人？')">删除</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-humanResource');
  navbarActive('navbar-humanResource');
  linkActive('humanResourceCandidate');
</script>
@endsection
