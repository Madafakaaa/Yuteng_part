@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">用户列表</li>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card mb-1">
        <div class="card-header border-0 p-0 m-1">
          <form action="" method="get" id="filter" name="filter">
            <div class="row m-2">
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input class="form-control" type="text" name="filter1" placeholder="用户姓名..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter2" data-toggle="select">
                  <option value=''>全部校区</option>
                  @foreach ($filter_departments as $filter_department)
                    <option value="{{ $filter_department->department_id }}" @if($request->input('filter2')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter3" data-toggle="select">
                  <option value=''>全部部门</option>
                  @foreach ($filter_sections as $filter_section)
                    <option value="{{ $filter_section->section_id }}" @if($request->input('filter3')==$filter_section->section_id) selected @endif>{{ $filter_section->section_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter4" data-toggle="select">
                  <option value=''>全部岗位</option>
                  @foreach ($filter_positions as $filter_position)
                    <option value="{{ $filter_position->position_id }}" @if($request->input('filter4')==$filter_position->position_id) selected @endif>{{ $filter_position->position_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <div class="row">
                  <div class="col-6">
                    <input type="submit" class="btn btn-primary btn-block" value="查询">
                  </div>
                  <div class="col-6">
                    <a href="?"><button type="button" class="form-control btn btn-outline-primary btn-block" style="white-space:nowrap; overflow:hidden;">重置</button></a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card main_card mb-4" style="display:none">
        <div class="card-header table-top">
          <div class="row">
            <div class="col-6 text-left">
              <a href="/user/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加用户">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加用户</span>
              </a>
            </div>
          </div>
        </div>
        <!-- Card header -->
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:120px;'>姓名</th>
                <th style='width:100px;'>账号</th>
                <th style='width:100px;'>校区</th>
                <th style='width:140px;'>部门</th>
                <th style='width:140px;'>岗位</th>
                <th style='width:70px;'>等级</th>
                <th style='width:100px;'>跨校区教学</th>
                <th style='width:140px;'>手机</th>
                <th style='width:149px;'>微信</th>
                <th style='width:188px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="10">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="入职日期: {{ $row->user_entry_date }}, 微信: {{ $row->user_wechat }}。">
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td title="姓名：{{ $row->user_name }}">{{ $row->user_name }}</td>
                <td title="账号：{{ $row->user_id }}">{{ $row->user_id }}</td>
                <td title="校区：{{ $row->department_name }}">{{ $row->department_name }}</td>
                <td title="部门：{{ $row->section_name }}">{{ $row->section_name }}</td>
                <td title="岗位：{{ $row->position_name }}">{{ $row->position_name }}</td>
                <td title="等级：等级 {{ $row->position_level }}">等级 {{ $row->position_level }}</td>
                @if($row->user_cross_teaching==1)
                  <td title="跨校区教学：是"><span style="color:green;">是</span></td>
                @else
                  <td title="跨校区教学：否"><span style="color:red;">否</span></td>
                @endif
                <td title="手机：{{ $row->user_phone }}">{{ $row->user_phone }}</td>
                <td title="微信：{{ $row->user_wechat }}">{{ $row->user_wechat }}</td>
                <td>
                  <form action="/user/{{ $row->user_id }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/user/{{$row->user_id}}'><button type="button" class="btn btn-primary btn-sm">用户详情</button></a>
                    <a href='/user/access/{{$row->user_id}}'><button type="button" class="btn btn-primary btn-sm">用户权限</button></a>
                    {{ deleteConfirm($row->user_id, ["用户名称：".$row->user_name]) }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      {{ pageLink($currentPage, $totalPage, $request, $totalNum) }}
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-human');
  navbarActive('navbar-human');
  linkActive('user');
</script>
@endsection
