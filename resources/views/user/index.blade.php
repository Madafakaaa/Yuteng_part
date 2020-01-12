@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
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
                <th style='width:160px;'>姓名</th>
                <th style='width:100px;'>账号</th>
                <th style='width:80px;'>校区</th>
                <th style='width:100px;'>部门</th>
                <th style='width:100px;'>岗位</th>
                <th style='width:80px;'>等级</th>
                <th style='width:100px;'>跨校区教学</th>
                <th style='width:100px;'>手机</th>
                <th style='width:100px;'>微信</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="10">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="入职日期: {{ $row->user_entry_date }}, 手机: {{ $row->user_phone }}, 微信: {{ $row->user_wechat }}。">
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->user_name }}</td>
                <td>{{ $row->user_id }}</td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->section_name }}</td>
                <td>{{ $row->position_name }}</td>
                <td>等级{{ $row->position_level }}</td>
                @if($row->user_cross_teaching==1)
                  <td><span style="color:green;">是</span></td>
                @else
                  <td><span style="color:red;">否</span></td>
                @endif
                <td>{{ $row->user_phone }}</td>
                <td>{{ $row->user_wechat }}</td>
                <td>
                  <form action="/user/{{ $row->user_id }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/user/{{$row->user_id}}'><button type="button" class="btn btn-primary btn-sm">查看详情</button></a>
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
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('link-1-2');
  navbarActive('navbar-1-2');
  linkActive('user');
</script>
@endsection
