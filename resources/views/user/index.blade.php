@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">用户管理</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">人事管理</li>
              <li class="breadcrumb-item active">用户管理</li>
            </ol>
          </nav>
        </div>
        <div class="col-6 text-right">
          <a href="user/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加校区">
            <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
            <span class="btn-inner--text">添加用户</span>
          </a>
          <a class="btn btn-sm btn-neutral btn-round btn-icon"data-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter">
            <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            <span class="btn-inner--text">搜索</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="collapse @if($filter_status==1) show @endif" id="filter">
        <div class="card mb-4">
          <div class="card-body border-1 p-0 my-1">
            <form action="" method="get">
              <div class="row m-2">
                <div class="col-lg-8 col-md-8 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <input class="form-control" type="text" name="filter1" placeholder="用户姓名..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter2" data-toggle="select">
                        <option value=''>全部校区</option>
                        @foreach ($filter_departments as $filter_department)
                          <option value="{{ $filter_department->department_id }}" @if($request->input('filter2')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter3" data-toggle="select">
                        <option value=''>全部部门</option>
                        @foreach ($filter_sections as $filter_section)
                          <option value="{{ $filter_section->section_id }}" @if($request->input('filter3')==$filter_section->section_id) selected @endif>{{ $filter_section->section_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter4" data-toggle="select">
                        <option value=''>全部岗位</option>
                        @foreach ($filter_positions as $filter_position)
                          <option value="{{ $filter_position->position_id }}" @if($request->input('filter4')==$filter_position->position_id) selected @endif>{{ $filter_position->position_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter5" data-toggle="select">
                        <option value=''>全部等级</option>
                        <option value='1' @if($request->input('filter5')==1) selected @endif>等级1</option>
                        <option value='2' @if($request->input('filter5')==2) selected @endif>等级2</option>
                        <option value='3' @if($request->input('filter5')==3) selected @endif>等级3</option>
                        <option value='4' @if($request->input('filter5')==4) selected @endif>等级4</option>
                        <option value='5' @if($request->input('filter5')==5) selected @endif>等级5</option>
                        <option value='6' @if($request->input('filter5')==6) selected @endif>等级6</option>
                        <option value='7' @if($request->input('filter5')==7) selected @endif>等级7</option>
                        <option value='8' @if($request->input('filter5')==8) selected @endif>等级8</option>
                        <option value='9' @if($request->input('filter5')==9) selected @endif>等级9</option>
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter6" data-toggle="select">
                        <option value=''>跨校区教学</option>
                        <option value='1' @if($request->input('filter6')==1) selected @endif>是</option>
                        <option value='0' @if($request->input('filter6')==0) selected @endif>否</option>
                      </select>
	                </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
                      <input type="submit" class="btn btn-primary btn-block" value="查询">
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
                      <a href="?"><button type="button" class="form-control btn btn-outline-primary btn-block" style="white-space:nowrap; overflow:hidden;">重置</button></a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="card main_card mb-4" style="display:none">
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
                <th style='width:208px;'>操作管理</th>
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
