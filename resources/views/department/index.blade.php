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
          <h6 class="h2 text-white d-inline-block mb-0">校区设置</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">公司管理</li>
              <li class="breadcrumb-item active">校区设置</li>
            </ol>
          </nav>
        </div>
        <div class="col-6 text-right">
          <a href="department/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加校区">
            <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
            <span class="btn-inner--text">添加校区</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card main_card mb-4" style="display:none">
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:80px;'>序号</th>
                <th style='width:150px;'>名称</th>
                <th style='width:205px;'>电话1</th>
                <th style='width:205px;'>电话2</th>
                <th style='width:489px;'>地址</th>
                <th style='width:188px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="6">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="创建时间：{{ $row->department_createtime }}">
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td title="名称：{{ $row->department_name }}">{{ $row->department_name }}</td>
                <td title="电话1：{{ $row->department_phone1 }}">{{ $row->department_phone1 }}</td>
                <td title="电话2：{{ $row->department_phone2 }}">{{ $row->department_phone2 }}</td>
                <td title="地址：{{ $row->department_location }}">{{ $row->department_location }}</td>
                <td>
                  <form action="department/{{$row->department_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/department/{{$row->department_id}}/edit'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                    {{ deleteConfirm($row->department_id, ["校区名称：".$row->department_name]) }}
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('department');
</script>
@endsection
