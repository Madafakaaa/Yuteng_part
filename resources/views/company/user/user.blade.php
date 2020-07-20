@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">用户管理</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">公司管理</li>
    <li class="breadcrumb-item active">用户管理</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="/company/user/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加用户">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">添加用户</span>
      </a>
      <a class="btn btn-sm btn-neutral btn-round btn-icon"data-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter">
        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
        <span class="btn-inner--text">搜索</span>
      </a>
      <button class="btn btn-sm btn-outline-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="批量删除" onclick="batchDeleteConfirm('/company/user/delete', '确认批量删除所选用户？')">
        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
        <span class="btn-inner--text">批量删除</span>
      </button>
    </div>
  </div>
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
                        <option value='2' @if($request->input('filter6')==2) selected @endif>否</option>
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
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:70px;'>序号</th>
                <th style='width:90px;'>姓名</th>
                <th style='width:250px;'></th>
                <th style='width:100px;'>账号</th>
                <th style='width:100px;'>校区</th>
                <th style='width:140px;'>部门</th>
                <th style='width:140px;'>岗位</th>
                <th style='width:70px;'>等级</th>
                <th style='width:100px;'>跨校区教学</th>
                <th style='width:140px;'>手机</th>
                <th style='width:149px;'>微信</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="12">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($row->user_id, 'user_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>
                  {{ $row->user_name }}&nbsp;
                  @if($row->user_gender=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td>
                  <a href='/user/{{$row->user_id}}'><button type="button" class="btn btn-primary btn-sm">详情</button></a>
                  <a href='/company/user/access?id={{encode($row->user_id, 'user_id')}}'><button type="button" class="btn btn-primary btn-sm">权限</button></a>
                  <a href='/company/user/password/restore?id={{encode($row->user_id, 'user_id')}}'><button type="button" class="btn btn-primary btn-sm">密码重置</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/company/user/delete?id={{encode($row->user_id, 'user_id')}}', '确认删除用户？')">删除</button>
                </td>
                <td>{{ $row->user_id }}</td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->section_name }}</td>
                <td>{{ $row->position_name }}</td>
                <td>等级 {{ $row->position_level }}</td>
                @if($row->user_cross_teaching==1)
                  <td><span style="color:green;">是</span></td>
                @else
                  <td><span style="color:red;">否</span></td>
                @endif
                <td>{{ $row->user_phone }}</td>
                <td>{{ $row->user_wechat }}</td>
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('companyUser');
</script>
@endsection
