@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item active">客户设置</li>
@endsection

@section('content')
<div class="container-fluid mt--6">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card mb-3">
        <div class="card-header border-0 p-0 m-2">
          <form action="" method="get" id="filter" name="filter">
            <div class="row m-2">
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input class="form-control" type="text" name="filter1" placeholder=" 客户姓名..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
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
                  <option value=''>全部年级</option>
                  @foreach ($filter_grades as $filter_grade)
                    <option value="{{ $filter_grade->grade_id }}" @if($request->input('filter3')==$filter_grade->grade_id) selected @endif>{{ $filter_grade->grade_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <div class="row">
                  <div class="col-6">
                    <input type="submit" class="btn btn-primary btn-block" value="查询">
                  </div>
                  <div class="col-6">
                    <a href="?"><button type="button" class="btn btn-outline-primary btn-block">重置</button></a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card main_card" style="display:none">
        <!-- Card header -->
        <div class="card-header border-0">
          <div class="row">
            <div class="col-6">
              <h2 class="mb-0">客户列表</h2>
            </div>
            <div class="col-6 text-right">
              <a href="/customer/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加客户">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加客户</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-flush table-hover text-center">
            <thead class="thead-light">
              <tr>
                <th style='width:6%;'>序号</th>
                <th style='width:12%;'>客户姓名</th>
                <th style='width:8%;'>客户校区</th>
                <th style='width:12%;'>联系电话</th>
                <th style='width:12%;'>学生姓名</th>
                <th style='width:8%;'>学生年级</th>
                <th style='width:8%;'>负责人</th>
                <th style='width:8%;'>跟进次数</th>
                <th style='width:8%;'>签约状态</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr><td colspan="10">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="微信号：{{ $row->customer_wechat }}。来源类型：{{ $row->source_name }}。客户关系：{{ $row->customer_relationship }}。意向课程：{{ $row->course_name }}。备注：{{ $row->customer_remark }}">
                <td class="p-2">{{ $startIndex+$loop->iteration }}</td>
                <td class="p-2">{{ $row->customer_name }}</td>
                <td class="p-2">{{ $row->department_name }}</td>
                <td class="p-2">{{ $row->customer_phone }}</td>
                <td class="p-2">{{ $row->customer_student_name }}</td>
                <td class="p-2">{{ $row->grade_name }}</td>
                <td class="p-2">{{ $row->user_name }}</td>
                <td class="p-2">{{ $row->customer_follow_time }}</td>
                <td class="p-2">@if($row->customer_conversed==1) <span style="color:green">已签约</span> @else <span style="color:red">未签约</span> @endif</td>
                <td class="p-2">
                  <form action="customer/{{$row->customer_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/customer/{{$row->customer_id}}'><button type="button" class="btn btn-primary btn-sm">查看详情</button></a>
                    {{ deleteConfirm($row->customer_id, ["客户名称：".$row->customer_name]) }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ pageLink($currentPage, $totalPage, $request) }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-2');
  navbarActive('navbar-2');
  linkActive('customer');
</script>
@endsection
