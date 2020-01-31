@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item"><a href="/user">用户列表</a></li>
    <li class="breadcrumb-item active">用户详情</li>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-8 col-sm-12 card-wrapper ct-example">
      <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-badge mr-2"></i>用户详情</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>档案文件</a>
          </li>
        </ul>
      </div>
      <div class="card shadow">
        <div class="card-body">
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">账号</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->user_id }}" readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">姓名</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->user_name }}" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">性别</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->user_gender }}" readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">校区</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->department_name }}" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">部门</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->section_name }}" readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">岗位</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->position_name }}(等级 {{ $user->position_level }})" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">入职时间</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->user_entry_date }}" readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">跨校区教学</label>
                    @if($user->user_cross_teaching==1)
                      <input class="form-control form-control-sm" type="text" value="是" readonly>
                    @else
                      <input class="form-control form-control-sm" type="text" value="否" readonly>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">手机</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->user_phone }}" readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">微信</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->user_wechat }}" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">添加时间</label>
                    <input class="form-control form-control-sm" type="text" value="{{ $user->user_createtime }}" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <a href="/user/{{ $user->user_id }}/edit"><button class="btn btn-block btn-primary">修改</button></a>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
              <div class="row mb-3 mt--2">
                <div class="col-12">
                  <a href="/archive/create?user_id={{ $user->user_id }}">
                    <button type="button" class="btn btn-sm btn-neutral btn-round btn-icon">添加档案</button>
                  </a>
                </div>
              </div>
              <table class="table align-items-center table-flush table-hover text-center">
                <thead class="thead-light">
                  <tr>
                    <th style='width:10%;'>序号</th>
                    <th style='width:45%;'>档案名称</th>
                    <th style='width:15%;'>文件大小</th>
                    <th>操作管理</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($rows)==0)
                  <tr><td colspan="4">当前没有记录</td></tr>
                  @endif
                  @foreach ($rows as $row)
                  <tr>
                    <td class="p-2">{{ $loop->iteration }}</td>
                    <td class="p-2">{{ $row->archive_name }}</td>
                    <td class="p-2">{{ $row->archive_file_size }}MB</td>
                    <td class="p-2">
                      <form action="/archive/{{$row->archive_id}}" method="POST">
                        @method('DELETE')
                        @csrf
                        <a href='/archive/{{$row->archive_id}}'><button type="button" class="btn btn-primary btn-sm">下载档案</button></a>
                        {{ deleteConfirm($row->archive_id, ["档案名称：".$row->archive_name]) }}
                      </form>
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
